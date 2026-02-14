<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vendas por Dia</h3>
      <div v-if="lineData?.labels?.length" class="h-64">
        <Line :data="lineData" :options="lineOptions" />
      </div>
      <p v-else class="text-sm text-gray-500 dark:text-gray-400">Sem dados no periodo</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vendas por Status</h3>
      <div v-if="doughnutData?.labels?.length" class="h-64">
        <Doughnut :data="doughnutData" :options="doughnutOptions" />
      </div>
      <p v-else class="text-sm text-gray-500 dark:text-gray-400">Sem dados no periodo</p>
    </div>
  </div>
</template>

<script setup>
import { Line, Doughnut } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
)

defineProps({
  lineData: {
    type: Object,
    default: null
  },
  lineOptions: {
    type: Object,
    default: null
  },
  doughnutData: {
    type: Object,
    default: null
  },
  doughnutOptions: {
    type: Object,
    default: null
  }
})
</script>
