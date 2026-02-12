<?php

namespace App\DTOs\Sales;

class CancelSaleDTO
{
    public function __construct(
        public int $sale_id,
        public int $tenant_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            sale_id: $data['sale_id'],
            tenant_id: auth()->user()->tenant_id
        );
    }

    public static function fromRoute(int $saleId): self
    {
        return new self(
            sale_id: $saleId,
            tenant_id: auth()->user()->tenant_id
        );
    }
}
