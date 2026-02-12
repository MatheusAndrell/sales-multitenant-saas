<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\DTOs\Product\ProductData;

class UpdateProductAction
{
    public function execute(Product $product, ProductData $data): Product
    {
        $product->update([
            'name' => $data->name,
            'description' => $data->description,
            'price' => $data->price,
            'stock_quantity' => $data->stockQuantity,
        ]);

        return $product;
    }
}
