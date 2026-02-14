<?php

namespace App\Http\Controllers\Api;

use App\Actions\Dashboard\GetDashboardMetricsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardMetricsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function metrics(DashboardMetricsRequest $request, GetDashboardMetricsAction $action): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $startDate = isset($data['start_date'])
            ? Carbon::parse($data['start_date'])->startOfDay()
            : now()->subDays(6)->startOfDay();

        $endDate = isset($data['end_date'])
            ? Carbon::parse($data['end_date'])->endOfDay()
            : now()->endOfDay();

        $metrics = $action->execute($user->tenant_id, $startDate, $endDate);

        return response()->json([
            'message' => 'Metricas carregadas com sucesso.',
            'data' => $metrics,
        ]);
    }
}
