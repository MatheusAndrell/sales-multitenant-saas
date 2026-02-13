<template>
  <form class="space-y-4 md:space-y-6" @submit.prevent="$emit('submit')">
    <EmailInput
      :modelValue="email"
      @update:modelValue="$emit('update:email', $event)"
    />
    
    <PasswordInput
      :modelValue="password"
      @update:modelValue="$emit('update:password', $event)"
    />
    
    <FormFooter
      :rememberMe="rememberMe"
      @update:rememberMe="$emit('update:rememberMe', $event)"
    />
    
    <Button 
      type="submit" 
      variant="primary" 
      size="md" 
      class="w-full"
      :disabled="loading"
    >
      {{ loading ? 'Signing in...' : 'Sign in' }}
    </Button>
    
    <FormHelper
      text="Don't have an account yet?"
      link-text="Sign up"
      href="/register"
    />
  </form>
</template>

<script setup>
import EmailInput from '../molecules/EmailInput.vue'
import PasswordInput from '../molecules/PasswordInput.vue'
import FormFooter from '../molecules/FormFooter.vue'
import FormHelper from '../molecules/FormHelper.vue'
import Button from '../atoms/Button.vue'

defineProps({
  email: String,
  password: String,
  rememberMe: Boolean,
  loading: {
    type: Boolean,
    default: false
  }
})

defineEmits(['submit', 'update:email', 'update:password', 'update:rememberMe'])
</script>
