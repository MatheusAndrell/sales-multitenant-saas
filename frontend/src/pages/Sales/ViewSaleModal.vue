<template>
	<BaseModal :show="show" @close="close" size="large">
		<ModalHeader>
			<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
				Venda #{{ saleId }} - {{ statusLabel }}
			</h3>
			<AtButton size="sm" variant="tertiary" @click="close" type="button">
				<Icon icon="material-symbols:close-rounded" 
					class="dark:text-white dark:hover:text-gray-300 text-primary hover:text-blue-700 cursor-pointer" />
			</AtButton>
		</ModalHeader>

		<ModalBody>
			<div v-if="loading" class="text-center py-8">
				<p class="text-gray-600 dark:text-gray-400">Carregando...</p>
			</div>

			<div v-else class="space-y-4">
				<!-- Informações da Venda -->
				<div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg space-y-2">
					<p class="text-sm text-gray-600 dark:text-gray-300">
						<strong>Cliente:</strong> {{ sale.customer?.name || '-' }}
					</p>
					<p class="text-sm text-gray-600 dark:text-gray-300">
						<strong>Data:</strong> {{ formattedDate }}
					</p>
					<p class="text-sm text-gray-600 dark:text-gray-300">
						<strong>Status:</strong> 
						<span :class="statusClass">{{ statusLabel }}</span>
					</p>
					<p v-if="sale.paid_at" class="text-sm text-gray-600 dark:text-gray-300">
						<strong>Pago em:</strong> {{ new Date(sale.paid_at).toLocaleString('pt-BR') }}
					</p>
				</div>

				<!-- Adicionar Item (só se pending) -->
				<div v-if="sale.status === 'pending'" class="space-y-4 border-t pt-4">
					<h4 class="font-semibold text-gray-900 dark:text-white">Adicionar Item</h4>
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
				</div>

				<!-- Lista de Itens -->
				<div class="border-t pt-4">
					<h4 class="font-semibold mb-2 text-gray-900 dark:text-white">Itens da Venda</h4>
					<div v-if="sale.items && sale.items.length > 0" class="space-y-2">
						<div v-for="item in sale.items" :key="item.id" class="flex justify-between items-center bg-white dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-600">
							<div>
								<p class="font-medium text-gray-900 dark:text-white">{{ item.product?.name || 'Produto' }}</p>
								<p class="text-sm text-gray-600 dark:text-gray-400">
									{{ item.quantity }}x R$ {{ parseFloat(item.unit_price).toFixed(2) }} = R$ {{ parseFloat(item.total_price).toFixed(2) }}
								</p>
							</div>
							<AtButton 
								v-if="sale.status === 'pending'" 
								size="sm" 
								variant="danger" 
								@click="removeItem(item.id)"
							>
								Remover
							</AtButton>
						</div>
					</div>
					<p v-else class="text-gray-500 dark:text-gray-400 text-sm">Nenhum item nesta venda.</p>

					<div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
						<p class="text-lg font-bold text-blue-900 dark:text-blue-100">
							Total: R$ {{ parseFloat(sale.total_amount || 0).toFixed(2) }}
						</p>
					</div>
				</div>

				<!-- Ações -->
				<div class="flex justify-between pt-4 border-t">
					<div>
						<AtButton 
							v-if="sale.status === 'pending'" 
							variant="danger" 
							@click="cancelSale"
						>
							Cancelar Venda
						</AtButton>
					</div>
					<div class="flex gap-2">
						<AtButton variant="secondary" @click="close">
							Fechar
						</AtButton>
						<AtButton 
							v-if="sale.status === 'pending' && sale.items && sale.items.length > 0" 
							variant="primary" 
							@click="paySale" 
							:loading="paying"
						>
							Finalizar Venda
						</AtButton>
					</div>
				</div>
			</div>
		</ModalBody>
	</BaseModal>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
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
	},
	saleId: {
		type: [Number, String],
		required: true
	}
})

const emit = defineEmits(['close', 'updated'])
const toast = useToast()

const loading = ref(false)
const addingItem = ref(false)
const paying = ref(false)
const sale = ref({})
const productOptions = ref([])

const newItem = ref({
	product_id: '',
	quantity: 1
})

const statusLabels = {
	pending: 'Pendente',
	paid: 'Paga',
	cancelled: 'Cancelada'
}

const statusLabel = computed(() => statusLabels[sale.value.status] || sale.value.status)

const statusClass = computed(() => {
	const classes = {
		pending: 'text-yellow-600 dark:text-yellow-400',
		paid: 'text-green-600 dark:text-green-400',
		cancelled: 'text-red-600 dark:text-red-400'
	}
	return classes[sale.value.status] || ''
})

const formattedDate = computed(() => {
	if (!sale.value.created_at) return '-'
	return new Date(sale.value.created_at).toLocaleString('pt-BR')
})

async function loadSale() {
	if (!props.saleId) return

	loading.value = true
	try {
		const response = await api.get(`/sales/${props.saleId}`)
		sale.value = response.data
	} catch (error) {
		toast.error('Erro ao carregar venda.')
	} finally {
		loading.value = false
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
	loadProducts()
})

watch(
	() => [props.show, props.saleId],
	([show]) => {
		if (show) {
			loadSale()
		}
	},
	{ immediate: true }
)

async function addItem() {
	addingItem.value = true
	try {
		await api.post(`/sales/item/${props.saleId}`, {
			product_id: newItem.value.product_id,
			quantity: parseInt(newItem.value.quantity)
		})
		
		await loadSale()
		newItem.value = { product_id: '', quantity: 1 }
		toast.success('Item adicionado com sucesso.')
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao adicionar item.')
	} finally {
		addingItem.value = false
	}
}

async function removeItem(itemId) {
	try {
		await api.delete(`/sales/${props.saleId}/items/${itemId}`)
		await loadSale()
		toast.success('Item removido.')
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao remover item.')
	}
}

async function paySale() {
	paying.value = true
	try {
		await api.post(`/sales/pay/${props.saleId}`)
		toast.success('Venda finalizada com sucesso!')
		emit('updated')
		close()
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao finalizar venda.')
	} finally {
		paying.value = false
	}
}

async function cancelSale() {
	try {
		await api.post(`/sales/cancel/${props.saleId}`)
		toast.success('Venda cancelada.')
		emit('updated')
		close()
	} catch (error) {
		toast.error(error.response?.data?.message || 'Erro ao cancelar venda.')
	}
}

function close() {
	newItem.value = { product_id: '', quantity: 1 }
	emit('close')
}
</script>
