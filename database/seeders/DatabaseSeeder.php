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
