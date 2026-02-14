<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center p-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center max-w-md w-full">
      <h1 class="text-4xl font-bold text-gray-900 dark:text-white">433</h1>
      <p class="text-gray-600 dark:text-gray-300 mt-2">Nao permitido</p>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
        Voce nao tem permissao para acessar esta pagina.
      </p>
      <div class="mt-6">
        <AtButton variant="primary" @click="goHome">Voltar ao Dashboard</AtButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import AtButton from '../components/atoms/Button.vue'

const router = useRouter()

const goHome = () => {
  router.push('/dashboard')
}
</script>
