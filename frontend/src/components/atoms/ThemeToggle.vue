<template>
  <button
    @click="toggleTheme"
    class="flex items-center justify-center w-10 h-10 rounded-full transition-colors duration-300 focus:outline-none"
    :class="isDark ? 'bg-gray-700 text-yellow-400' : 'bg-yellow-300 text-gray-800'"
    aria-label="Alternar tema"
  >
    <span v-if="isDark" class="iconify" data-icon="mdi:weather-night" data-inline="false"></span>
    <span v-else class="iconify" data-icon="mdi:white-balance-sunny" data-inline="false"></span>
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isDark = ref(false)

onMounted(() => {
  isDark.value = document.documentElement.classList.contains('dark')
})

function toggleTheme() {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}
</script>

<style scoped>
.iconify {
  font-size: 1.5rem;
  transition: transform 0.3s;
}
button:active .iconify {
  transform: rotate(20deg) scale(1.1);
}
</style>
