import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import PrimeVue from 'primevue/config'
import Carousel from 'primevue/carousel'
import '@fortawesome/fontawesome-free/css/all.min.css';
// import '../css/filament/admin/theme.css';

import { LoopingRhombusesSpinner } from 'epic-spinners'
import NotificationList from './Components/NotificationList.vue'
import 'lite-youtube-embed/src/lite-yt-embed'  // Correct import path

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue')
    const page = (await pages[`./Pages/${name}.vue`]()).default
    return page
  },
  setup({ el, App, props, plugin }) {
    const vueApp = createApp({ render: () => h(App, props) })

    return vueApp
      .use(plugin)
      .use(ZiggyVue)
      .use(PrimeVue)
      .component('Carousel', Carousel)
      .component('LoopingRhombusesSpinner', LoopingRhombusesSpinner)
      .component('notification-list', NotificationList)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})