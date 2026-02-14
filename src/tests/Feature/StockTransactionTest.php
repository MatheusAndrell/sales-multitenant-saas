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

class StockTransactionTest extends TestCase
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
            'name' => 'Tenant Estoque',
            'slug' => 'tenant-estoque-' . Str::random(6),
            'email' => 'tenant-' . Str::random(6) . '@teste.com',
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

    public function test_stock_decreases_on_pay(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, 'Vendedor');

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cliente Estoque',
        ]);

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Produto Estoque',
            'price' => 10.00,
            'stock_quantity' => 10,
        ]);

        $sale = Sale::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 20.00,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 10.00,
            'total_price' => 20.00,
        ]);

        $response = $this->actingAs($user)->postJson("/api/sales/pay/{$sale->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_stock_returns_on_cancel_paid_sale(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant, 'Vendedor');

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cliente Cancelamento',
        ]);

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Produto Cancelamento',
            'price' => 15.00,
            'stock_quantity' => 10,
        ]);

        $sale = Sale::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 30.00,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 15.00,
            'total_price' => 30.00,
        ]);

        $this->actingAs($user)->postJson("/api/sales/pay/{$sale->id}");

        $response = $this->actingAs($user)->postJson("/api/sales/cancel/{$sale->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);
    }
}
