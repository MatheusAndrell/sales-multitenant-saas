<?php

namespace App\Actions\Dashboard;

use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetDashboardMetricsAction
{
    public function execute(int $tenantId, Carbon $startDate, Carbon $endDate): array
    {
        $cacheKey = sprintf(
            'dashboard:metrics:%d:%s:%s',
            $tenantId,
            $startDate->toDateString(),
            $endDate->toDateString()
        );

        return Cache::store('redis')->remember($cacheKey, now()->addMinutes(5), function () use ($tenantId, $startDate, $endDate) {
        $baseQuery = Sale::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalSales = (clone $baseQuery)->count();
        $totalAmount = (clone $baseQuery)->sum('total_amount');
        $averageSale = $totalSales > 0 ? $totalAmount / $totalSales : 0;

        $salesByStatus = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statusSummary = [
            'pending' => [
                'count' => (int) ($salesByStatus['pending']->count ?? 0),
                'total_amount' => (float) ($salesByStatus['pending']->total_amount ?? 0),
            ],
            'paid' => [
                'count' => (int) ($salesByStatus['paid']->count ?? 0),
                'total_amount' => (float) ($salesByStatus['paid']->total_amount ?? 0),
            ],
            'cancelled' => [
                'count' => (int) ($salesByStatus['cancelled']->count ?? 0),
                'total_amount' => (float) ($salesByStatus['cancelled']->total_amount ?? 0),
            ],
        ];

        $salesPerDay = (clone $baseQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.tenant_id', $tenantId)
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->where('sales.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $topCustomers = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.tenant_id', $tenantId)
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->where('sales.status', '!=', 'cancelled')
            ->select(
                'customers.id',
                'customers.name',
                DB::raw('COUNT(sales.id) as total_purchases'),
                DB::raw('SUM(sales.total_amount) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        $recentSales = (clone $baseQuery)
            ->with('customer')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'customer' => $sale->customer?->name,
                    'total_amount' => $sale->total_amount,
                    'status' => $sale->status,
                    'created_at' => $sale->created_at->toISOString(),
                ];
            });

        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $todaySales = Sale::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$todayStart, $todayEnd]);

        return [
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'summary' => [
                'total_sales' => $totalSales,
                'total_amount' => (float) $totalAmount,
                'average_sale' => (float) $averageSale,
                'sales_today' => (int) $todaySales->count(),
                'amount_today' => (float) $todaySales->sum('total_amount'),
            ],
            'sales_by_status' => $statusSummary,
            'sales_per_day' => $salesPerDay,
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'recent_sales' => $recentSales,
        ];
        });
    }
}
