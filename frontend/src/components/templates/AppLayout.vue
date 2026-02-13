<script setup>
import Sidebar from '../organisms/Sidebar.vue'
</script>

<template>
  <Sidebar />
  <div class="sm:ml-64 ">
    <router-view />
  </div>
</template>