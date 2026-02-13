<template>
  <FormGroup
    id="email"
    label="Your email"
    type="email"
    name="email"
    placeholder="name@company.com"
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
