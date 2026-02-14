<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <p class="text-sm text-gray-500 dark:text-gray-400">Total de Vendas</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ summary?.total_sales || 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <p class="text-sm text-gray-500 dark:text-gray-400">Valor Total</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(summary?.total_amount) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <p class="text-sm text-gray-500 dark:text-gray-400">Ticket Medio</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(summary?.average_sale) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <p class="text-sm text-gray-500 dark:text-gray-400">Vendas Hoje</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ summary?.sales_today || 0 }}</p>
      <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatCurrency(summary?.amount_today) }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  summary: {
    type: Object,
    default: null
  },
  formatCurrency: {
    type: Function,
    required: true
  }
})
</script>
