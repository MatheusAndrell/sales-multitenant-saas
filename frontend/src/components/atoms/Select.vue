<template>
  <select
    :id="id"
    :name="name"
    :value="modelValue"
    @change="$emit('update:modelValue', $event.target.value)"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
  >
    <option value="" disabled>{{ placeholder }}</option>
    <option 
      v-for="option in options" 
      :key="option.value" 
      :value="option.value"
    >
      {{ option.label }}
    </option>
  </select>
</template>

<script setup>
defineProps({
  id: String,
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
