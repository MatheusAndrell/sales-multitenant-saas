<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center
           bg-black/40 backdrop-blur-sm" @click.self="$emit('close')">
    <div :class="modalClasses">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue'

const props = defineProps({
  show: Boolean,
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'large', 'xl'].includes(value)
  }
})

const emit = defineEmits(['close'])

const modalClasses = computed(() => {
  const baseClasses = 'bg-white dark:bg-gray-800 rounded-base shadow-lg p-6 relative w-full max-h-[85vh] overflow-y-auto'
  
  const sizeClasses = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-2xl',
    large: 'max-w-2xl',
    xl: 'max-w-4xl'
  }
  
  return `${baseClasses} ${sizeClasses[props.size]}`
})
</script>
