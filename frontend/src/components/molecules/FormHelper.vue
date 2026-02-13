<template>
  <div class="text-sm font-light text-gray-500 dark:text-gray-400">
    {{ text }}
    <Link :href="href" variant="secondary">{{ linkText }}</Link>
  </div>
</template>

<script setup>
import Link from '../atoms/Link.vue'

defineProps({
  text: String,
  linkText: String,
  href: String
})
</script>
