<template>
  <FormGroup
    id="admin-name"
    label="Full Name"
    type="text"
    name="admin_name"
    placeholder="Your full name"
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
