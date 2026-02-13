<template>
  <nav class="flex mb-6 text-sm text-primary dark:text-gray-300" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1">
      <li>
        <RouterLink to="/dashboard" class="hover:text-fg-brand">Dashboard</RouterLink>
      </li>
      <li v-if="currentPage">
        <span class="mx-2">/</span>
      </li>
      <li v-if="currentPage" aria-current="page" class="font-semibold text-neutral-700 dark:text-white">
        {{ currentPage }}
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const pageNames = {
  '/dashboard': '',
  '/customers': 'Clientes',
  '/products': 'Produtos',
  '/users': 'Usuários',
  '/sales': 'Vendas'
}

const currentPage = computed(() => {
  // Prioriza meta.title da rota, senão usa o mapeamento
  if (route.meta?.title && route.path !== '/dashboard') {
    return route.meta.title
  }
  return pageNames[route.path] || ''
})
</script>
