<script setup>
import { computed } from 'vue'
import SidebarItem from '../molecules/SidebarItem.vue'
import SidebarSection from '../molecules/SidebarSection.vue'
import AppLogo from '../atoms/AppLogo.vue'
import { useAuth } from '../../composables/useAuth'

const baseMenu = [
  {
    section: 'main',
    items: [
      { icon: 'mdi:view-dashboard-outline', label: 'Dashboard', to: '/dashboard' },
      { icon: 'icon-park-outline:city', label: 'Clientes', to: '/customers' },
      { icon: 'mdi:cart-outline', label: 'Produtos', to: '/products' },
      { icon: 'material-symbols:box-outline', label: 'Vendas', to: '/sales' },
      { icon: 'mdi:users-outline', label: 'Usuários', to: '/users' }
    ]
  },
]

const { user } = useAuth()

const isAdmin = computed(() => (user.value?.roles || []).includes('Admin da Loja'))
const isSeller = computed(() => (user.value?.roles || []).includes('Vendedor'))

const menu = computed(() => {
  if (isAdmin.value) {
    return baseMenu
  }

  if (isSeller.value) {
    return [
      {
        section: 'main',
        items: baseMenu[0].items.filter(item => ['Dashboard', 'Clientes', 'Vendas'].includes(item.label))
      }
    ]
  }

  return []
})
</script>

<template>
  <aside class="fixed top-0 left-0 w-64 h-full">
    <div class="flex flex-col items-center w-64 h-full overflow-hidden text-gray-400 bg-white dark:bg-gray-800">
      <AppLogo />

      <div class="w-full px-2 flex-1">
        <SidebarSection v-for="section in menu" :key="section.section">
          <SidebarItem v-for="item in section.items" :key="item.label" v-bind="item" />
        </SidebarSection>
      </div>

      <div class="w-full flex justify-center mb-6 mt-auto">
        <AtThemeToggle />
      </div>
    </div>
  </aside>
</template>