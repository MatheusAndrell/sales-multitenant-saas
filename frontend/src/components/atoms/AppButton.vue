<script setup>
defineProps({
  icon: String
})
</script>

<template>
  <button
    class="text-heading hover:bg-neutral-secondary-medium rounded-base p-2 sm:hidden"
  >
    <AppIcon :name="icon" class="w-6 h-6" />
  </button>
</template>