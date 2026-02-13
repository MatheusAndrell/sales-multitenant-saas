<template>
  <a :href="href" :class="classes" v-bind="$attrs">
    <slot />
  </a>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  href: String,
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'tertiary'].includes(value)
  }
})

const classes = computed(() => {
  const variantClasses = {
    primary: 'text-sm font-medium text-primary-600 hover:underline dark:text-primary-500',
    secondary: 'text-sm font-light text-gray-500 hover:underline dark:text-gray-400',
    tertiary: 'text-gray-900 hover:underline dark:text-white'
  }

  return variantClasses[props.variant]
})
</script>
