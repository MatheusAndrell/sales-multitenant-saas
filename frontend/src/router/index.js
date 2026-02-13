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
        meta: { title: 'Dashboard' }
      },
      {
        path: 'customers',
        component: () => import('../pages/Customers/Index.vue'),
        meta: { title: 'Clientes' }
      },
      {
        path: 'products',
        component: () => import('../pages/Products/Index.vue'),
        meta: { title: 'Produtos' }
      },
      {
        path: 'sales',
        component: () => import('../pages/Sales/Index.vue'),
        meta: { title: 'Vendas' }
      },
      {
        path: 'users',
        component: () => import('../pages/Users/Index.vue'),
        meta: { title: 'Usuários' }
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const { isAuthenticated, checkAuth } = useAuth()

  checkAuth()

  if (to.meta.requiresAuth) {
    if (isAuthenticated.value) {
      next()
    } else {
      next('/login')
    }
  }
  else if (to.meta.requiresGuest) {
    if (isAuthenticated.value) {
      next('/dashboard')
    } else {
      next()
    }
  }
  else {
    next()
  }
})

export default router
