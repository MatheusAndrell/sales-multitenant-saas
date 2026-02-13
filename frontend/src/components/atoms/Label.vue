<template>
  <label :for="htmlFor" :class="classes">
    <slot />
  </label>
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  htmlFor: String,
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'required'].includes(value)
  }
})

const classes = computed(() => {
  const base = 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
  return base
})
</script>
