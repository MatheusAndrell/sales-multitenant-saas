import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import LoginPage from '../pages/Auth/Login.vue'
import RegisterPage from '../pages/Auth/Register.vue'
import NotAllowedPage from '../pages/NotAllowed.vue'
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
  {
    path: '/433',
    component: NotAllowedPage,
    meta: { title: 'Nao permitido' }
  },

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
        component: () => import('../pages/Dashboard.vue'),
        meta: { title: 'Dashboard', roles: ['Admin da Loja', 'Vendedor'] }
      },
      {
        path: 'customers',
        component: () => import('../pages/Customers/Index.vue'),
        meta: { title: 'Clientes', roles: ['Admin da Loja', 'Vendedor'] }
      },
      {
        path: 'products',
        component: () => import('../pages/Products/Index.vue'),
        meta: { title: 'Produtos', roles: ['Admin da Loja'] }
      },
      {
        path: 'sales',
        component: () => import('../pages/Sales/Index.vue'),
        meta: { title: 'Vendas', roles: ['Admin da Loja', 'Vendedor'] }
      },
      {
        path: 'users',
        component: () => import('../pages/Users/Index.vue'),
        meta: { title: 'Usuários', roles: ['Admin da Loja'] }
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const { isAuthenticated, checkAuth, user, logout } = useAuth()

  const isValidAuth = checkAuth()

  if (to.meta.requiresAuth) {
    if (isAuthenticated.value && isValidAuth) {
      const requiredRoles = to.matched
        .map(record => record.meta.roles)
        .filter(Boolean)
        .flat()

      if (requiredRoles.length === 0) {
        next()
        return
      }

      const userRoles = user.value?.roles || []
      const hasAccess = requiredRoles.some(role => userRoles.includes(role))

      if (hasAccess) {
        next()
      } else {
        next('/433')
      }
    } else {
      next('/login')
    }
  }
  else if (to.meta.requiresGuest) {
    if (isAuthenticated.value && isValidAuth) {
      next('/dashboard')
    } else {
      if (!isValidAuth) {
        logout()
      }
      next()
    }
  }
  else {
    next()
  }
})

export default router
