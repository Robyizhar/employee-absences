<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;

class PermissionsDemoSeeder extends Seeder {

    public function run() {
        // delete semua data user, role, permission
        DB::table('users')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'absence_log', 'guard_name' => 'web' ]);
        Permission::create(['name' => 'absence_recap', 'guard_name' => 'web' ]);
        Permission::create(['name' => 'setting', 'guard_name' => 'web' ]);
        Permission::create(['name' => 'master_employees', 'guard_name' => 'web' ]);

        // buat role
        $admin = Role::create(['name' => 'Admin']);
        $dev = Role::create(['name' => 'Maintener']);
        $dev->givePermissionTo(Permission::all());

        // buat user
        $user = User::create([
            'company_id' => 1,
            'username' => 'admin_techcorp',
            'email' => 'admin@techcorp.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($admin);

        $user = User::create([
            'company_id' => 2,
            'username' => 'admin_bizsolutions',
            'email' => 'admin@bizsolutions.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($admin);

        $user = User::create([
            'company_id' => 2,
            'username' => 'maintener',
            'email' => 'maintener@example.com',
            'password' => Hash::make('asdw1234'),
        ]);
        $user->assignRole($dev);
    }
}
