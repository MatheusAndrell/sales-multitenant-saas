<template>
  <div class="max-w-7xl mx-auto p-6">
    <Breadcrumb />
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-neutral-700 dark:text-white">Clientes</h1>
      <div class="flex gap-2">
        <AtButton variant="primary" @click="openAddModal">Adicionar Cliente</AtButton>
        <AtButton variant="secondary" @click="handleExportCSV">Exportar CSV</AtButton>
      </div>
    </div>
    <CrudTable :columns="columns" :rows="rows" :hasActions="true" @edit="handleEdit" @delete="handleDelete" />
    <AddModal v-if="showAddModal" :show="showAddModal" @close="showAddModal = false" />
    <EditModal
      v-if="showEditModal"
      :show="showEditModal"
      :customerId="selectedCustomerId"
      @close="showEditModal = false"
      @updated="fetchCustomers"
    />
  </div>
</template>

<script setup>
import Breadcrumb from '../../components/molecules/Breadcrumb.vue'
import CrudTable from '../../components/molecules/CrudTable.vue'
import { ref, onMounted } from 'vue'
import AddModal from './AddModal.vue'
import EditModal from './EditModal.vue'
import api from '../../services/api'
import { useToast } from 'vue-toastification'
import Swal from 'sweetalert2'

const columns = [
  { key: 'name', label: 'Nome' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Telefone' },
  { key: 'document', label: 'Documento' }
]

const rows = ref([])
const showEditModal = ref(false)
const selectedCustomerId = ref(null)
const toast = useToast()

async function fetchCustomers() {
  try {
    const response = await api.get('/customers')
    rows.value = response.data.data.map(customer => ({
      id: customer.id,
      name: customer.name,
      email: customer.email,
      phone: customer.phone,
      document: customer.document || ''
    }))
  } catch (error) {
    console.error('Erro ao buscar clientes:', error)
  }
}

onMounted(fetchCustomers)

const showAddModal = ref(false)

function openAddModal() {
  showAddModal.value = true
}

function handleExportCSV() {
  const csvRows = []
  csvRows.push(columns.map(col => col.label).join(','))
  rows.value.forEach(row => {
    csvRows.push(columns.map(col => row[col.key]).join(','))
  })
  const csvString = csvRows.join('\n')
  const blob = new Blob([csvString], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'clientes.csv'
  a.click()
  window.URL.revokeObjectURL(url)
}

function handleEdit(row) {
  selectedCustomerId.value = row.id
  showEditModal.value = true
}

async function handleDelete(row) {
  const result = await Swal.fire({
    title: 'Excluir cliente? ',
    text: `Deseja realmente excluir ${row.name}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sim, excluir',
    cancelButtonText: 'Cancelar'
  })

  if (!result.isConfirmed) {
    return
  }

  try {
    await api.delete(`/customers/delete/${row.id}`)
    toast.success('Cliente removido com sucesso.')
    await fetchCustomers()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Erro ao remover cliente.')
  }
}
</script>