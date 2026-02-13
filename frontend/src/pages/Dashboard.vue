<template>
  <div class="min-h-screen bg-blue-100 dark:bg-gray-900 p-6">
    <div class="max-w-7xl mx-auto">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
          Dashboard
        </h1>
        <AtButton variant="secondary" @click="handleLogout">
          Logout
        </AtButton>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
          Bem-vindo, {{ user?.name }}!
        </h2>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Email: {{ user?.email }}
        </p>
        <p class="text-gray-600 dark:text-gray-300">
          Você está autenticado e pode acessar todas as funcionalidades do sistema.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            Vendas
          </h3>
          <p class="text-gray-600 dark:text-gray-300">
            Gerencie suas vendas e pedidos
          </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            Produtos
          </h3>
          <p class="text-gray-600 dark:text-gray-300">
            Controle seu catálogo de produtos
          </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            Clientes
          </h3>
          <p class="text-gray-600 dark:text-gray-300">
            Manage seus clientes e informações
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const { user, logout } = useAuth()

const handleLogout = () => {
  logout()
  router.push('/login')
}
</script>
