<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index() {

        $companies = Company::all(['id', 'name']);
        $roles = Role::all(['id', 'name']);
        return view('user.index', compact('companies', 'roles'));
    }

    public function list(Request $request) {

        $perPage = 10;
        $lastId = $request->get('last_id', null);

        $query = User::with(['company', 'roles'])->orderBy('id', 'asc');

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

        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'company_id' => 'nullable|exists:companies,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => $validated['company_id'] ?? null,
        ]);

        $role = Role::find($validated['role_id']);
        $user->assignRole($role);

        return response()->json(['message' => 'User created successfully']);
    }

    public function update(Request $request, $id) {

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'company_id' => 'nullable|exists:companies,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'company_id' => $validated['company_id'] ?? null,
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $user->password,
        ]);

        $role = Role::findOrFail($validated['role_id']);

        // pastikan guard-nya cocok
        if ($role->guard_name !== 'web') {
            $role->guard_name = 'web';
            $role->save();
        }

        // gunakan nama role, bukan ID
        $user->syncRoles([$role->name]);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id) {

        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
