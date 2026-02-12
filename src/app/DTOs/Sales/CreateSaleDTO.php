<?php

namespace App\DTOs\Sales;

class CreateSaleDTO
{
    public function __construct(
        public int $customer_id,
        public int $tenant_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customer_id: $data['customer_id'],
            tenant_id: auth()->user()->tenant_id
        );
    }
}
