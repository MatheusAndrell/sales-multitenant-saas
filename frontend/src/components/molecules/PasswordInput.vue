<template>
  <FormGroup
    id="password"
    label="Password"
    type="password"
    name="password"
    placeholder="••••••••"
    :modelValue="modelValue"
    @update:modelValue="$emit('update:modelValue', $event)"
  />
</template>

<script setup>
import FormGroup from './FormGroup.vue'

defineProps({
  modelValue: String
})

defineEmits(['update:modelValue'])
</script>
