import { createApp } from 'vue'
import './style.css'
import 'vue-toastification/dist/index.css'
import App from './App.vue'
import router from './router'
import { registerGlobalComponents } from './components'
import Toast from 'vue-toastification'

const app = createApp(App)

// Registra o router
app.use(router)

// Registra o toast global
app.use(Toast)

// Registra todos os componentes globalmente
registerGlobalComponents(app)

app.mount('#app')
