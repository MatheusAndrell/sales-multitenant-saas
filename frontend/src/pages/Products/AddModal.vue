<template>
	<BaseModal :show="show" @close="close">
		<form @submit.prevent="handleSubmit">
			<ModalHeader>
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Adicionar Produto</h3>
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
import { ref } from 'vue'
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
	}
})

const emit = defineEmits(['close', 'created'])
const loading = ref(false)
const toast = useToast()
const form = ref({
	name: '',
	description: '',
	category: '',
	price: '',
	stock_quantity: ''
})

function close() {
	form.value = {
		name: '',
		description: '',
		category: '',
		price: '',
		stock_quantity: ''
	}
	emit('close')
}

async function handleSubmit() {
	loading.value = true
	try {
		await api.post('/products/store', form.value)
		toast.success('Produto criado com sucesso.')
		close()
		setTimeout(() => window.location.reload(), 800)
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao criar produto.')
	} finally {
		loading.value = false
	}
}
</script>
