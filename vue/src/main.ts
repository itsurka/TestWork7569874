import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
// import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min'
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

createApp(App)
    .use(store)
    .use(router)
    .mount('#app')
