<script setup>
import AppIcon from '../atoms/AppIcon.vue'

defineProps({
  icon: String,
  label: String,
  to: String,
  badge: String
})
</script>

<template>
  <RouterLink :to="to" class="flex items-center w-full h-12 px-3 mt-2 rounded dark:hover:bg-gray-700 hover:text-gray-300 hover:bg-gray-300 ">
    <AppIcon :name="icon" class="w-6 h-6" />
    <span class="ml-2 text-sm font-medium dark:text-white dark:hover:text-gray-300 hover:text-blue-700 text-primary">{{ label }}</span>

    <span v-if="badge" class="ml-auto text-xs bg-indigo-500 px-2 py-0.5 rounded">
      {{ badge }}
    </span>
  </RouterLink>
</template>