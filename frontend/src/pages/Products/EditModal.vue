<template>
	<BaseModal :show="show" @close="close">
		<form @submit.prevent="handleSubmit">
			<ModalHeader>
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Produto</h3>
				<AtButton size="sm" variant="tertiary" @click="close" type="button">
					<Icon icon="material-symbols:close-rounded" 
						class="dark:text-white dark:hover:text-gray-300 text-primary hover:text-blue-700 cursor-pointer" />
				</AtButton>
			</ModalHeader>
			<ModalBody>
				<MolFormGroup
					label="Nome"
					placeholder="Nome do produto"
					v-model="form.name"
				/>
				<MolFormGroup
					label="Descrição"
					placeholder="Descrição do produto"
					v-model="form.description"
				/>
				<MolFormGroup
					label="Categoria"
					placeholder="Categoria do produto"
					v-model="form.category"
				/>
				<MolFormGroup
					label="Preço"
					type="number"
					step="0.01"
					placeholder="0.00"
					v-model="form.price"
				/>
				<MolFormGroup
					label="Quantidade em Estoque"
					type="number"
					placeholder="0"
					v-model="form.stock_quantity"
				/>
			</ModalBody>
			<ModalFooter>
				<AtButton variant="secondary" @click="close" type="button">Cancelar</AtButton>
				<AtButton variant="primary" type="submit" :loading="loading">Salvar</AtButton>
			</ModalFooter>
		</form>
	</BaseModal>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useToast } from 'vue-toastification'
import BaseModal from '../../components/organisms/BaseModal.vue'
import ModalHeader from '../../components/organisms/ModalHeader.vue'
import ModalBody from '../../components/organisms/ModalBody.vue'
import ModalFooter from '../../components/organisms/ModalFooter.vue'
import MolFormGroup from '../../components/molecules/FormGroup.vue'
import AtButton from '../../components/atoms/Button.vue'
import api from '../../services/api'
import { Icon } from '@iconify/vue'

const props = defineProps({
	show: {
		type: Boolean,
		default: false
	},
	productId: {
		type: [Number, String],
		required: true
	}
})

const emit = defineEmits(['close', 'updated'])
const loading = ref(false)
const toast = useToast()
const form = ref({
	name: '',
	description: '',
	category: '',
	price: '',
	stock_quantity: ''
})

const defaultForm = {
	name: '',
	description: '',
	category: '',
	price: '',
	stock_quantity: ''
}

function close() {
	emit('close')
}

async function loadProduct() {
	if (!props.productId) {
		return
	}

	loading.value = true
	try {
		const response = await api.get(`/products/${props.productId}`)
		const product = response.data
		form.value = {
			...defaultForm,
			name: product.name || '',
			description: product.description || '',
			category: product.category || '',
			price: product.price || '',
			stock_quantity: product.stock_quantity || ''
		}
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao buscar produto.')
	} finally {
		loading.value = false
	}
}

watch(
	() => [props.show, props.productId],
	([show]) => {
		if (show) {
			loadProduct()
		}
	},
	{ immediate: true }
)

async function handleSubmit() {
	loading.value = true
	try {
		await api.put(`/products/update/${props.productId}`, form.value)
		toast.success('Produto atualizado com sucesso.')
		emit('updated')
		close()
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao atualizar produto.')
	} finally {
		loading.value = false
	}
}
</script>
