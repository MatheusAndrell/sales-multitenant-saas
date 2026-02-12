<?php

namespace App\Http\Controllers\Api;

use App\Actions\Sales\AddItemToSaleAction;
use App\Actions\Sales\CreateSaleAction;
use App\Actions\Sales\ListSalesAction;
use App\DTOs\Sales\CreateSaleDTO;
use App\DTOs\Sales\AddItemToSaleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\AddItemRequest;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(
        private CreateSaleAction $createAction,
        private ListSalesAction $listAction,
    ) {

    }

    public function index()
    {
        return response()->json($this->listAction->execute());
    }

    public function store(StoreSaleRequest $request, CreateSaleAction $action)
    {
        try {
            $dto = CreateSaleDTO::fromArray($request->validated());

            $sale = $action->execute($dto);

            return response()->json($sale, 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Erro ao criar venda.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function addItem(AddItemRequest $request, int $sale, AddItemToSaleAction $action)
    {
        try {
            $saleModel = Sale::where('id', $sale)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->where('status', 'pending')
                ->firstOrFail();

            $dto = AddItemToSaleDTO::fromArray($request->validated());

            $item = $action->execute($saleModel, $dto);

            return response()->json([
                'message' => 'Item adicionado com sucesso.',
                'data' => $item
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Venda não encontrada ou inacessível para este tenant.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao adicionar item na venda.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

}
