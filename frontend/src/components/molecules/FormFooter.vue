<template>
  <div class="flex items-center justify-between">
    <CheckboxLabel
      label="Remember me"
      :modelValue="rememberMe"
      @update:modelValue="$emit('update:rememberMe', $event)"
    />
    <Link href="#" variant="primary">Forgot password?</Link>
  </div>
</template>

<script setup>
import CheckboxLabel from './CheckboxLabel.vue'
import Link from '../atoms/Link.vue'

defineProps({
  rememberMe: Boolean
})

defineEmits(['update:rememberMe'])
</script>
