<template>
  <button
    :type="type"
    :class="classes"
    :disabled="disabled"
    @click="$emit('click')"
    v-bind="$attrs"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'tertiary', 'danger'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  disabled: Boolean
})

defineEmits(['click'])

const classes = computed(() => {
  const base = 'font-medium rounded-lg text-center transition-all cursor-pointer'
  
  const sizeClasses = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-5 py-2.5 text-sm',
    lg: 'px-6 py-3 text-base'
  }

  const variantClasses = {
    primary: 'text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-primary dark:bg-primary dark:hover:bg-blue-800 dark:focus:ring-primary',
    secondary: 'text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800',
    tertiary: 'text-gray-500 hover:text-gray-700 dark:text-gray-400',
    danger: 'text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800'
  }

  const disabledClass = 'opacity-50 cursor-not-allowed'

  return `${base} ${sizeClasses[props.size]} ${variantClasses[props.variant]} ${props.disabled ? disabledClass : ''}`
})
</script>
