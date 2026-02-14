<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantOneTestDataSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        try {
            $tenant = Tenant::where('slug', 'loja-padrao')->first();

            if (!$tenant) {
                return;
            }

            $baseNow = Carbon::create(2026, 2, 13, 22, 27, 0, 'America/Sao_Paulo');

            // Usuarios adicionais (além dos seeders existentes)
            $this->seedUsers($tenant, $baseNow);

            // Customers (fornecedores tratados como customers)
            $customers = $this->seedCustomers($tenant, $baseNow, 50);

            // Produtos
            $products = $this->seedProducts($tenant, $baseNow, 60);

            // Vendas e itens
            $this->seedSales($tenant, $baseNow, $customers, $products, 120);
        } finally {
            Model::reguard();
        }
    }

    private function seedUsers(Tenant $tenant, Carbon $baseNow): void
    {
        $users = [
            ['name' => 'Ana Souza', 'email' => 'ana@loja.com', 'role' => 'Admin da Loja'],
            ['name' => 'Carlos Pereira', 'email' => 'carlos@loja.com', 'role' => 'Vendedor'],
            ['name' => 'Fernanda Lima', 'email' => 'fernanda@loja.com', 'role' => 'Vendedor'],
            ['name' => 'Marcos Silva', 'email' => 'marcos@loja.com', 'role' => 'Vendedor'],
            ['name' => 'Patricia Gomes', 'email' => 'patricia@loja.com', 'role' => 'Vendedor'],
            ['name' => 'Roberto Alves', 'email' => 'roberto@loja.com', 'role' => 'Vendedor'],
            ['name' => 'Juliana Rocha', 'email' => 'juliana@loja.com', 'role' => 'Vendedor'],
            ['name' => 'Bruno Costa', 'email' => 'bruno@loja.com', 'role' => 'Vendedor'],
        ];

        foreach ($users as $index => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'tenant_id' => $tenant->id,
                    'created_at' => $baseNow->copy()->subDays(30)->addMinutes($index * 10),
                    'updated_at' => $baseNow->copy()->subDays(5)->addMinutes($index * 5),
                ]
            );

            if (!$user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }
        }
    }

    private function seedCustomers(Tenant $tenant, Carbon $baseNow, int $count)
    {
        $customers = [];
        $firstNames = ['Joao', 'Maria', 'Jose', 'Ana', 'Pedro', 'Paula', 'Marcos', 'Julia', 'Rafael', 'Carla'];
        $lastNames = ['Silva', 'Souza', 'Oliveira', 'Santos', 'Lima', 'Gomes', 'Costa', 'Pereira', 'Alves', 'Rocha'];

        for ($i = 0; $i < $count; $i++) {
            $name = $firstNames[$i % count($firstNames)] . ' ' . $lastNames[$i % count($lastNames)];
            $email = Str::lower(str_replace(' ', '.', $name)) . sprintf('%02d@cliente.com', $i + 1);

            $customers[] = Customer::firstOrCreate(
                ['email' => $email],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $name,
                    'phone' => sprintf('(11) 9%04d-%04d', rand(1000, 9999), rand(1000, 9999)),
                    'document' => sprintf('%03d.%03d.%03d-%02d', rand(100, 999), rand(100, 999), rand(100, 999), rand(10, 99)),
                    'created_at' => $baseNow->copy()->subDays(rand(1, 90))->subHours(rand(0, 23)),
                    'updated_at' => $baseNow->copy()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                ]
            );
        }

        return $customers;
    }

    private function seedProducts(Tenant $tenant, Carbon $baseNow, int $count)
    {
        $products = [];
        $categories = ['Eletronicos', 'Moveis', 'Informatica', 'Papelaria', 'Limpeza', 'Alimentos'];

        for ($i = 0; $i < $count; $i++) {
            $name = 'Produto ' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);

            $products[] = Product::firstOrCreate(
                ['name' => $name, 'tenant_id' => $tenant->id],
                [
                    'description' => 'Descricao do ' . $name,
                    'price' => rand(1000, 90000) / 100,
                    'stock_quantity' => rand(10, 250),
                    'category' => $categories[$i % count($categories)],
                    'created_at' => $baseNow->copy()->subDays(rand(1, 60))->subMinutes(rand(0, 59)),
                    'updated_at' => $baseNow->copy()->subDays(rand(0, 30))->subMinutes(rand(0, 59)),
                ]
            );
        }

        return $products;
    }

    private function seedSales(Tenant $tenant, Carbon $baseNow, array $customers, array $products, int $count): void
    {
        if (empty($customers) || empty($products)) {
            return;
        }

        $statusPool = ['paid', 'pending', 'cancelled'];

        for ($i = 0; $i < $count; $i++) {
            $customer = $customers[array_rand($customers)];
            $status = $statusPool[$i % count($statusPool)];
            $createdAt = $baseNow->copy()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $sale = Sale::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'status' => $status,
                'total_amount' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'paid_at' => $status === 'paid' ? $createdAt->copy()->addMinutes(rand(5, 180)) : null,
            ]);

            $itemsCount = rand(1, 4);
            $total = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 5);
                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $total += $totalPrice;
            }

            if ($status === 'cancelled') {
                $sale->update([
                    'total_amount' => 0,
                    'paid_at' => null,
                    'updated_at' => $createdAt,
                ]);
            } else {
                $sale->update([
                    'total_amount' => $total,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
