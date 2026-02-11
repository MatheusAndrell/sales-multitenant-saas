<?php

namespace App\DTOs\Tenant;

class RegisterTenantData
{
    public function __construct(
        public string $tenantName,
        public string $tenantEmail,
        public string $cnpj,
        public string $adminName,
        public string $adminEmail,
        public string $password,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tenantName: $data['tenant_name'],
            tenantEmail: $data['tenant_email'],
            cnpj: $data['cnpj'],
            adminName: $data['admin_name'],
            adminEmail: $data['admin_email'],
            password: $data['password'],
        );
    }
}

