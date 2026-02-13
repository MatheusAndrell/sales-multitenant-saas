<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Product\CreateProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Actions\Product\ListProductsAction;
use App\DTOs\Product\ProductData;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct(
        private CreateProductAction $createAction,
        private UpdateProductAction $updateAction,
        private DeleteProductAction $deleteAction,
        private ListProductsAction $listAction,
    ) {
    }

    public function index()
    {
        return response()->json($this->listAction->execute());
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function store(StoreProductRequest $request)
    {
        try {

            $data = ProductData::fromArray($request->validated());

            $product = $this->createAction->execute($data);

            return response()->json([
                'message' => 'Produto criado com sucesso.',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {

            \Log::error('Erro ao criar produto', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao criar produto.'
            ], 500);
        }
    }



    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $data = ProductData::fromArray($request->validated());
            
            $updated = $this->updateAction->execute(
                $product,
                $data
            );

            return response()->json([
                'message' => 'Produto atualizado com sucesso.',
                'data' => $updated
            ]);

        } catch (\Exception $e) {

            \Log::error('Erro ao atualizar produto', [
                'error' => $e->getMessage(),
                'product_id' => $product->id
            ]);

            return response()->json([
                'message' => 'Erro interno ao atualizar produto.'
            ], 500);
        }
    }


    public function destroy(Product $product)
    {
        $this->deleteAction->execute($product);

        return response()->json(['message' => 'Produto removido com sucesso']);
    }
}
