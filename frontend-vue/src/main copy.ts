import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import "@fortawesome/fontawesome-free/css/all.min.css"
import AOS from "aos";
import "aos/dist/aos.css";

import { createApp, onMounted } from 'vue'
import App from './App.vue'
import myRoutes from './components/routes'
import { createPinia } from 'pinia'



const pinia =createPinia();
const app =createApp(App);
onMounted(() => {
  AOS.init({ duration: 1000, once: true });
});

app.use(pinia)
app.use(myRoutes)
app.mount('#app')