<template>
  <FormGroup
    id="tenant-email"
    label="Company Email"
    type="email"
    name="tenant_email"
    placeholder="contato@empresa.com"
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
