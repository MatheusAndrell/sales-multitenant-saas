<script setup>
defineProps({ title: String })
</script>

<template>
  <div class="flex flex-col items-center w-full mt-3 border-t border-gray-300 dark:border-gray-700">
    <slot />
  </div>
</template>