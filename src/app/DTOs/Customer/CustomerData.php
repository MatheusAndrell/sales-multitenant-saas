<?php

namespace App\DTOs\Customer;

class CustomerData
{
  public function __construct(
    public string $name,
    public ?string $email,
    public ?string $phone,
    public ?string $document,
    public int $tenant_id
  ) {
  }

  public static function fromArray(array $data): self
  {
    return new self(
      name: $data['name'],
      email: $data['email'] ?? null,
      phone: $data['phone'] ?? null,
      document: $data['document'] ?? null,
      tenant_id: auth()->user()->tenant_id
    );
  }

  public function toArray(): array
  {
    return get_object_vars($this);
  }
}
