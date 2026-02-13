<template>
	<BaseModal :show="show" @close="close">
		<form @submit.prevent="handleSubmit">
			<ModalHeader>
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Cliente</h3>
				<AtButton size="sm" variant="tertiary" @click="close" type="button">
					<Icon icon="material-symbols:close-rounded" 
						class="dark:text-white dark:hover:text-gray-300 text-primary hover:text-blue-700 cursor-pointer" />
				</AtButton>
			</ModalHeader>
			<ModalBody>
				<MolFormGroup
					label="Nome"
					placeholder="Nome completo"
					v-model="form.name"
				/>
				<MolFormGroup
					label="Email"
					type="email"
					placeholder="Email"
					v-model="form.email"
				/>
				<MolFormGroup
					label="Telefone"
					placeholder="Telefone"
					v-model="form.phone"
				/>
				<MolFormGroup
					label="Documento"
					placeholder="Documento"
					v-model="form.document"
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
	customerId: {
		type: [Number, String],
		required: true
	}
})

const emit = defineEmits(['close', 'updated'])
const loading = ref(false)
const toast = useToast()
const form = ref({
	name: '',
	email: '',
	phone: '',
	document: '',
})

const defaultForm = {
	name: '',
	email: '',
	phone: '',
	document: '',
}

function close() {
	emit('close')
}

async function loadCustomer() {
	if (!props.customerId) {
		return
	}

	loading.value = true
	try {
		const response = await api.get(`/customers/${props.customerId}`)
		const customer = response.data
		form.value = {
			...defaultForm,
			name: customer.name || '',
			email: customer.email || '',
			phone: customer.phone || '',
			document: customer.document || '',
		}
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao buscar cliente.')
	} finally {
		loading.value = false
	}
}

watch(
	() => [props.show, props.customerId],
	([show]) => {
		if (show) {
			loadCustomer()
		}
	},
	{ immediate: true }
)

async function handleSubmit() {
	loading.value = true
	try {
		await api.put(`/customers/update/${props.customerId}`, form.value)
		toast.success('Cliente atualizado com sucesso.')
		emit('updated')
		close()
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao atualizar cliente.')
	} finally {
		loading.value = false
	}
}
</script>
