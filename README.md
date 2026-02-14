# Sales Multitenant SaaS

Guia de instalacao e execucao local do projeto.

## Pre-requisitos

- Docker
- Docker Compose

## Configuracao inicial

1) Clone o repositorio

```bash
git clone <URL_DO_REPOSITORIO>
cd sales-multitenant-saas
```

2) Configure o backend (.env)

```bash
copy src\.env.example src\.env
```

Ajuste as variaveis principais em [src/.env](src/.env) para bater com o docker-compose:

```dotenv
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sales_db
DB_USERNAME=sales_user
DB_PASSWORD=secret

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_HOST=mailhog
MAIL_PORT=1025
```

3) Suba o ambiente Docker

```bash
docker-compose up -d --build
```

## Instalacao de dependencias

### Backend (PHP/Laravel)

```bash
docker-compose exec app composer install
```

### Frontend (Vue)

```bash
docker-compose exec frontend npm install
```

## Migrations e seeders

```bash
docker-compose exec app php artisan migrate --seed
```

Se precisar resetar tudo:

```bash
docker-compose exec app php artisan migrate:fresh --seed
```

## Acessos

- Frontend: http://localhost:5173
- API: http://localhost:8000/api
- Mailhog: http://localhost:8025

## Documentacao da API

Acesse:
http://localhost:8080

## Executar testes

```bash
docker-compose exec app php artisan test
```

## Comandos uteis

Gerar APP_KEY se necessario:

```bash
docker-compose exec app php artisan key:generate
```

Ver logs do worker:

```bash
docker-compose logs -f queue-worker
```

## Observacoes

- O worker de filas ja sobe automaticamente pelo servico `queue-worker` no docker-compose.
- O cache, sessao e fila estao configurados para Redis.
- A aplicacao usa multi-tenant (tenant_1 padrao criado no seed).
