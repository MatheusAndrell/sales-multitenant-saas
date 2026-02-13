<template>
	<BaseModal :show="show" @close="close" size="large">
		<ModalHeader>
			<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nova Venda</h3>
			<AtButton size="sm" variant="tertiary" @click="close" type="button">
				<Icon icon="material-symbols:close-rounded" 
					class="dark:text-white dark:hover:text-gray-300 text-primary hover:text-blue-700 cursor-pointer" />
			</AtButton>
		</ModalHeader>

		<ModalBody>
			<!-- Step 1: Selecionar Cliente -->
			<div v-if="currentStep === 1" class="space-y-4">
				<FormSelect
					label="Cliente"
					placeholder="Selecione um cliente"
					v-model="selectedCustomerId"
					:options="customerOptions"
				/>
				<div class="flex justify-end">
					<AtButton variant="primary" @click="createSale" :loading="loading" :disabled="!selectedCustomerId">
						Próximo
					</AtButton>
				</div>
			</div>

			<!-- Step 2: Adicionar Produtos -->
			<div v-if="currentStep === 2" class="space-y-4">
				<div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
					<p class="text-sm text-gray-600 dark:text-gray-300">
						<strong>Cliente:</strong> {{ selectedCustomerName }}
					</p>
					<p class="text-sm text-gray-600 dark:text-gray-300">
						<strong>Venda #{{ saleId }}</strong>
					</p>
				</div>

				<!-- Formulário para adicionar produto -->
				<div class="grid grid-cols-2 gap-4">
					<FormSelect
						label="Produto"
						placeholder="Selecione um produto"
						v-model="newItem.product_id"
						:options="productOptions"
					/>
					<MolFormGroup
						label="Quantidade"
						type="number"
						min="1"
						placeholder="1"
						v-model="newItem.quantity"
					/>
				</div>
				<AtButton variant="secondary" @click="addItem" :loading="addingItem" :disabled="!newItem.product_id || !newItem.quantity">
					Adicionar Item
				</AtButton>

				<!-- Lista de itens -->
				<div v-if="items.length > 0" class="mt-4">
					<h4 class="font-semibold mb-2 text-gray-900 dark:text-white">Itens da Venda</h4>
					<div class="space-y-2">
						<div v-for="item in items" :key="item.id" class="flex justify-between items-center bg-white dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-600">
							<div>
								<p class="font-medium text-gray-900 dark:text-white">{{ item.product_name }}</p>
								<p class="text-sm text-gray-600 dark:text-gray-400">
									{{ item.quantity }}x R$ {{ item.unit_price }} = R$ {{ item.total_price }}
								</p>
							</div>
							<AtButton size="sm" variant="danger" @click="removeItem(item.id)">
								Remover
							</AtButton>
						</div>
					</div>
					<div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
						<p class="text-lg font-bold text-blue-900 dark:text-blue-100">
							Total: R$ {{ totalAmount }}
						</p>
					</div>
				</div>

				<div class="flex justify-between mt-6">
					<AtButton variant="secondary" @click="cancelSale">
						Cancelar Venda
					</AtButton>
					<AtButton variant="primary" @click="finalizeSale" :loading="finalizing" :disabled="items.length === 0">
						Finalizar Venda
					</AtButton>
				</div>
			</div>
		</ModalBody>
	</BaseModal>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import BaseModal from '../../components/organisms/BaseModal.vue'
import ModalHeader from '../../components/organisms/ModalHeader.vue'
import ModalBody from '../../components/organisms/ModalBody.vue'
import MolFormGroup from '../../components/molecules/FormGroup.vue'
import FormSelect from '../../components/molecules/FormSelect.vue'
import AtButton from '../../components/atoms/Button.vue'
import api from '../../services/api'
import { Icon } from '@iconify/vue'

const props = defineProps({
	show: {
		type: Boolean,
		default: false
	}
})

const emit = defineEmits(['close', 'created'])
const toast = useToast()

const currentStep = ref(1)
const loading = ref(false)
const addingItem = ref(false)
const finalizing = ref(false)

const selectedCustomerId = ref('')
const selectedCustomerName = ref('')
const saleId = ref(null)
const items = ref([])
const totalAmount = ref('0.00')

const customerOptions = ref([])
const productOptions = ref([])

const newItem = ref({
	product_id: '',
	quantity: 1
})

async function loadCustomers() {
	try {
		const response = await api.get('/customers')
		customerOptions.value = response.data.data.map(customer => ({
			value: customer.id,
			label: customer.name
		}))
	} catch (error) {
		toast.error('Erro ao carregar clientes.')
	}
}

async function loadProducts() {
	try {
		const response = await api.get('/products')
		productOptions.value = response.data.data.map(product => ({
			value: product.id,
			label: `${product.name} - R$ ${product.price} (Estoque: ${product.stock_quantity})`
		}))
	} catch (error) {
		toast.error('Erro ao carregar produtos.')
	}
}

onMounted(() => {
	loadCustomers()
	loadProducts()
})

async function createSale() {
	loading.value = true
	try {
		const response = await api.post('/sales/store', {
			customer_id: selectedCustomerId.value
		})
		saleId.value = response.data.data.id
		selectedCustomerName.value = customerOptions.value.find(c => c.value == selectedCustomerId.value)?.label || ''
		currentStep.value = 2
		toast.success('Venda criada! Adicione produtos.')
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao criar venda.')
	} finally {
		loading.value = false
	}
}

async function addItem() {
	addingItem.value = true
	try {
		const response = await api.post(`/sales/item/${saleId.value}`, {
			product_id: newItem.value.product_id,
			quantity: parseInt(newItem.value.quantity)
		})
		
		await loadSaleDetails()
		
		newItem.value = { product_id: '', quantity: 1 }
		toast.success('Item adicionado com sucesso.')
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao adicionar item.')
	} finally {
		addingItem.value = false
	}
}

async function loadSaleDetails() {
	try {
		const response = await api.get(`/sales/${saleId.value}`)
		const sale = response.data
		items.value = sale.items.map(item => ({
			id: item.id,
			product_name: item.product?.name || 'Produto',
			quantity: item.quantity,
			unit_price: parseFloat(item.unit_price).toFixed(2),
			total_price: parseFloat(item.total_price).toFixed(2)
		}))
		totalAmount.value = parseFloat(sale.total_amount).toFixed(2)
	} catch (error) {
		console.error('Erro ao carregar detalhes da venda:', error)
	}
}

async function removeItem(itemId) {
	try {
		await api.delete(`/sales/${saleId.value}/items/${itemId}`)
		await loadSaleDetails()
		toast.success('Item removido.')
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao remover item.')
	}
}

async function finalizeSale() {
	finalizing.value = true
	try {
		await api.post(`/sales/pay/${saleId.value}`)
		toast.success('Venda finalizada com sucesso!')
		emit('created')
		close()
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao finalizar venda.')
	} finally {
		finalizing.value = false
	}
}

async function cancelSale() {
	try {
		await api.post(`/sales/cancel/${saleId.value}`)
		toast.info('Venda cancelada.')
		close()
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao cancelar venda.')
	}
}

function close() {
	currentStep.value = 1
	selectedCustomerId.value = ''
	saleId.value = null
	items.value = []
	totalAmount.value = '0.00'
	newItem.value = { product_id: '', quantity: 1 }
	emit('close')
}
</script>
