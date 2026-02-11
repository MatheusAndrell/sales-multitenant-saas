<?php

namespace App\Http\Controllers\Api;

use App\Actions\Tenant\RegisterTenantAction;
use App\DTOs\Tenant\RegisterTenantData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\RegisterTenantRequest;
use Illuminate\Support\Facades\Log;

class TenantRegistrationController extends Controller
{
    public function __construct(
        private RegisterTenantAction $action
    ) {}

    public function register(RegisterTenantRequest $request)
    {
        try {

            $data = RegisterTenantData::fromArray($request->validated());

            $result = $this->action->execute($data);

            return response()->json([
                'success' => true,
                'message' => 'Tenant criado com sucesso.',
                'data' => [
                    'tenant' => $result['tenant'],
                    'user' => $result['user'],
                    'token' => $result['token'],
                ]
            ], 201);

        } catch (\Throwable $e) {

            Log::error('Erro ao registrar tenant', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao criar tenant.'
            ], 500);
        }
    }
}
