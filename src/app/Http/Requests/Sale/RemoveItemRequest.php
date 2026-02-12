<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class RemoveItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sale_id' => 'required|integer|exists:sales,id',
            'item_id' => 'required|integer|exists:sale_items,id',
        ];
    }

    public function messages(): array
    {
        return [
            'sale_id.required' => 'O ID da venda é obrigatório.',
            'sale_id.exists' => 'A venda não existe.',
            'item_id.required' => 'O ID do item é obrigatório.',
            'item_id.exists' => 'O item não existe.',
        ];
    }
}
