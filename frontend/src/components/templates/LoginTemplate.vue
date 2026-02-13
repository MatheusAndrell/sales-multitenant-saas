<template>
  <section class="w-full h-full bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 w-full h-full">
      <Logo
        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
        alt="logo"
        text="Sales SaaS"
        :spacing="true"
      />
      
      <Card>
        <div class="flex items-center gap-2">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Sign in to your account
          </h1>
          <AtTooltip />
        </div>

        <div v-if="errorMessage" class="mt-4 mb-4 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
          <p class="text-red-700 dark:text-red-200 text-sm">{{ errorMessage }}</p>
        </div>
        
        <LoginForm
          :email="email"
          :password="password"
          :rememberMe="rememberMe"
          :loading="loading"
          @submit="handleSubmit"
          @update:email="email = $event"
          @update:password="password = $event"
          @update:rememberMe="rememberMe = $event"
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
import LoginForm from '../organisms/LoginForm.vue'

const router = useRouter()
const { login } = useAuth()

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const loading = ref(false)
const errorMessage = ref('')

const handleSubmit = async () => {
  errorMessage.value = ''

  if (!email.value || !password.value) {
    errorMessage.value = 'Please fill in all fields'
    return
  }

  loading.value = true

  try {
    const result = await login(email.value, password.value)

    if (result.success) {
      // Token foi salvo automaticamente no useAuth
      // Redirecionar para dashboard
      router.push('/dashboard')
    } else {
      errorMessage.value = 'Invalid email or password'
    }
  } catch (error) {
    const errorData = error.response?.data
    if (errorData?.message) {
      errorMessage.value = errorData.message
    } else {
      errorMessage.value = 'Error during login. Please try again.'
    }
    console.error('Login error:', error)
  } finally {
    loading.value = false
  }
}
</script>
