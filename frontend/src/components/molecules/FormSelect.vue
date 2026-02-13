<template>
  <div>
    <Label :html-for="id">{{ label }}</Label>
    <Select
      :id="id"
      :name="name"
      :placeholder="placeholder"
      :modelValue="modelValue"
      :options="options"
      @update:modelValue="$emit('update:modelValue', $event)"
    />
  </div>
</template>

<script setup>
import Label from '../atoms/Label.vue'
import Select from '../atoms/Select.vue'

defineProps({
  id: String,
  label: String,
  name: String,
  placeholder: {
    type: String,
    default: 'Selecione uma opção'
  },
  modelValue: [String, Number],
  options: {
    type: Array,
    default: () => []
  }
})

defineEmits(['update:modelValue'])
</script>
