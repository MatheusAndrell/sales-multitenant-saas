<template>
  <section class="w-full h-full bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 w-full h-full overflow-y-auto">
         <AppLogo />

      <Card>
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white mb-2">
          Create your account
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
          Set up your company and create your administrator account
        </p>
        
        <div v-if="errorMessage" class="mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
          <p class="text-red-700 dark:text-red-200 text-sm">{{ errorMessage }}</p>
        </div>

        <RegisterForm
          :tenantName="tenantName"
          :tenantEmail="tenantEmail"
          :cnpj="cnpj"
          :adminName="adminName"
          :adminEmail="adminEmail"
          :password="password"
          :agreeTerms="agreeTerms"
          :loading="loading"
          @submit="handleSubmit"
          @update:tenantName="tenantName = $event"
          @update:tenantEmail="tenantEmail = $event"
          @update:cnpj="cnpj = $event"
          @update:adminName="adminName = $event"
          @update:adminEmail="adminEmail = $event"
          @update:password="password = $event"
          @update:agreeTerms="agreeTerms = $event"
        />
      </Card>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import Logo from '../atoms/Logo.vue'
import Card from '../molecules/Card.vue'
import RegisterForm from '../organisms/RegisterForm.vue'
import AppLogo from '../atoms/AppLogo.vue'

const router = useRouter()
const { register } = useAuth()

const tenantName = ref('')
const tenantEmail = ref('')
const cnpj = ref('')
const adminName = ref('')
const adminEmail = ref('')
const password = ref('')
const agreeTerms = ref(false)
const loading = ref(false)
const errorMessage = ref('')

const handleSubmit = async () => {
  errorMessage.value = ''

  // Validações
  if (!tenantName.value || !tenantEmail.value || !cnpj.value) {
    errorMessage.value = 'Please fill in all company information fields'
    return
  }

  if (!adminName.value || !adminEmail.value || !password.value) {
    errorMessage.value = 'Please fill in all administrator account fields'
    return
  }

  if (!agreeTerms.value) {
    errorMessage.value = 'You must agree to the Terms and Conditions'
    return
  }

  if (password.value.length < 8) {
    errorMessage.value = 'Password must be at least 8 characters long'
    return
  }

  loading.value = true

  try {
    const registerData = {
      tenant_name: tenantName.value,
      tenant_email: tenantEmail.value,
      cnpj: cnpj.value,
      admin_name: adminName.value,
      admin_email: adminEmail.value,
      password: password.value
    }

    console.log('Sending registration data:', registerData)

    const result = await register(registerData)

    if (result.success) {
      // Token foi salvo automaticamente no useAuth
      // Redirecionar para dashboard
      router.push('/dashboard')
    } else {
      errorMessage.value = 'Registration failed. Please try again.'
    }
  } catch (error) {
    const errorData = error.response?.data
    if (errorData?.message) {
      errorMessage.value = errorData.message
    } else {
      errorMessage.value = 'Error creating account. Please try again.'
    }
    console.error('Registration error:', error)
  } finally {
    loading.value = false
  }
}
</script>
