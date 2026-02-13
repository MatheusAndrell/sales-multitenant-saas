<template>
  <div class="max-w-7xl mx-auto p-6">
    <Breadcrumb />
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-neutral-700 dark:text-white">Produtos</h1>
      <div class="flex gap-2">
        <AtButton variant="primary" @click="openAddModal">Adicionar Produto</AtButton>
        <AtButton variant="secondary" @click="handleExportCSV">Exportar CSV</AtButton>
      </div>
    </div>
    <CrudTable :columns="columns" :rows="rows" :hasActions="true" @edit="handleEdit" @delete="handleDelete" />
    <AddModal v-if="showAddModal" :show="showAddModal" @close="showAddModal = false" @created="fetchProducts" />
    <EditModal
      v-if="showEditModal"
      :show="showEditModal"
      :productId="selectedProductId"
      @close="showEditModal = false"
      @updated="fetchProducts"
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
  { key: 'description', label: 'Descrição' },
  { key: 'price', label: 'Preço' },
  { key: 'stock_quantity', label: 'Quantidade em Estoque' },
  { key: 'category', label: 'Categoria' }
]

const rows = ref([])
const showEditModal = ref(false)
const selectedProductId = ref(null)
const toast = useToast()

async function fetchProducts() {
  try {
    const response = await api.get('/products')
    rows.value = response.data.data.map(product => ({
      id: product.id,
      name: product.name,
      description: product.description,
      price: product.price,
      stock_quantity: product.stock_quantity,
      category: product.category
    }))
  } catch (error) {
    console.error('Erro ao buscar produtos:', error)
  }
}

onMounted(fetchProducts)

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
  a.download = 'produtos.csv'
  a.click()
  window.URL.revokeObjectURL(url)
}

function handleEdit(row) {
  selectedProductId.value = row.id
  showEditModal.value = true
}

async function handleDelete(row) {
  const result = await Swal.fire({
    title: 'Excluir produto? ',
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
    await api.delete(`/products/delete/${row.id}`)
    toast.success('Produto removido com sucesso.')
    await fetchProducts()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Erro ao remover produto.')
  }
}
</script>