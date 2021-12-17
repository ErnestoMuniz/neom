<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'edit_users'
        ]);
        Permission::create([
            'name' => 'edit_roles'
        ]);
        Permission::create([
            'name' => 'edit_permissions'
        ]);
        Permission::create([
            'name' => 'view_onus'
        ]);
        Permission::create([
            'name' => 'edit_onus'
        ]);
        $permissions = Permission::all()->pluck('id')->toArray();
        $admin_role = Role::create([
            'name' => 'Admin'
        ]);
        $admin_role->permissions()->attach($permissions);
        $admin_user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);
        $admin_user->role_id = $admin_role->id;
        $admin_user->save();
    }
}
