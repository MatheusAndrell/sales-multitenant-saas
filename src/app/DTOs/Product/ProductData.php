<?php

namespace App\DTOs\Product;

class ProductData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public int $stockQuantity,
        public ?string $category = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            price: $data['price'],
            stockQuantity: $data['stock_quantity'],
            category: $data['category'] ?? null,
        );
    }
}
