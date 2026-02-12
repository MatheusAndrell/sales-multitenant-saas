<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Exception;

class CancelSaleAction
{
    public function execute(Sale $sale, int $tenantId): Sale
    {
        return DB::transaction(function () use ($sale, $tenantId) {

            if ($sale->tenant_id !== $tenantId) {
                throw new Exception('Unauthorized to cancel this sale.');
            }

            if (!in_array($sale->status, ['pending', 'paid'])) {
                throw new Exception('Only pending or paid sales can be cancelled.');
            }

            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->increment('stock_quantity', $item->quantity);
            }

            $sale->update([
                'status' => 'cancelled',
                'total_amount' => 0
            ]);

            return $sale;
        });
    }
}
