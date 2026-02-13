<template>
  <div class="flex items-center h-5">
    <input
      :id="id"
      type="checkbox"
      :checked="modelValue"
      :class="classes"
      @change="$emit('update:modelValue', $event.target.checked)"
      v-bind="$attrs"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  id: String,
  modelValue: Boolean,
  error: Boolean
})

defineEmits(['update:modelValue'])

const classes = computed(() => {
  const base = 'w-4 h-4 border rounded focus:ring-3 focus:ring-primary-300'
  const borderColor = 'border-gray-300 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800'
  return `${base} ${borderColor}`
})
</script>
