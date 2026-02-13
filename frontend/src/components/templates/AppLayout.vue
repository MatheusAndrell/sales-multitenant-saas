<script setup>
import Sidebar from '../organisms/Sidebar.vue'
</script>

<template>
  <Sidebar />
  <div class="sm:ml-64 p-4">
    <router-view />
  </div>
</template>