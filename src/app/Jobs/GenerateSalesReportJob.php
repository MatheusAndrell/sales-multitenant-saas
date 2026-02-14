<?php

namespace App\Jobs;

use App\DTOs\Sales\SalesReportDTO;
use App\Actions\Sales\GenerateSalesReportAction;
use App\Mail\SalesReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class GenerateSalesReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SalesReportDTO $reportDTO
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GenerateSalesReportAction $action): void
    {
        try {
            Log::info('Starting sales report generation', [
                'tenant_id' => $this->reportDTO->tenant_id,
                'email' => $this->reportDTO->email
            ]);

            // Gerar dados do relatório
            $reportData = $action->execute($this->reportDTO);

            // Gerar PDF
            $pdf = Pdf::loadView('reports.sales', $reportData)
                ->setPaper('a4', 'portrait');

            // Enviar por e-mail
            Mail::to($this->reportDTO->email)
                ->send(new SalesReportMail($pdf->output(), $reportData));

            Log::info('Sales report sent successfully', [
                'tenant_id' => $this->reportDTO->tenant_id,
                'email' => $this->reportDTO->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate sales report', [
                'tenant_id' => $this->reportDTO->tenant_id,
                'email' => $this->reportDTO->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Sales report job failed permanently', [
            'tenant_id' => $this->reportDTO->tenant_id,
            'email' => $this->reportDTO->email,
            'error' => $exception->getMessage()
        ]);
    }
}
