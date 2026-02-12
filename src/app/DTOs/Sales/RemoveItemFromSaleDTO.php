<?php

namespace App\DTOs\Sales;

class RemoveItemFromSaleDTO
{
    public function __construct(
        public int $sale_id,
        public int $item_id,
        public int $tenant_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sale_id: $data['sale_id'],
            item_id: $data['item_id'],
            tenant_id: auth()->user()->tenant_id
        );
    }

    public static function fromRoute(array $data): self
    {
        return new self(
            sale_id: $data['sale_id'],
            item_id: $data['item_id'],
            tenant_id: auth()->user()->tenant_id
        );
    }
}
