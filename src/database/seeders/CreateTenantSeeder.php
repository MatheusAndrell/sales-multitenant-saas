<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class CreateTenantSeeder extends Seeder
{
    public function run()
    {
        Tenant::firstOrCreate(
            ['cnpj' => '12345678000190'],
            [
                'name' => 'Loja Padrão',
                'slug' => 'loja-padrao',
                'email' => 'contato@loja.com',
                'phone' => '(11) 99999-9999',
                'address' => 'Rua Padrão, 123, São Paulo, SP',
                'primary_color' => '#2563eb',
                'secondary_color' => '#1e40af',
                'is_active' => true,
            ]
        );
    }
}
