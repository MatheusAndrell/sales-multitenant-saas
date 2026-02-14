<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Jobs\GenerateSalesReportJob;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportsAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    private function createTenant(): Tenant
    {
        return Tenant::create([
            'name' => 'Tenant Relatorio',
            'slug' => 'tenant-relatorio-' . Str::random(6),
            'email' => 'tenant-' . Str::random(6) . '@teste.com',
            'cnpj' => (string) random_int(10000000000000, 99999999999999),
            'is_active' => true,
        ]);
    }

    public function test_report_requires_permission(): void
    {
        $tenant = $this->createTenant();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $user->assignRole('Vendedor');

        $response = $this->actingAs($user)->postJson('/api/reports/sales', [
            'email' => 'relatorio@teste.com'
        ]);

        $response->assertStatus(403);
    }

    public function test_report_job_is_dispatched_for_admin(): void
    {
        Queue::fake();

        $tenant = $this->createTenant();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $user->assignRole('Admin da Loja');

        $response = $this->actingAs($user)->postJson('/api/reports/sales/email', [
            'email' => 'relatorio@teste.com'
        ]);

        $response->assertStatus(202);
        Queue::assertPushed(GenerateSalesReportJob::class);
    }
}
