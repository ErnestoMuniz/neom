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
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões
        // Admnistrador
        Permission::create(['name' => 'manageUsers']);
        // N2
        Permission::create(['name' => 'managePops']);
        Permission::create(['name' => 'manageOlts']);
        Permission::create(['name' => 'provisionOnus']);
        Permission::create(['name' => 'removeOnus']);
        // N1
        Permission::create(['name' => 'editOnus']);
        Permission::create(['name' => 'viewOnus']);

        // Criar grupos e atribuir permissões
        // Administrador
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('manageUsers');
        // N2
        $n2 = Role::create(['name' => 'n2']);
        $n2->givePermissionTo('managePops');
        $n2->givePermissionTo('manageOlts');
        $n2->givePermissionTo('provisionOnus');
        $n2->givePermissionTo('removeOnus');
        // N1
        $n1 = Role::create(['name' => 'n1']);
        $n1->givePermissionTo('editOnus');
        $n1->givePermissionTo('viewOnus');

        // Cria usuário admin e atribui grupos
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin'),
        ]);
        $user->assignRole($admin);
        $user->assignRole($n1);
        $user->assignRole($n2);

        // Cria OLT de exemplo
        Olt::factory()->create([
            'nome' => 'OLT-Exemplo',
            'ip' => '0.0.0.0',
            'user' => 'usuario',
            'pass' => 'senha',
            'slot' => '4',
            'pon' => '16',
            'vendor' => 'test',
        ]);
    }
}
