<template>
  <FormGroup
    id="cnpj"
    label="CNPJ"
    type="text"
    name="cnpj"
    placeholder="XX.XXX.XXX/0001-XX"
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
