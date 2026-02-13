<template>
  <input
    :type="type"
    :name="name"
    :id="id"
    :placeholder="placeholder"
    :value="modelValue"
    :class="classes"
    @input="$emit('update:modelValue', $event.target.value)"
    v-bind="$attrs"
  />
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  type: {
    type: String,
    default: 'text'
  },
  name: String,
  id: String,
  placeholder: String,
  modelValue: [String, Number],
  error: Boolean
})

defineEmits(['update:modelValue'])

const classes = computed(() => {
  const base = 'bg-gray-50 border text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
  const borderColor = 'border-gray-300 dark:border-gray-600'
  return `${base} ${borderColor}`
})
</script>
