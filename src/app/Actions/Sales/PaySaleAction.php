<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class PaySaleAction
{
  public function execute(int $saleId, int $tenantId): Sale
  {
    return DB::transaction(function () use ($saleId, $tenantId) {

      $sale = Sale::where('tenant_id', $tenantId)
        ->where('id', $saleId)
        ->where('status', 'pending')
        ->with('items')
        ->lockForUpdate()
        ->firstOrFail();

      if ($sale->items->isEmpty()) {
        throw new Exception('Cannot pay a sale without items.');
      }

      foreach ($sale->items as $item) {

        $product = Product::where('tenant_id', $tenantId)
          ->where('id', $item->product_id)
          ->lockForUpdate()
          ->firstOrFail();

        if ($product->stock_quantity < $item->quantity) {
          throw new Exception(
            "Insufficient stock for product {$product->name}"
          );
        }

        $product->decrement('stock_quantity', $item->quantity);
      }

      $sale->update([
        'status' => 'paid',
        'paid_at' => now(),
      ]);

      return $sale->fresh('items');
    });
  }
}
