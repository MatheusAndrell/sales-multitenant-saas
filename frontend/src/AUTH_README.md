# Autenticação e Roteamento

## Visão Geral

O sistema de autenticação e roteamento foi configurado para proteger as rotas e redirecionar usuários não autenticados para a página de login.

## Como Funciona

### 1. **Composable `useAuth`** (`src/composables/useAuth.js`)

Gerencia o estado de autenticação do usuário:

```javascript
import { useAuth } from '@/composables/useAuth'

export default {
  setup() {
    const { user, token, isAuthenticated, login, logout, checkAuth } = useAuth()
    
    // user - Dados do usuário logado
    // token - Token de autenticação
    // isAuthenticated - Se está autenticado (computed)
    // login(email, password) - Faz login
    // logout() - Faz logout
    // checkAuth() - Verifica autenticação com localStorage
    
    return { user, isAuthenticated, logout }
  }
}
```

### 2. **Router** (`src/router/index.js`)

Define as rotas e route guards:

```javascript
const routes = [
  { path: '/', redirect: '/dashboard' },
  { path: '/login', component: LoginPage, meta: { requiresGuest: true } },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } }
]
```

**Meta Tags:**
- `requiresAuth: true` - Requer autenticação (redireciona para login se não autenticado)
- `requiresGuest: true` - Requer estar desautenticado (redireciona para dashboard se autenticado)
- Nenhuma meta - Rota pública

### 3. **Fluxo de Autenticação**

```
Usuário tenta acessar /dashboard
        ↓
Route Guard verifica meta
        ↓
checkAuth() - Verifica localStorage
        ↓
isAuthenticated === true?
        ├─ SIM → Permite acesso
        └─ NÃO → Redireciona para /login
```

## Fluxo de Login

1. Usuário preenche email e senha
2. Clica em "Sign in"
3. `LoginForm` emite evento 'submit'
4. `LoginTemplate` chama `login(email, password)`
5. `useAuth.login()` simula chamada à API
6. Token salvo em localStorage
7. Router redireciona para `/dashboard`

## Usar em Componentes

### Verificar Autenticação

```vue
<script setup>
import { useAuth } from '@/composables/useAuth'

const { isAuthenticated, user } = useAuth()
</script>

<template>
  <div v-if="isAuthenticated">
    Bem-vindo, {{ user.name }}!
  </div>
</template>
```

### Fazer Login

```vue
<script setup>
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

const { login } = useAuth()
const router = useRouter()

const handleLogin = async () => {
  const result = await login('user@example.com', 'password')
  if (result.success) {
    router.push('/dashboard')
  }
}
</script>
```

### Fazer Logout

```vue
<script setup>
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

const { logout } = useAuth()
const router = useRouter()

const handleLogout = () => {
  logout()
  router.push('/login')
}
</script>

<template>
  <button @click="handleLogout">Logout</button>
</template>
```

## Integração com API Real

Para integrar com sua API Laravel, atualize `src/composables/useAuth.js`:

```javascript
const login = async (email, password) => {
  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    })
    
    const data = await response.json()
    
    if (data.token) {
      token.value = data.token
      localStorage.setItem('auth_token', data.token)
      user.value = data.user
      return { success: true, user: data.user }
    }
    
    return { success: false }
  } catch (error) {
    console.error('Login failed:', error)
    return { success: false }
  }
}
```

## Headers de Autenticação

Sempre envie o token nas requisições:

```javascript
const headers = {
  'Authorization': `Bearer ${token.value}`,
  'Content-Type': 'application/json'
}

fetch('/api/endpoint', {
  method: 'POST',
  headers,
  body: JSON.stringify(data)
})
```

## Persistência

O token é automaticamente salvo em `localStorage` e restaurado ao recarregar a página. O `route guard` verifica automaticamente se há um token válido.

## Próximos Passos

1. Implementar a autenticação real com sua API
2. Adicionar renovação de token (refresh token)
3. Adicionar tratamento de erros (401, 403, etc)
4. Implementar 2FA se necessário
5. Adicionar logout automático por inatividade
