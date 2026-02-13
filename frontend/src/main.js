import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import { registerGlobalComponents } from './components'

const app = createApp(App)

// Registra o router
app.use(router)

// Registra todos os componentes globalmente
registerGlobalComponents(app)

app.mount('#app')
