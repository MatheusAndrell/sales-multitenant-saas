<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        .info {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #2563eb;
            color: white;
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-item {
            display: table-cell;
            width: 33%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f3f4f6;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #ddd;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-cancelled {
            background-color: #f3f4f6;
            color: #374151;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Vendas</h1>
        <div class="info">
            Gerado em: {{ $generated_at }}
            @if($filters['start_date'] || $filters['end_date'])
                | Período: 
                {{ $filters['start_date'] ? date('d/m/Y', strtotime($filters['start_date'])) : 'Início' }} 
                até 
                {{ $filters['end_date'] ? date('d/m/Y', strtotime($filters['end_date'])) : 'Hoje' }}
            @endif
        </div>
    </div>

    <!-- Resumo -->
    <div class="section">
        <div class="section-title">Resumo Geral</div>
        <div class="summary">
            <div class="summary-item">
                <div class="summary-label">Total de Vendas</div>
                <div class="summary-value">{{ $summary['total_sales'] }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Valor Total</div>
                <div class="summary-value">R$ {{ number_format($summary['total_amount'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Ticket Médio</div>
                <div class="summary-value">R$ {{ number_format($summary['average_sale'], 2, ',', '.') }}</div>
            </div>
        </div>
        <div class="summary">
            <div class="summary-item">
                <div class="summary-label">Total Pago</div>
                <div class="summary-value" style="color: #059669;">R$ {{ number_format($summary['total_paid'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pendente</div>
                <div class="summary-value" style="color: #d97706;">R$ {{ number_format($summary['total_pending'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Canceladas</div>
                <div class="summary-value" style="color: #6b7280;">{{ $summary['total_cancelled'] }}</div>
            </div>
        </div>
    </div>

    <!-- Top Produtos -->
    @if($top_products->count() > 0)
    <div class="section">
        <div class="section-title">Top 10 Produtos</div>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th style="text-align: center;">Quantidade Vendida</th>
                    <th style="text-align: right;">Receita Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td style="text-align: center;">{{ $product->total_quantity }}</td>
                    <td style="text-align: right;">R$ {{ number_format($product->total_revenue, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Top Clientes -->
    @if($top_customers->count() > 0)
    <div class="section">
        <div class="section-title">Top 10 Clientes</div>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th style="text-align: center;">Compras</th>
                    <th style="text-align: right;">Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td style="text-align: center;">{{ $customer->total_purchases }}</td>
                    <td style="text-align: right;">R$ {{ number_format($customer->total_spent, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Detalhamento de Vendas -->
    <div class="section">
        <div class="section-title">Detalhamento de Vendas</div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th style="text-align: right;">Valor</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->customer->name }}</td>
                    <td style="text-align: right;">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge status-{{ $sale->status }}">
                            {{ $sale->status === 'pending' ? 'Pendente' : ($sale->status === 'paid' ? 'Paga' : 'Cancelada') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Este relatório foi gerado automaticamente pelo sistema Sales Multitenant SaaS
    </div>
</body>
</html>
