<template>
  <div class="flex items-start">
    <div class="flex items-center h-5">
      <Checkbox
        id="remember"
        aria-describedby="remember"
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue', $event)"
      />
    </div>
    <div class="ml-3 text-sm">
      <label for="remember" class="text-gray-500 dark:text-gray-300">
        {{ label }}
      </label>
    </div>
  </div>
</template>

<script setup>
import Checkbox from '../atoms/Checkbox.vue'

defineProps({
  label: String,
  modelValue: Boolean
})

defineEmits(['update:modelValue'])
</script>
