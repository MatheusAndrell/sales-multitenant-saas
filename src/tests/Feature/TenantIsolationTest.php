<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    private function createTenant(string $prefix): Tenant
    {
        return Tenant::create([
            'name' => "Tenant {$prefix}",
            'slug' => "tenant-{$prefix}-" . Str::random(6),
            'email' => "tenant-{$prefix}-" . Str::random(6) . '@teste.com',
            'cnpj' => (string) random_int(10000000000000, 99999999999999),
            'is_active' => true,
        ]);
    }

    private function createUser(Tenant $tenant, string $role): User
    {
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $user->assignRole($role);
        return $user;
    }

    public function test_customer_isolated_by_tenant(): void
    {
        $tenantA = $this->createTenant('A');
        $tenantB = $this->createTenant('B');

        $customerA = Customer::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Cliente A',
            'email' => 'cliente-a@teste.com',
        ]);

        $userB = $this->createUser($tenantB, 'Vendedor');

        $response = $this->actingAs($userB)->getJson("/api/customers/{$customerA->id}");
        $response->assertStatus(404);
    }

    public function test_product_isolated_by_tenant(): void
    {
        $tenantA = $this->createTenant('A');
        $tenantB = $this->createTenant('B');

        $productA = Product::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Produto A',
            'price' => 10.00,
            'stock_quantity' => 10,
        ]);

        $userB = $this->createUser($tenantB, 'Vendedor');

        $response = $this->actingAs($userB)->getJson("/api/products/{$productA->id}");
        $response->assertStatus(404);
    }

    public function test_sales_item_add_blocked_for_other_tenant(): void
    {
        $tenantA = $this->createTenant('A');
        $tenantB = $this->createTenant('B');

        $customerA = Customer::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Cliente A',
        ]);

        $productA = Product::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Produto A',
            'price' => 20.00,
            'stock_quantity' => 10,
        ]);

        $saleA = Sale::create([
            'tenant_id' => $tenantA->id,
            'customer_id' => $customerA->id,
            'status' => 'pending',
            'total_amount' => 0,
        ]);

        SaleItem::create([
            'sale_id' => $saleA->id,
            'product_id' => $productA->id,
            'quantity' => 1,
            'unit_price' => 20.00,
            'total_price' => 20.00,
        ]);

        $userB = $this->createUser($tenantB, 'Vendedor');

        $response = $this->actingAs($userB)->postJson("/api/sales/item/{$saleA->id}", [
            'product_id' => $productA->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(404);
    }
}
