<?php

namespace App\DTOs\Sales;

class AddItemToSaleDTO
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public int $tenant_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: $data['product_id'],
            quantity: $data['quantity'],
            tenant_id: auth()->user()->tenant_id
        );
    }
}
