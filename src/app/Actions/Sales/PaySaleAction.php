<?php
namespace App\Actions\Sales;

use App\Models\Sale;
use App\DTOs\Sales\PaySaleDTO;
use Illuminate\Support\Facades\DB;
use Exception;

class PaySaleAction
{
  public function execute(PaySaleDTO $dto): Sale
  {
    return DB::transaction(function () use ($dto) {

      $sale = Sale::where('tenant_id', $dto->tenant_id)
        ->where('status', 'pending')
        ->with('items.product')
        ->findOrFail($dto->sale_id);

      foreach ($sale->items as $item) {

        if ($item->product->stock < $item->quantity) {
          throw new Exception(
            "Estoque insuficiente para o produto {$item->product->name}"
          );
        }

        $item->product->decrement('stock', $item->quantity);
      }

      $sale->update([
        'status' => 'paid',
        'paid_at' => now()
      ]);

      return $sale;
    });
  }
}
