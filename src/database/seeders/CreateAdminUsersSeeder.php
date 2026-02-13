<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class CreateAdminUsersSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::first();

        if (!$tenant) {
            return;
        }

        // Admin da Loja
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'tenant_id' => $tenant->id,
            ]
        );
        $admin->assignRole('Admin da Loja');

        // Vendedor
        $vendedor = User::firstOrCreate(
            ['email' => 'vendedor@vendedor.com'],
            [
                'name' => 'Vendedor',
                'password' => Hash::make('vendedor123'),
                'tenant_id' => $tenant->id,
            ]
        );
        $vendedor->assignRole('Vendedor');
    }
}
