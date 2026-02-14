<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status das Vendas</h3>
    <div class="space-y-3">
      <div class="flex justify-between items-center">
        <span class="text-sm text-gray-600 dark:text-gray-300">Pendente</span>
        <span class="text-sm font-semibold text-yellow-600">{{ salesByStatus?.pending?.count || 0 }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-sm text-gray-600 dark:text-gray-300">Paga</span>
        <span class="text-sm font-semibold text-green-600">{{ salesByStatus?.paid?.count || 0 }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-sm text-gray-600 dark:text-gray-300">Cancelada</span>
        <span class="text-sm font-semibold text-gray-600">{{ salesByStatus?.cancelled?.count || 0 }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  salesByStatus: {
    type: Object,
    default: null
  }
})
</script>
