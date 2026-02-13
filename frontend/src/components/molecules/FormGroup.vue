<template>
  <div>
    <Label :html-for="id">{{ label }}</Label>
    <TextInput
      :id="id"
      :type="type"
      :name="name"
      :placeholder="placeholder"
      :modelValue="modelValue"
      @update:modelValue="$emit('update:modelValue', $event)"
    />
  </div>
</template>

<script setup>
import Label from '../atoms/Label.vue'
import TextInput from '../atoms/TextInput.vue'

defineProps({
  id: String,
  label: String,
  type: {
    type: String,
    default: 'text'
  },
  name: String,
  placeholder: String,
  modelValue: [String, Number],
  error: Boolean
})

defineEmits(['update:modelValue'])
</script>
