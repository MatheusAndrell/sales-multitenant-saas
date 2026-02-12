<?php

namespace App\DTOs\Sales;

class PaySaleDTO
{
    public function __construct(
        public int $sale_id,
        public int $tenant_id
    ) {}
}
