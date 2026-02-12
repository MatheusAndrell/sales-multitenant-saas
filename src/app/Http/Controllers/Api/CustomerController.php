<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Customers\CreateCustomerAction;
use App\Actions\Customers\UpdateCustomerAction;
use App\Actions\Customers\DeleteCustomerAction;
use App\Actions\Customers\ListCustomersAction;
use App\DTOs\Customer\CustomerData;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct(
        private CreateCustomerAction $createAction,
        private UpdateCustomerAction $updateAction,
        private DeleteCustomerAction $deleteAction,
        private ListCustomersAction $listAction,
    ) {
    }

    public function index()
    {
        return response()->json($this->listAction->execute());
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $data = CustomerData::fromArray($request->validated());

            $customer = $this->createAction->execute($data);

            return response()->json([
                'message' => 'Cliente criado com sucesso.',
                'data' => $customer
            ], 201);

        } catch (\Exception $e) {

            \Log::error('Erro ao criar cliente', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'Erro interno ao criar cliente.'
            ], 500);
        }
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $data = CustomerData::fromArray($request->validated());
            
            $updated = $this->updateAction->execute(
                $customer,
                $data
            );

            return response()->json([
                'message' => 'Cliente atualizado com sucesso.',
                'data' => $updated
            ]);

        } catch (\Exception $e) {

            \Log::error('Erro ao atualizar cliente', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return response()->json([
                'message' => 'Erro interno ao atualizar cliente.'
            ], 500);
        }
    }

    public function destroy(Customer $customer)
    {
        $this->deleteAction->execute($customer);

        return response()->json(['message' => 'Cliente removido com sucesso']);
    }
}
