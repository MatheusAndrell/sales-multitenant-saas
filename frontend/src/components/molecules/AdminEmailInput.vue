<template>
  <FormGroup
    id="admin-email"
    label="Admin Email"
    type="email"
    name="admin_email"
    placeholder="admin@email.com"
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
