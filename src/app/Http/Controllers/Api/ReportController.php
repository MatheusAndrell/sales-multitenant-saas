<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\GenerateReportRequest;
use App\DTOs\Sales\SalesReportDTO;
use App\Actions\Sales\GenerateSalesReportAction;
use App\Jobs\GenerateSalesReportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Get sales report data (synchronous - for preview/download)
     */
    public function getSalesReport(GenerateReportRequest $request, GenerateSalesReportAction $action): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $dto = SalesReportDTO::fromRequest(
                data: $request->validated(),
                tenant_id: $user->tenant_id
            );

            $reportData = $action->execute($dto);

            return response()->json([
                'message' => 'Relatório gerado com sucesso.',
                'data' => $reportData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao gerar relatório.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate and email sales report (asynchronous - with queue)
     */
    public function emailSalesReport(GenerateReportRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $dto = SalesReportDTO::fromRequest(
                data: $request->validated(),
                tenant_id: $user->tenant_id
            );

            // Dispatch job to queue
            GenerateSalesReportJob::dispatch($dto);

            return response()->json([
                'message' => 'Relatório está sendo gerado e será enviado para o e-mail informado em breve.',
                'email' => $dto->email
            ], 202); // 202 Accepted
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar solicitação.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
