# Arquitetura do Sistema

Este documento descreve as principais decisoes de arquitetura do projeto, com foco na estrutura do banco de dados e no modelo de Roles/Permissions.

## Visao Geral

- Backend: Laravel + Sanctum + Spatie Permissions
- Frontend: Vue 3 + Vite
- Banco: MySQL
- Cache/Fila: Redis
- Infra: Docker Compose

## Estrutura de Banco de Dados

### Multi-tenancy
- Todas as tabelas de dominio principais (`customers`, `products`, `sales`, `users`) possuem `tenant_id`.
- O trait `BelongsToTenant` aplica automaticamente `tenant_id` no `creating` e adiciona o escopo global `TenantScope`.
- Isso garante isolamento de dados entre tenants com minimo acoplamento no codigo.

### Entidades principais

- `tenants`
  - Armazena dados da loja (nome, slug, email, CNPJ, configuracoes basicas).

- `users`
  - Usuarios pertencem a um tenant.
  - Usam Sanctum para autenticacao via token.
  - Relacao com roles via Spatie Permission.

- `customers`
  - Clientes do tenant.
  - Dados basicos (nome, email, telefone, documento).
  - Fornecedores foram modelados como customers, para simplificar o dominio.

- `products`
  - Produtos do tenant.
  - Campos de estoque (`stock_quantity`) e `category`.

- `sales`
  - Venda associada a um `customer` e `tenant`.
  - Status: `pending`, `paid`, `cancelled`.
  - Campo `paid_at` registra quando a venda foi finalizada.

- `sale_items`
  - Itens da venda (produto, quantidade, preco unitario e total).
  - Relacao 1:N entre `sales` e `sale_items`.

### Regras de negocio em transacoes
- Finalizar venda (`pay`) e cancelar venda (`cancel`) usam transacoes com `lockForUpdate`.
- Isso garante integridade de estoque em cenarios concorrentes.
- O debito de estoque ocorre na finalizacao da venda (status `paid`) para manter vendas pendentes sem impacto em estoque.

## Roles e Permissions

### Roles
- `Admin da Loja`
  - Acesso total ao sistema.
  - Permissoes: `manage customers`, `manage products`, `manage users`, `manage sales`, `view reports`.

- `Vendedor`
  - Acesso operacional.
  - Permissoes: `manage customers`, `manage sales`.
  - Pode listar produtos para incluir em vendas, mas nao pode criar/editar produtos.

### Permissions (Spatie)
- Permissions sao atribuidas por role no seeder `RolesAndPermissionsSeeder`.
- O backend valida permissao no middleware das rotas e nos FormRequests.
- O frontend usa as roles para esconder itens do menu e bloquear rotas.

## Decisoes de API

- APIs sao separadas por recursos e versao unica (`/api`).
- Autenticacao via token Sanctum.
- Respostas padrao com `message` e `data` quando necessario.

## Cache e Filas

- Redis e usado para cachear metricas do dashboard (TTL curto, 5 minutos).
- Fila (`queue`) usa Redis e processa jobs de relatorio em background.

## Seeders e Dados de Teste

- Seeders criam um tenant padrao e usuarios iniciais (admin e vendedor).
- Seeder adicional cria dados ficticios para demonstracao com base na data 13/02/2026 22:27.

## Observacoes

- A separacao Action/DTO foi escolhida para isolar regras de negocio e manter controllers simples.
- A arquitetura facilita testes, manutencao e evolucao do dominio.
