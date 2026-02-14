import { ref, computed } from 'vue'
import api from '../services/api'

const storedUser = localStorage.getItem('auth_user')
const user = ref(storedUser ? JSON.parse(storedUser) : null)
const token = ref(localStorage.getItem('auth_token') || null)

const isAuthenticated = computed(() => !!token.value)

export function useAuth() {
  const login = async (email, password) => {
    try {
      const response = await api.post('/auth/login', {
        email,
        password
      })

      const { success, data } = response.data

      if (success && data.token) {
        token.value = data.token
        localStorage.setItem('auth_token', data.token)

        const roles = data.roles || data.user?.roles || []

        user.value = {
          id: data.user.id,
          email: data.user.email,
          name: data.user.name,
          tenant_id: data.user.tenant_id,
          roles
        }

        localStorage.setItem('auth_user', JSON.stringify(user.value))

        return {
          success: true,
          user: user.value
        }
      }

      return { success: false }
    } catch (error) {
      console.error('Login failed:', error.response?.data || error.message)
      throw error
    }
  }

  const register = async (registerData) => {
    try {
      const response = await api.post('/tenants/register', registerData)

      const { success, data } = response.data

      if (success && data.token) {
        token.value = data.token
        localStorage.setItem('auth_token', data.token)

        const roles = data.roles || data.user?.roles || []

        user.value = {
          id: data.user.id,
          email: data.user.email,
          name: data.user.name,
          tenant_id: data.user.tenant_id,
          roles
        }

        localStorage.setItem('auth_user', JSON.stringify(user.value))

        return {
          success: true,
          user: user.value,
          tenant: data.tenant
        }
      }

      return { success: false }
    } catch (error) {
      console.error('Registration failed:', error.response?.data || error.message)
      throw error
    }
  }

  const logout = () => {
    token.value = null
    user.value = null
    localStorage.removeItem('auth_token')
    localStorage.removeItem('auth_user')
  }

  const checkAuth = () => {
    // Se há token em localStorage, a API pode validar na próxima requisição
    if (token.value) {
      const hasUser = !!user.value
      const hasRoles = Array.isArray(user.value?.roles)
      if (hasUser && hasRoles) {
        return true
      }

      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      return false
    }
    return false
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
    checkAuth
  }
}

