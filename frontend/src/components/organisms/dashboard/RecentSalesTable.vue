<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ultimas Vendas</h3>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Cliente</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Data</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in sales" :key="sale.id" class="border-b border-gray-200 dark:border-gray-700">
            <td class="px-4 py-2">#{{ sale.id }}</td>
            <td class="px-4 py-2">{{ sale.customer || '-' }}</td>
            <td class="px-4 py-2">{{ formatCurrency(sale.total_amount) }}</td>
            <td class="px-4 py-2">{{ getStatusLabel(sale.status) }}</td>
            <td class="px-4 py-2">{{ formatDateTime(sale.created_at) }}</td>
          </tr>
        </tbody>
      </table>
      <p v-if="sales.length === 0" class="text-sm text-gray-500 dark:text-gray-400 mt-3">Sem dados</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  sales: {
    type: Array,
    default: () => []
  },
  formatCurrency: {
    type: Function,
    required: true
  },
  getStatusLabel: {
    type: Function,
    required: true
  },
  formatDateTime: {
    type: Function,
    required: true
  }
})
</script>
