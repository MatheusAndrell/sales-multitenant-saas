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

            if ($product->stock_quantity <= 0) {
                throw new Exception('Product is out of stock.');
            }

            $existingItem = SaleItem::where('sale_id', $sale->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingItem) {

                $newQuantity = $existingItem->quantity + $dto->quantity;

                if ($newQuantity > $product->stock_quantity) {
                    throw new Exception('Insufficient stock for this product.');
                }

                $sale->decrement('total_amount', $existingItem->total_price);

                $existingItem->update([
                    'quantity' => $newQuantity,
                    'total_price' => $product->price * $newQuantity
                ]);

                $sale->increment('total_amount', $existingItem->total_price);

                return $existingItem;
            }

            if ($dto->quantity > $product->stock_quantity) {
                throw new Exception('Insufficient stock for this product.');
            }

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
