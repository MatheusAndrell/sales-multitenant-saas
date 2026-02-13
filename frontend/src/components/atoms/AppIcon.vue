<script setup>
import { Icon } from '@iconify/vue'
defineProps({ name: String, class: String })
</script>

<template>
  <Icon :icon="name" :class="class" class="dark:text-white dark:hover:text-gray-300 text-red-500" />
</template>