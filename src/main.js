import './assets/main.css'

import { createApp } from 'vue'
import PrimeVue from 'primevue/config';
import Carousel from 'primevue/carousel';
import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(router)
app.use(PrimeVue);
app.component('Carousel', Carousel);


app.mount('#app')
