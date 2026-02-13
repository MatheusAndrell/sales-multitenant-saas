<template>
  <form class="space-y-4 md:space-y-6" @submit.prevent="$emit('submit')">
    <!-- Seção: Dados da Empresa -->
    <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Information</h3>
      
      <div class="space-y-4">
        <CompanyNameInput
          :modelValue="tenantName"
          @update:modelValue="$emit('update:tenantName', $event)"
        />
        
        <CompanyEmailInput
          :modelValue="tenantEmail"
          @update:modelValue="$emit('update:tenantEmail', $event)"
        />
        
        <CnpjInput
          :modelValue="cnpj"
          @update:modelValue="$emit('update:cnpj', $event)"
        />
      </div>
    </div>

    <!-- Seção: Dados do Admin -->
    <div class="pb-4">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Administrator Account</h3>
      
      <div class="space-y-4">
        <AdminNameInput
          :modelValue="adminName"
          @update:modelValue="$emit('update:adminName', $event)"
        />
        
        <AdminEmailInput
          :modelValue="adminEmail"
          @update:modelValue="$emit('update:adminEmail', $event)"
        />
        
        <PasswordInput
          :modelValue="password"
          @update:modelValue="$emit('update:password', $event)"
        />
      </div>
    </div>

    <!-- Seção: Termos e Condições -->
    <div class="flex items-start">
      <div class="flex items-center h-5">
        <Checkbox
          id="agree-terms"
          :modelValue="agreeTerms"
          @update:modelValue="$emit('update:agreeTerms', $event)"
        />
      </div>
      <div class="ml-3 text-sm">
        <label for="agree-terms" class="text-gray-500 dark:text-gray-300">
          I agree to the 
          <AtLink href="#" variant="primary">Terms and Conditions</AtLink>
        </label>
      </div>
    </div>

    <!-- Botões -->
    <Button 
      type="submit" 
      variant="primary" 
      size="md" 
      class="w-full"
      :disabled="loading"
    >
      {{ loading ? 'Creating account...' : 'Create Account' }}
    </Button>
    
    <div class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
      Already have an account? 
      <AtLink href="/login" variant="primary">Sign in</AtLink>
    </div>
  </form>
</template>

<script setup>
import CompanyNameInput from '../molecules/CompanyNameInput.vue'
import CompanyEmailInput from '../molecules/CompanyEmailInput.vue'
import CnpjInput from '../molecules/CnpjInput.vue'
import AdminNameInput from '../molecules/AdminNameInput.vue'
import AdminEmailInput from '../molecules/AdminEmailInput.vue'
import PasswordInput from '../molecules/PasswordInput.vue'
import Checkbox from '../atoms/Checkbox.vue'
import Button from '../atoms/Button.vue'

defineProps({
  tenantName: String,
  tenantEmail: String,
  cnpj: String,
  adminName: String,
  adminEmail: String,
  password: String,
  agreeTerms: Boolean,
  loading: {
    type: Boolean,
    default: false
  }
})

defineEmits([
  'submit',
  'update:tenantName',
  'update:tenantEmail',
  'update:cnpj',
  'update:adminName',
  'update:adminEmail',
  'update:password',
  'update:agreeTerms'
])
</script>
