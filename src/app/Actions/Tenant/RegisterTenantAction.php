<?php

namespace App\Actions\Tenant;

use App\DTOs\Tenant\RegisterTenantData;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisterTenantAction
{
    public function execute(RegisterTenantData $data): array
    {
        return DB::transaction(function () use ($data) {

            $slug = $this->generateUniqueSlug($data->tenantName);

            $tenant = Tenant::create([
                'name' => $data->tenantName,
                'slug' => $slug,
                'email' => $data->tenantEmail,
                'cnpj' => preg_replace('/\D/', '', $data->cnpj),

                'is_active' => true,
            ]);

            $admin = User::create([
                'tenant_id' => $tenant->id,
                'name' => $data->adminName,
                'email' => $data->adminEmail,
                'password' => Hash::make($data->password),
            ]);

            $adminRole = Role::where('name', 'Admin da Loja')->first();
            $admin->assignRole($adminRole);

            $token = $admin->createToken('api-token')->plainTextToken;

            return [
                'tenant' => $tenant,
                'user' => $admin,
                'token' => $token,
            ];
        });
    }

    private function generateUniqueSlug(string $name): string
    {
        do {
            $slug = Str::slug($name) . '-' . Str::random(5);
        } while (Tenant::where('slug', $slug)->exists());

        return $slug;
    }
}
