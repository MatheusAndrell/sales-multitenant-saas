<template>
  <div class="relative inline-block">
    <div
      class="cursor-help text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
      @mouseenter="isVisible = true"
      @mouseleave="isVisible = false"
    >
      <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
      </svg>
    </div>

    <transition name="fade">
      <div
        v-if="isVisible"
        class="absolute left-0 mt-2 w-64 bg-white dark:bg-neutral-700 text-gray-900 dark:text-white text-sm rounded-lg shadow-lg p-4 z-50"
      >
        <div class="font-semibold mb-3 text-gray-900 dark:text-white">Credenciais de Teste:</div>
        
        <div class="mb-3">
          <div class="text-gray-700 dark:text-gray-300 font-medium">Admin</div>
          <div class="text-gray-400 text-xs mt-1">
            Email: <span class="text-blue-400">admin@admin.com</span>
          </div>
          <div class="text-gray-400 text-xs">
            Senha: <span class="text-blue-400">admin123</span>
          </div>
        </div>

        <div>
          <div class="text-gray-700 dark:text-gray-300 font-medium">Vendedor</div>
          <div class="text-gray-400 text-xs mt-1">
            Email: <span class="text-blue-400">vendedor@vendedor.com</span>
          </div>
          <div class="text-gray-400 text-xs">
            Senha: <span class="text-blue-400">vendedor123</span>
          </div>
        </div>

        <!-- Seta do tooltip -->
        <div class="absolute top-0 left-4 -mt-1 text-gray-900 dark:text-gray-800">
          <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M0 11l10-10 10 10z" />
          </svg>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isVisible = ref(false)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
