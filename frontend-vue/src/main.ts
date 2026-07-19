import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import "@fortawesome/fontawesome-free/css/all.min.css"
import "aos/dist/aos.css";
import "./assets/main.css"



import { createApp } from 'vue'
import App from './App.vue'
import myRoutes from './components/routes'
import { createPinia } from 'pinia'

const pinia =createPinia();
const app =createApp(App);

app.use(pinia)
app.use(myRoutes)
app.mount('#app')