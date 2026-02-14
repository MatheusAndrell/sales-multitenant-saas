<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #2563eb; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">Relatório de Vendas</h1>
        <p style="margin: 10px 0 0 0; font-size: 14px;">Seu relatório está pronto!</p>
    </div>
    
    <div style="background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <p style="margin-top: 0;">Olá,</p>
        
        <p>Seu relatório de vendas foi gerado com sucesso e está anexado a este e-mail.</p>
        
        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb;">
            <h2 style="margin-top: 0; color: #2563eb; font-size: 18px;">Resumo do Relatório</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <strong>Total de Vendas:</strong>
                    </td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                        {{ $summary['total_sales'] }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <strong>Valor Total:</strong>
                    </td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                        R$ {{ number_format($summary['total_amount'], 2, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                        <strong>Ticket Médio:</strong>
                    </td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                        R$ {{ number_format($summary['average_sale'], 2, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">
                        <strong>Gerado em:</strong>
                    </td>
                    <td style="padding: 10px 0; text-align: right;">
                        {{ $generated_at }}
                    </td>
                </tr>
            </table>
            
            @if($filters['start_date'] || $filters['end_date'] || $filters['status'])
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;"><strong>Filtros aplicados:</strong></p>
                <ul style="margin: 0; padding-left: 20px; color: #6b7280; font-size: 14px;">
                    @if($filters['start_date'])
                        <li>Data inicial: {{ date('d/m/Y', strtotime($filters['start_date'])) }}</li>
                    @endif
                    @if($filters['end_date'])
                        <li>Data final: {{ date('d/m/Y', strtotime($filters['end_date'])) }}</li>
                    @endif
                    @if($filters['status'])
                        <li>Status: {{ $filters['status'] }}</li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
        
        <p style="color: #6b7280; font-size: 14px; margin-bottom: 0;">
            O relatório completo em PDF está anexado a este e-mail com todos os detalhes das vendas, produtos mais vendidos e principais clientes.
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px;">
        <p>Este é um e-mail automático do sistema Sales Multitenant SaaS</p>
    </div>
</body>
</html>
