<template>
	<BaseModal :show="show" @close="close">
		<form @submit.prevent="handleSubmit">
			<ModalHeader>
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Editar Usuário</h3>
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
					placeholder="email@exemplo.com"
					v-model="form.email"
				/>
				<MolFormGroup
					label="Nova Senha (opcional)"
					type="password"
					placeholder="Deixe em branco para manter"
					v-model="form.password"
				/>
				<MolFormGroup
					label="Confirmar Nova Senha"
					type="password"
					placeholder="Repita a nova senha"
					v-model="form.password_confirmation"
				/>
				<FormSelect
					label="Função"
					placeholder="Selecione uma função"
					v-model="form.role"
					:options="roleOptions"
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
import FormSelect from '../../components/molecules/FormSelect.vue'
import AtButton from '../../components/atoms/Button.vue'
import api from '../../services/api'
import { Icon } from '@iconify/vue'

const props = defineProps({
	show: {
		type: Boolean,
		default: false
	},
	userId: {
		type: [Number, String],
		required: true
	}
})

const emit = defineEmits(['close', 'updated'])
const loading = ref(false)
const toast = useToast()

const roleOptions = [
	{ value: 'Admin da Loja', label: 'Admin da Loja' },
	{ value: 'Vendedor', label: 'Vendedor' }
]

const form = ref({
	name: '',
	email: '',
	password: '',
	password_confirmation: '',
	role: ''
})

const defaultForm = {
	name: '',
	email: '',
	password: '',
	password_confirmation: '',
	role: ''
}

function close() {
	emit('close')
}

async function loadUser() {
	if (!props.userId) {
		return
	}

	loading.value = true
	try {
		const response = await api.get(`/users/${props.userId}`)
		const user = response.data
		form.value = {
			...defaultForm,
			name: user.name || '',
			email: user.email || '',
			password: '',
			password_confirmation: '',
			role: user.roles && user.roles.length > 0 ? user.roles[0].name : ''
		}
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao buscar usuário.')
	} finally {
		loading.value = false
	}
}

watch(
	() => [props.show, props.userId],
	([show]) => {
		if (show) {
			loadUser()
		}
	},
	{ immediate: true }
)

async function handleSubmit() {
	loading.value = true
	try {
		const payload = {
			name: form.value.name,
			email: form.value.email,
			role: form.value.role
		}
		
		if (form.value.password) {
			payload.password = form.value.password
			payload.password_confirmation = form.value.password_confirmation
		}
		
		await api.put(`/users/update/${props.userId}`, payload)
		toast.success('Usuário atualizado com sucesso.')
		emit('updated')
		close()
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao atualizar usuário.')
	} finally {
		loading.value = false
	}
}
</script>
