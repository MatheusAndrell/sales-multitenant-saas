# Integração Frontend-Backend com Axios

## Configuração

### Arquivo de Configuração da API
- **Arquivo**: `src/services/api.js`
- **URL Base**: `http://localhost:8000/api`
- **Autenticação**: Bearer Token (automaticamente adicionado via Interceptor)

### Fluxo de Autenticação

#### 1. **Login** (`POST /api/auth/login`)
```javascript
{
  "email": "admin@admin.com",
  "password": "admin123"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "email": "admin@admin.com",
      "name": "Admin User",
      "tenant_id": 1
    },
    "token": "3|MDOsrfTHjyVQg4yI8fXDpjBeTlXi5FX4HCQNbhYi68dff024"
  }
}
```

#### 2. **Registro** (`POST /api/tenants/register`)
```javascript
{
  "tenant_name": "Loja Test LTDA",
  "tenant_email": "contato@loja.com",
  "cnpj": "81161297000179",
  "admin_name": "Matheus Admin",
  "admin_email": "admin@gmail.com",
  "password": "12345678"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Tenant criado com sucesso.",
  "data": {
    "tenant": {
      "id": 2,
      "name": "Loja Test LTDA",
      "slug": "loja-test-ltda-8zR7z",
      "email": "contato@loja.com",
      "cnpj": "81161297000179",
      "is_active": true,
      "created_at": "2026-02-13T01:31:08.000000Z",
      "updated_at": "2026-02-13T01:31:08.000000Z"
    },
    "user": {
      "id": 3,
      "tenant_id": 2,
      "name": "Matheus Admin",
      "email": "admin@gmail.com",
      "created_at": "2026-02-13T01:31:09.000000Z",
      "updated_at": "2026-02-13T01:31:09.000000Z"
    },
    "token": "3|MDOsrfTHjyVQg4yI8fXDpjBeTlXi5FX4HCQNbhYi68dff024"
  }
}
```

## Como Funciona

### 1. **Composable `useAuth`** 
Arquivo: `src/composables/useAuth.js`

**Métodos disponíveis:**

```javascript
import { useAuth } from '@/composables/useAuth'

const { 
  user,              // Dados do usuário autenticado (ref)
  token,             // Token de autenticação (ref)
  isAuthenticated,   // Computed: se está autenticado
  login,             // Função async: faz login
  register,          // Função async: registra novo tenant
  logout,            // Função: faz logout
  checkAuth          // Função: verifica se há token
} = useAuth()
```

### 2. **Serviço API** 
Arquivo: `src/services/api.js`

**Características:**
- ✅ Interceptador de requisição (adiciona Bearer Token)
- ✅ Interceptador de resposta (trata 401 - não autenticado)
- ✅ URL base configurada automaticamente
- ✅ Headers padrão (Content-Type: application/json)

**Exemplo de uso:**
```javascript
import api from '@/services/api'

// GET
const response = await api.get('/products')

// POST
const response = await api.post('/sales', { data })

// PUT
const response = await api.put('/products/1', { data })

// DELETE
const response = await api.delete('/products/1')
```

### 3. **Fluxo de Login**

```
Usuário preenche email/senha
        ↓
LoginTemplate → handleSubmit()
        ↓
useAuth.login(email, password)
        ↓
axios POST /api/auth/login
        ↓
API retorna { success: true, data: { token, user } }
        ↓
Token salvo em localStorage
Usuário salvo em ref
        ↓
Router redireciona para /dashboard
```

### 4. **Fluxo de Registro**

```
Usuário preenche dados da empresa e admin
        ↓
RegisterTemplate → handleSubmit()
        ↓
Validações (campos obrigatórios, termos, senha)
        ↓
useAuth.register(registerData)
        ↓
axios POST /api/tenants/register
        ↓
API retorna { success: true, data: { token, user, tenant } }
        ↓
Token salvo em localStorage
Usuário e Tenant salvos em refs
        ↓
Router redireciona para /dashboard (já autenticado)
```

## Tratamento de Erros

### Exemplo com try-catch:

```javascript
const { register } = useAuth()

try {
  const result = await register(registerData)
  if (result.success) {
    router.push('/dashboard')
  }
} catch (error) {
  // error.response?.data contém resposta da API
  const errorMessage = error.response?.data?.message
  console.error('Error:', errorMessage)
}
```

### Resposta de Erro Esperada:
```json
{
  "success": false,
  "message": "Email já cadastrado",
  "errors": {
    "email": ["Email não pode ser duplicado"]
  }
}
```

## Requisições Autenticadas

Após login, todas as requisições automaticamente incluem o token:

```javascript
// No interceptor do axios:
headers: {
  "Authorization": "Bearer {token}"
}
```

**Exemplo de requisição protegida:**

```javascript
import api from '@/services/api'

// Isso já inclui o token automaticamente
const products = await api.get('/products')
```

## Logout

```javascript
const { logout } = useAuth()

const handleLogout = () => {
  logout()  // Remove token e user do localStorage
  router.push('/login')  // Redireciona
}
```

## Ajustar URL Base

Se seu backend estiver em uma URL diferente, edite `src/services/api.js`:

```javascript
const api = axios.create({
  baseURL: 'http://seu-backend.com/api',  // ← Alterar aqui
  // ...
})
```

## CORS

Certifique-se de que seu Laravel está configurado para aceitar requisições CORS. No `config/cors.php`:

```php
'allowed_origins' => ['http://localhost:5174', 'http://localhost:5173'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'exposed_headers' => ['*'],
'max_age' => 0,
'supports_credentials' => true,
```

## Próximos Passos

1. ✅ Integração básica com login/registro
2. 📌 Adicionar refresh token (para renovar autenticação)
3. 📌 Implementar requisições para produtos, clientes, vendas
4. 📌 Tratamento completo de erros e validações
5. 📌 Logout automático por inatividade
