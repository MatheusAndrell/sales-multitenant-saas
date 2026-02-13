<template>
  <FormGroup
    id="tenant-name"
    label="Company Name"
    type="text"
    name="tenant_name"
    placeholder="Ex: Loja Test LTDA"
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
