import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import LoginPage from '../pages/Auth/Login.vue'
import RegisterPage from '../pages/Auth/Register.vue'
import AppLayout from '../components/templates/AppLayout.vue'
const routes = [
  {
    path: '/login',
    component: LoginPage,
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    component: RegisterPage,
    meta: { requiresGuest: true }
  },

  // 🔥 TODAS AS ROTAS COM LAYOUT
  {
    path: '/',
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/dashboard'
      },
      {
        path: 'dashboard',
        component: () => import('../pages/Dashboard.vue')
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Route Guard - Verifica autenticação
router.beforeEach((to, from, next) => {
  const { isAuthenticated, checkAuth } = useAuth()

  // Verifica se há token armazenado
  checkAuth()

  // Se a rota requer autenticação
  if (to.meta.requiresAuth) {
    if (isAuthenticated.value) {
      next()
    } else {
      // Redireciona para login
      next('/login')
    }
  }
  // Se a rota requer que NÃO esteja autenticado (login, registro, etc)
  else if (to.meta.requiresGuest) {
    if (isAuthenticated.value) {
      // Se está autenticado, vai para dashboard
      next('/dashboard')
    } else {
      next()
    }
  }
  // Rotas públicas
  else {
    next()
  }
})

export default router
