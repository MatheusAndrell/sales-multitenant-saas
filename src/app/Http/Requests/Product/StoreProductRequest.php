<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage products');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número válido.',
            'price.min' => 'O preço não pode ser negativo.',
            'stock_quantity.required' => 'A quantidade em estoque é obrigatória.',
        ];
    }
}
