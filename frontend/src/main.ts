import './assets/main.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { useCounterStore } from './stores/counter'

library.add(fas)

import App from './App.vue'
import router from './router'

const app = createApp(App)
const pinia = createPinia()

app.component('font-awesome-icon', FontAwesomeIcon)

app.use(pinia)
app.use(router)

// Inicializácia aplikácie - načíta stav autentifikácie z localStorage
const counterStore = useCounterStore()
counterStore.checkAuth()

// Mountovanie aplikácie
app.mount('#app')