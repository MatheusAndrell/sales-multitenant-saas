<template>
	<BaseModal :show="show" @close="close">
		<form @submit.prevent="handleSubmit">
			<ModalHeader>
				<h3 class="text-lg font-semibold text-gray-900 dark:text-white">Adicionar Usuário</h3>
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
					label="Senha"
					type="password"
					placeholder="Mínimo 8 caracteres"
					v-model="form.password"
				/>
				<MolFormGroup
					label="Confirmar Senha"
					type="password"
					placeholder="Repita a senha"
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
import { ref } from 'vue'
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
	}
})

const emit = defineEmits(['close', 'created'])
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

function close() {
	form.value = {
		name: '',
		email: '',
		password: '',
		password_confirmation: '',
		role: ''
	}
	emit('close')
}

async function handleSubmit() {
	loading.value = true
	try {
		await api.post('/users/store', form.value)
		toast.success('Usuário criado com sucesso.')
		emit('created')
		close()
	} catch (err) {
		toast.error(err.response?.data?.message || 'Erro ao criar usuário.')
	} finally {
		loading.value = false
	}
}
</script>
