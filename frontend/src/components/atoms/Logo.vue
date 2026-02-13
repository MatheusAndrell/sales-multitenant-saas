<template>
  <div class="flex items-center" :class="{ 'mb-6': spacing }">
    <img :src="src" :alt="alt" :class="imageClasses" />
    <span v-if="text" :class="textClasses">{{ text }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  src: {
    type: String,
    required: true
  },
  alt: {
    type: String,
    default: 'logo'
  },
  text: String,
  spacing: Boolean,
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
})

const imageClasses = computed(() => {
  const sizes = {
    sm: 'w-6 h-6',
    md: 'w-8 h-8',
    lg: 'w-10 h-10'
  }
  return `${sizes[props.size]} mr-2`
})

const textClasses = computed(() => {
  const sizes = {
    sm: 'text-lg font-semibold',
    md: 'text-2xl font-semibold',
    lg: 'text-3xl font-semibold'
  }
  return `${sizes[props.size]} text-gray-900 dark:text-white`
})
</script>
