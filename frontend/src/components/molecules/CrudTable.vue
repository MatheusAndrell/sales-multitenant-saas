<template>
  <div class="relative overflow-x-auto bg-white dark:bg-gray-800 shadow-xs rounded-base border border-gray-200 dark:border-gray-700">
    <table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-100">
      <thead class="text-sm bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
        <tr>
          <th v-for="col in columns" :key="col.key" scope="col" class="px-6 py-3 font-medium">
            {{ col.label }}
          </th>
          <th v-if="hasActions" class="px-6 py-3 font-medium text-center">
            Ações
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows" :key="row.id" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
          <td v-for="col in columns" :key="col.key" class="px-6 py-4">
            <slot :name="`cell-${col.key}`" :row="row" :value="row[col.key]">
              {{ row[col.key] }}
            </slot>
          </td>
          <td v-if="hasActions" class="px-6 py-4 text-center flex justify-center gap-2">
            <AtButton size="sm" variant="primary" @click="$emit('edit', row)">{{ editLabel }}</AtButton>
            <AtButton size="sm" variant="danger" @click="$emit('delete', row)">{{ deleteLabel }}</AtButton>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import AtButton from '../atoms/Button.vue'
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  columns: Array,
  rows: Array,
  hasActions: {
    type: Boolean,
    default: true
  },
  editLabel: {
    type: String,
    default: 'Editar'
  },
  deleteLabel: {
    type: String,
    default: 'Deletar'
  }
})

const emit = defineEmits(['edit', 'delete'])
</script>
