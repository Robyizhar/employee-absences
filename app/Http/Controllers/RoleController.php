<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index() {
        $permissions = Permission::all();
        return view('role.index', compact('permissions'));
    }

    public function list(Request $request) {
        $perPage = 10;
        $lastId = $request->get('last_id', null);

        $query = Role::with('permissions')->orderBy('id', 'asc');

        if ($lastId) {
            $query->where('id', '>', $lastId);
        }

        $data = $query->take($perPage)->get();

        return response()->json([
            'data' => $data,
            'hasMore' => $data->count() === $perPage
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create(['name' => $request->name]);
        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role berhasil ditambahkan']);
    }

    public function show(Role $role) {
        $role->load('permissions');
        return response()->json($role);
    }

    public function update(Request $request, Role $role) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return response()->json(['message' => 'Role berhasil diperbarui']);
    }

    public function destroy(Role $role) {
        $role->delete();
        return response()->json(['message' => 'Role berhasil dihapus']);
    }
}
