<script setup>
import SidebarItem from '../molecules/SidebarItem.vue'
import SidebarSection from '../molecules/SidebarSection.vue'
import AppLogo from '../atoms/AppLogo.vue'

const menu = [
  {
    section: 'main',
    items: [
      { icon: 'mdi:view-dashboard', label: 'Dashboard', to: '/dashboard' },
      { icon: 'icon-park-outline:city', label: 'Clientes', to: '/customers' },
      { icon: 'mdi:cart-outline', label: 'Produtos', to: '/products' },
      { icon: 'material-symbols:box-outline', label: 'Vendas', to: '/sales' },
      { icon: 'mdi:users-outline', label: 'Usuários', to: '/users' }
    ]
  },
]
</script>

<template>
  <aside class="fixed top-0 left-0 w-64 h-full">
    <div class="flex flex-col items-center w-64 h-full overflow-hidden text-gray-400 bg-gray-800">
      <AppLogo />

      <div class="w-full px-2 flex-1">
        <SidebarSection v-for="section in menu" :key="section.section">
          <SidebarItem v-for="item in section.items" :key="item.label" v-bind="item" />
        </SidebarSection>
      </div>

      <!-- <div class="w-full flex justify-center mb-6 mt-auto">
        <AtThemeToggle />
      </div> -->
    </div>
  </aside>
</template>