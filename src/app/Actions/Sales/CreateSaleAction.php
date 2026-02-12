<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use App\DTOs\Sales\CreateSaleDTO;

class CreateSaleAction
{
  public function execute(CreateSaleDTO $dto): Sale
  {
    return Sale::create([
      'tenant_id' => $dto->tenant_id,
      'customer_id' => $dto->customer_id,
      'status' => 'pending',
      'total' => 0
    ]);
  }
}
