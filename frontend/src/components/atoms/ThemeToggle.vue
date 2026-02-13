<script setup>
import { ref, onMounted } from 'vue'
import { Icon } from '@iconify/vue'

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

<template>
  <button @click="toggleTheme" class="relative w-16 h-8 rounded-full transition-colors duration-500 focus:outline-none"
    :class="isDark ? 'bg-gray-700' : 'bg-yellow-300'">
    <div class="absolute top-1 left-1 w-6 h-6 rounded-full bg-white shadow-md
             flex items-center justify-center
             transform transition-transform duration-500 ease-in-out"
      :class="isDark ? 'translate-x-8' : 'translate-x-0'">
      <Icon :icon="isDark ? 'mdi:weather-night' : 'mdi:white-balance-sunny'" class="text-lg" />
    </div>
  </button>
</template>