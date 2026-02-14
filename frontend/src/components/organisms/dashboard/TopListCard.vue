<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ title }}</h3>
    <ul class="space-y-2">
      <li v-for="item in items" :key="item[idKey]" class="flex justify-between text-sm">
        <span class="text-gray-700 dark:text-gray-300">{{ item[labelKey] }}</span>
        <span class="text-gray-500 dark:text-gray-400">{{ formatValue(item[valueKey]) }}</span>
      </li>
    </ul>
    <p v-if="items.length === 0" class="text-sm text-gray-500 dark:text-gray-400">Sem dados</p>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true
  },
  items: {
    type: Array,
    default: () => []
  },
  idKey: {
    type: String,
    default: 'id'
  },
  labelKey: {
    type: String,
    default: 'name'
  },
  valueKey: {
    type: String,
    required: true
  },
  valueFormatter: {
    type: Function,
    default: null
  }
})

const formatValue = (value) => {
  if (props.valueFormatter) {
    return props.valueFormatter(value)
  }
  return value
}
</script>
