<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Exception;

class RemoveItemFromSaleAction
{
  public function execute(Sale $sale, int $itemId, int $tenantId): void
  {
    DB::transaction(function () use ($sale, $itemId, $tenantId) {

      if ($sale->tenant_id !== $tenantId) {
        throw new Exception('Unauthorized to modify this sale.');
      }

      if ($sale->status !== 'pending') {
        throw new Exception('Only pending sales can be modified.');
      }

      $item = SaleItem::where('sale_id', $sale->id)
        ->where('id', $itemId)
        ->firstOrFail();

      $sale->decrement('total_amount', $item->total_price);
      $item->delete();
    });
  }
}
