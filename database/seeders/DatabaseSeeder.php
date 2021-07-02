<?php

namespace Database\Seeders;

use App\Models\Olt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin_user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);
        $admin_role = Role::create(['name' => 'Administrator']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_olts']);
        Permission::create(['name' => 'view_admin_panel']);
        $admin_role->givePermissionTo(Permission::all());
        $admin_user->assignRole($admin_role);
    }
}
