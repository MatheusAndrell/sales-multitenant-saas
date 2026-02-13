<?php

namespace App\Http\Controllers\Api;

use App\Actions\Sales\AddItemToSaleAction;
use App\Actions\Sales\CancelSaleAction;
use App\Actions\Sales\CreateSaleAction;
use App\Actions\Sales\ListSalesAction;
use App\Actions\Sales\PaySaleAction;
use App\Actions\Sales\RemoveItemFromSaleAction;
use App\DTOs\Sales\CreateSaleDTO;
use App\DTOs\Sales\AddItemToSaleDTO;
use App\DTOs\Sales\PaySaleDTO;
use App\DTOs\Sales\RemoveItemFromSaleDTO;
use App\DTOs\Sales\CancelSaleDTO;
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
        private PaySaleAction $payAction,
        private RemoveItemFromSaleAction $removeItemFromSaleAction,
        private CancelSaleAction $cancelSaleAction,
    ) {

    }

    public function index()
    {
        return response()->json($this->listAction->execute());
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'customer']);

        return response()->json($sale);
    }

    public function store(StoreSaleRequest $request, CreateSaleAction $action)
    {
        try {
            $dto = CreateSaleDTO::fromArray($request->validated());

            $sale = $action->execute($dto);

            return response()->json([
                'message' => 'Venda criada com sucesso.',
                'data' => $sale
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar venda', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao criar venda.'
            ], 500);
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
                'data' => $item->load('product')
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Venda não encontrada ou inacessível para este tenant.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar item na venda', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sale_id' => $sale,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => $e->getMessage() ?: 'Erro interno ao adicionar item na venda.'
            ], 500);
        }
    }

    public function pay(int $sale, PaySaleAction $action)
    {
        try {
            $dto = PaySaleDTO::fromRoute($sale);

            $paidSale = $action->execute($dto->sale_id, $dto->tenant_id);

            return response()->json([
                'message' => 'Venda paga com sucesso.',
                'data' => $paidSale
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Venda não encontrada ou já paga.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao pagar venda', [
                'error' => $e->getMessage(),
                'sale_id' => $sale,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao pagar venda.'
            ], 500);
        }
    }

    public function removeItem(int $sale, int $item, RemoveItemFromSaleAction $action)
    {
        try {
            $saleModel = Sale::where('id', $sale)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->firstOrFail();

            $this->removeItemFromSaleAction->execute($saleModel, $item, auth()->user()->tenant_id);

            return response()->json([
                'message' => 'Item removido com sucesso.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Venda ou item não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao remover item da venda', [
                'error' => $e->getMessage(),
                'sale_id' => $sale,
                'item_id' => $item,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao remover item da venda.'
            ], 500);
        }
    }

    public function cancel(int $sale, CancelSaleAction $action)
    {
        try {
            $saleModel = Sale::where('id', $sale)
                ->where('tenant_id', auth()->user()->tenant_id)
                ->firstOrFail();

            $canceledSale = $action->execute($saleModel, auth()->user()->tenant_id);

            return response()->json([
                'message' => 'Venda cancelada com sucesso.',
                'data' => $canceledSale
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Venda não encontrada.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Erro ao cancelar venda', [
                'error' => $e->getMessage(),
                'sale_id' => $sale,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao cancelar venda.'
            ], 500);
        }
    }

}
