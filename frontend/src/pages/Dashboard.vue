<template>
  <div class="min-h-screen bg-blue-100 dark:bg-gray-900 p-6">
    <div class="max-w-7xl mx-auto">
      <DashboardHeader :periodLabel="periodLabel" @logout="handleLogout" />

      <WelcomeCard :user="user" />

      <div v-if="loading" class="text-gray-600 dark:text-gray-300">
        Carregando metricas...
      </div>

      <div v-else>
        <SummaryCards :summary="metrics?.summary" :formatCurrency="formatCurrency" />

        <SalesCharts
          :lineData="lineChartData"
          :lineOptions="lineChartOptions"
          :doughnutData="doughnutChartData"
          :doughnutOptions="doughnutChartOptions"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <StatusSummaryCard :salesByStatus="metrics?.sales_by_status" />
          <TopListCard
            title="Top Produtos"
            :items="metrics?.top_products || []"
            valueKey="total_quantity"
          />
          <TopListCard
            title="Top Clientes"
            :items="metrics?.top_customers || []"
            valueKey="total_spent"
            :valueFormatter="formatCurrency"
          />
        </div>

        <RecentSalesTable
          :sales="metrics?.recent_sales || []"
          :formatCurrency="formatCurrency"
          :getStatusLabel="getStatusLabel"
          :formatDateTime="formatDateTime"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import api from '../services/api'
import DashboardHeader from '../components/organisms/dashboard/DashboardHeader.vue'
import WelcomeCard from '../components/organisms/dashboard/WelcomeCard.vue'
import SummaryCards from '../components/organisms/dashboard/SummaryCards.vue'
import SalesCharts from '../components/organisms/dashboard/SalesCharts.vue'
import StatusSummaryCard from '../components/organisms/dashboard/StatusSummaryCard.vue'
import TopListCard from '../components/organisms/dashboard/TopListCard.vue'
import RecentSalesTable from '../components/organisms/dashboard/RecentSalesTable.vue'

const router = useRouter()
const { user, logout } = useAuth()

const metrics = ref(null)
const loading = ref(false)

const handleLogout = () => {
  logout()
  router.push('/login')
}

const periodLabel = computed(() => {
  if (!metrics.value?.period) return ''
  return `${formatDate(metrics.value.period.start)} a ${formatDate(metrics.value.period.end)}`
})

const formatCurrency = (value) => {
  const amount = Number(value || 0)
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(amount)
}

const formatDate = (value) => {
  if (!value) return '-'
  return new Date(value).toLocaleDateString('pt-BR')
}

const formatDateTime = (value) => {
  if (!value) return '-'
  return new Date(value).toLocaleString('pt-BR')
}

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Pendente',
    paid: 'Paga',
    cancelled: 'Cancelada'
  }
  return labels[status] || status
}

const fetchMetrics = async () => {
  loading.value = true
  try {
    const response = await api.get('/dashboard/metrics')
    metrics.value = response.data.data
  } catch (error) {
    console.error('Erro ao carregar metricas:', error)
  } finally {
    loading.value = false
  }
}

const lineChartData = computed(() => {
  const rows = metrics.value?.sales_per_day || []
  const labels = rows.map(row => formatDate(row.date))
  const data = rows.map(row => Number(row.total_amount || 0))

  return {
    labels,
    datasets: [
      {
        label: 'Valor Total (R$)',
        data,
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37, 99, 235, 0.15)',
        pointBackgroundColor: '#2563eb',
        tension: 0.3,
        fill: true
      }
    ]
  }
})

const lineChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false }
  },
  scales: {
    y: {
      ticks: {
        callback: (value) => `R$ ${Number(value).toFixed(2)}`
      }
    }
  }
}

const doughnutChartData = computed(() => {
  const status = metrics.value?.sales_by_status || {}
  const labels = ['Pendente', 'Paga', 'Cancelada']
  const data = [
    status.pending?.count || 0,
    status.paid?.count || 0,
    status.cancelled?.count || 0
  ]

  return {
    labels: data.some(value => value > 0) ? labels : [],
    datasets: [
      {
        data,
        backgroundColor: ['#f59e0b', '#10b981', '#6b7280']
      }
    ]
  }
})

const doughnutChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'bottom' }
  }
}

onMounted(fetchMetrics)
</script>
