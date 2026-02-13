<?php

namespace App\Actions\Product;

use App\DTOs\Product\ProductData;
use App\Models\Product;

class CreateProductAction
{
    public function execute(ProductData $data): Product
    {
        return Product::create([
            'name' => $data->name,
            'description' => $data->description,
            'price' => $data->price,
            'stock_quantity' => $data->stockQuantity,
            'category' => $data->category,
        ]);
    }
}
