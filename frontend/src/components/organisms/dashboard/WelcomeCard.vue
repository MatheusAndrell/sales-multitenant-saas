<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
      Bem-vindo, {{ user?.name }}!
    </h2>
    <p class="text-gray-600 dark:text-gray-300">
      Email: {{ user?.email }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  user: {
    type: Object,
    default: null
  }
})
</script>
