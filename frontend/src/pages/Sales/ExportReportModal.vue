<template>
  <BaseModal :show="show" @close="close" size="md">
    <ModalHeader>
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exportar Relatorio (PDF)</h3>
      <AtButton size="sm" variant="tertiary" @click="close" type="button">
        <Icon
          icon="material-symbols:close-rounded"
          class="dark:text-white dark:hover:text-gray-300 text-primary hover:text-blue-700 cursor-pointer"
        />
      </AtButton>
    </ModalHeader>

    <ModalBody>
      <div class="space-y-4">
        <FormGroup
          label="Email"
          type="email"
          placeholder="email@exemplo.com"
          v-model="form.email"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <FormGroup
            label="Data inicial"
            type="date"
            v-model="form.start_date"
          />
          <FormGroup
            label="Data final"
            type="date"
            v-model="form.end_date"
          />
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
          Se nao informar as datas, sera usado o periodo padrao do sistema.
        </p>
      </div>
    </ModalBody>

    <ModalFooter>
      <AtButton variant="secondary" @click="close" type="button">Cancelar</AtButton>
      <AtButton
        variant="primary"
        :loading="loading"
        :disabled="!form.email"
        @click="submit"
        type="button"
      >
        Enviar Relatorio
      </AtButton>
    </ModalFooter>
  </BaseModal>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useToast } from 'vue-toastification'
import { Icon } from '@iconify/vue'
import BaseModal from '../../components/organisms/BaseModal.vue'
import ModalHeader from '../../components/organisms/ModalHeader.vue'
import ModalBody from '../../components/organisms/ModalBody.vue'
import ModalFooter from '../../components/organisms/ModalFooter.vue'
import FormGroup from '../../components/molecules/FormGroup.vue'
import AtButton from '../../components/atoms/Button.vue'
import api from '../../services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'sent'])
const toast = useToast()
const loading = ref(false)

const form = reactive({
  email: '',
  start_date: '',
  end_date: ''
})

function close() {
  emit('close')
}

async function submit() {
  if (!form.email) return

  loading.value = true
  try {
    const payload = {
      email: form.email
    }

    if (form.start_date) {
      payload.start_date = form.start_date
    }

    if (form.end_date) {
      payload.end_date = form.end_date
    }

    await api.post('/reports/sales/email', payload)

    toast.success('Relatorio enviado para o email informado.')
    emit('sent')
    close()
  } catch (error) {
    toast.error(error.response?.data?.message || 'Erro ao solicitar relatorio.')
  } finally {
    loading.value = false
  }
}
</script>
