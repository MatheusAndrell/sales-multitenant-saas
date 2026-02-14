<template>
  <div class="flex justify-between items-center mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
      <p v-if="periodLabel" class="text-sm text-gray-600 dark:text-gray-300">
        Periodo: {{ periodLabel }}
      </p>
    </div>
    <AtButton variant="secondary" @click="$emit('logout')">Logout</AtButton>
  </div>
</template>

<script setup>
import AtButton from '../../atoms/Button.vue'

defineProps({
  periodLabel: {
    type: String,
    default: ''
  }
})

defineEmits(['logout'])
</script>
