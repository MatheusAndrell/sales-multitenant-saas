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
    validator: (value) => ['primary', 'secondary', 'tertiary'].includes(value)
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
  const base = 'font-medium rounded-lg text-center transition-all'
  
  const sizeClasses = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-5 py-2.5 text-sm',
    lg: 'px-6 py-3 text-base'
  }

  const variantClasses = {
    primary: 'text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800',
    secondary: 'text-primary-600 hover:text-primary-700 dark:text-primary-500',
    tertiary: 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
  }

  const disabledClass = 'opacity-50 cursor-not-allowed'

  return `${base} ${sizeClasses[props.size]} ${variantClasses[props.variant]} ${props.disabled ? disabledClass : ''}`
})
</script>
