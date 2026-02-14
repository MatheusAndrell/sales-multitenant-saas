<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ValidationSmokeTest extends TestCase
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
            'name' => 'Tenant Teste',
            'slug' => 'tenant-teste-' . Str::random(6),
            'email' => 'tenant' . Str::random(6) . '@teste.com',
            'cnpj' => (string) random_int(10000000000000, 99999999999999),
            'is_active' => true,
        ]);
    }

    private function createUserWithRole(Tenant $tenant, string $role): User
    {
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $user->assignRole($role);
        return $user;
    }

    public function test_customer_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Admin da Loja');

        $response = $this->actingAs($user)->postJson('/api/customers/store', [
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_product_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Admin da Loja');

        $response = $this->actingAs($user)->postJson('/api/products/store', [
            'name' => '',
            'price' => -10,
            'stock_quantity' => -1
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price', 'stock_quantity']);
    }

    public function test_sale_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Vendedor');

        $response = $this->actingAs($user)->postJson('/api/sales/store', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['customer_id']);
    }

    public function test_add_item_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Vendedor');

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cliente Teste',
        ]);

        $sale = Sale::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 0,
        ]);

        $response = $this->actingAs($user)->postJson("/api/sales/item/{$sale->id}", [
            'product_id' => null,
            'quantity' => 0
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_id', 'quantity']);
    }

    public function test_update_customer_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Admin da Loja');

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cliente Atual',
            'email' => 'cliente@teste.com',
        ]);

        $response = $this->actingAs($user)->putJson("/api/customers/update/{$customer->id}", [
            'email' => 'email-invalido'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_update_product_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Admin da Loja');

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Produto Atual',
            'price' => 10.50,
            'stock_quantity' => 10,
        ]);

        $response = $this->actingAs($user)->putJson("/api/products/update/{$product->id}", [
            'price' => -1,
            'stock_quantity' => -5
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price', 'stock_quantity']);
    }

    public function test_store_user_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $user = $this->createUserWithRole($tenant, 'Admin da Loja');

        $response = $this->actingAs($user)->postJson('/api/users/store', [
            'name' => '',
            'email' => 'email-invalido',
            'password' => '123',
            'password_confirmation' => '456',
            'role' => 'Invalido'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
    }

    public function test_update_user_validation_rules(): void
    {
        $tenant = $this->createTenant();
        $admin = $this->createUserWithRole($tenant, 'Admin da Loja');
        $targetUser = User::factory()->create(['tenant_id' => $tenant->id]);

        $response = $this->actingAs($admin)->putJson("/api/users/update/{$targetUser->id}", [
            'email' => 'email-invalido',
            'password' => '123',
            'password_confirmation' => '456',
            'role' => 'Invalido'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password', 'role']);
    }
}
