<template>
  <div class="max-w-7xl mx-auto p-6">
    <Breadcrumb />
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-neutral-700 dark:text-white">Vendas</h1>
      <div class="flex gap-2">
        <AtButton variant="primary" @click="openCreateModal">Nova Venda</AtButton>
        <AtButton variant="secondary" @click="openExportModal">Exportar PDF</AtButton>
      </div>
    </div>
    <CrudTable 
      :columns="columns" 
      :rows="rows" 
      :hasActions="true" 
      editLabel="Visualizar"
      deleteLabel="Cancelar"
      @edit="handleView" 
      @delete="handleCancel"
    >
      <template #cell-status="{ row }">
        <span 
          class="px-2 py-1 text-xs font-semibold rounded-full"
          :class="getStatusBadgeClass(row.raw_status)"
        >
          {{ row.status }}
        </span>
      </template>
    </CrudTable>
    <CreateSaleModal 
      v-if="showCreateModal" 
      :show="showCreateModal" 
      @close="showCreateModal = false" 
      @created="fetchSales" 
    />
    <ViewSaleModal
      v-if="showViewModal"
      :show="showViewModal"
      :saleId="selectedSaleId"
      @close="showViewModal = false"
      @updated="fetchSales"
    />
    <ExportReportModal
      v-if="showExportModal"
      :show="showExportModal"
      @close="showExportModal = false"
      @sent="handleReportSent"
    />
  </div>
</template>

<script setup>
import Breadcrumb from '../../components/molecules/Breadcrumb.vue'
import CrudTable from '../../components/molecules/CrudTable.vue'
import AtButton from '../../components/atoms/Button.vue'
import { ref, onMounted } from 'vue'
import CreateSaleModal from './CreateSaleModal.vue'
import ViewSaleModal from './ViewSaleModal.vue'
import ExportReportModal from './ExportReportModal.vue'
import api from '../../services/api'
import { useToast } from 'vue-toastification'
import Swal from 'sweetalert2'

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'customer', label: 'Cliente' },
  { key: 'total_amount', label: 'Total' },
  { key: 'status', label: 'Status' },
  { key: 'created_at', label: 'Data' }
]

const rows = ref([])
const showViewModal = ref(false)
const showExportModal = ref(false)
const selectedSaleId = ref(null)
const toast = useToast()

const statusLabels = {
  pending: 'Pendente',
  paid: 'Paga',
  cancelled: 'Cancelada'
}

async function fetchSales() {
  try {
    const response = await api.get('/sales')
    rows.value = response.data.data.map(sale => ({
      id: sale.id,
      customer: sale.customer?.name || '-',
      total_amount: `R$ ${parseFloat(sale.total_amount || 0).toFixed(2)}`,
      status: statusLabels[sale.status] || sale.status,
      created_at: new Date(sale.created_at).toLocaleDateString('pt-BR'),
      raw_status: sale.status
    }))
  } catch (error) {
    console.error('Erro ao buscar vendas:', error)
  }
}

onMounted(fetchSales)

const showCreateModal = ref(false)

function openCreateModal() {
  showCreateModal.value = true
}

function openExportModal() {
  showExportModal.value = true
}

function handleReportSent() {
  toast.success('Relatorio em processamento. Voce recebera por email.')
}

function handleView(row) {
  selectedSaleId.value = row.id
  showViewModal.value = true
}

async function handleCancel(row) {
  if (row.raw_status === 'cancelled') {
    toast.info('Esta venda já está cancelada.')
    return
  }

  const result = await Swal.fire({
    title: 'Cancelar venda?',
    text: `Deseja realmente cancelar a venda #${row.id}? O estoque será devolvido.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sim, cancelar',
    cancelButtonText: 'Não'
  })

  if (!result.isConfirmed) {
    return
  }

  try {
    await api.post(`/sales/cancel/${row.id}`)
    toast.success('Venda cancelada com sucesso.')
    await fetchSales()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Erro ao cancelar venda.')
  }
}

function getStatusBadgeClass(status) {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    paid: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    cancelled: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>