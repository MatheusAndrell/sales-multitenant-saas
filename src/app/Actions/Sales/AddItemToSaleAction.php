<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use App\DTOs\Sales\AddItemToSaleDTO;
use Illuminate\Support\Facades\DB;
use Exception;

class AddItemToSaleAction
{
    public function execute(Sale $sale, AddItemToSaleDTO $dto): SaleItem
    {
        return DB::transaction(function () use ($sale, $dto) {

            if ($sale->tenant_id !== $dto->tenant_id) {
                throw new Exception('Unauthorized to modify this sale.');
            }

            if ($sale->status !== 'pending') {
                throw new Exception('Only pending sales can receive items.');
            }

            $product = Product::where('tenant_id', $dto->tenant_id)
                ->findOrFail($dto->product_id);

            $totalPrice = $product->price * $dto->quantity;

            $item = SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $dto->quantity,
                'unit_price' => $product->price,
                'total_price' => $totalPrice
            ]);

            $sale->increment('total_amount', $totalPrice);

            return $item;
        });
    }
}
