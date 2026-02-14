<?php

namespace App\DTOs\Sales;

class SalesReportDTO
{
    public function __construct(
        public readonly int $tenant_id,
        public readonly ?string $start_date = null,
        public readonly ?string $end_date = null,
        public readonly ?string $status = null,
        public readonly ?int $customer_id = null,
        public readonly string $email
    ) {}

    public static function fromRequest(array $data, int $tenant_id): self
    {
        return new self(
            tenant_id: $tenant_id,
            start_date: $data['start_date'] ?? null,
            end_date: $data['end_date'] ?? null,
            status: $data['status'] ?? null,
            customer_id: $data['customer_id'] ?? null,
            email: $data['email']
        );
    }
}
