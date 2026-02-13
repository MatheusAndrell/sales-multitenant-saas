<script setup>
defineProps({
  collapsed: {
    type: Boolean,
    default: false
  }
})
</script>

<template>
  <div class="flex items-center justify-center w-full h-16">
    <RouterLink to="/dashboard" class="flex items-center gap-2">
      <div class="flex items-center justify-center w-9 h-9 rounded bg-indigo-600 text-white font-bold">
        S
      </div>

      <span class="text-lg font-semibold text-primary dark:text-white tracking-wide">
        Sales Multitenant
      </span>
    </RouterLink>
  </div>
</template>