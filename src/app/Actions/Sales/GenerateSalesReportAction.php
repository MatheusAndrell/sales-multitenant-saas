<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use App\DTOs\Sales\SalesReportDTO;
use Illuminate\Support\Facades\DB;

class GenerateSalesReportAction
{
    public function execute(SalesReportDTO $dto): array
    {
        // Query otimizada com eager loading e filtros
        $query = Sale::with(['items.product', 'customer'])
            ->where('tenant_id', $dto->tenant_id);

        // Aplicar filtros
        if ($dto->start_date) {
            $query->whereDate('created_at', '>=', $dto->start_date);
        }

        if ($dto->end_date) {
            $query->whereDate('created_at', '<=', $dto->end_date);
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->customer_id) {
            $query->where('customer_id', $dto->customer_id);
        }

        // Buscar vendas
        $sales = $query->orderBy('created_at', 'desc')->get();

        // Calcular estatísticas agregadas de forma otimizada
        $summary = [
            'total_sales' => $sales->count(),
            'total_amount' => $sales->sum('total_amount'),
            'total_paid' => $sales->where('status', 'paid')->sum('total_amount'),
            'total_pending' => $sales->where('status', 'pending')->sum('total_amount'),
            'total_cancelled' => $sales->where('status', 'cancelled')->count(),
            'average_sale' => $sales->avg('total_amount'),
        ];

        // Produtos mais vendidos (otimizado)
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.tenant_id', $dto->tenant_id)
            ->where('sales.status', '!=', 'cancelled')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Top customers (otimizado)
        $topCustomers = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.tenant_id', $dto->tenant_id)
            ->where('sales.status', '!=', 'cancelled')
            ->select(
                'customers.name',
                DB::raw('COUNT(sales.id) as total_purchases'),
                DB::raw('SUM(sales.total_amount) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return [
            'sales' => $sales,
            'summary' => $summary,
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'filters' => [
                'start_date' => $dto->start_date,
                'end_date' => $dto->end_date,
                'status' => $dto->status,
                'customer_id' => $dto->customer_id,
            ],
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];
    }
}
