<?php

namespace App\DTOs\User;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public ?string $role = null,
        public int $tenant_id = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            role: $data['role'] ?? null,
            tenant_id: auth()->user()->tenant_id
        );
    }

    public static function fromUpdate(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? null,
            role: $data['role'] ?? null,
            tenant_id: auth()->user()->tenant_id
        );
    }
}
