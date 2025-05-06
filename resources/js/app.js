import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import PrimeVue from 'primevue/config'
import Carousel from 'primevue/carousel'

import { LoopingRhombusesSpinner } from 'epic-spinners'

import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons' // Solid icons
import { far } from '@fortawesome/free-regular-svg-icons' // Regular icons
import { fab } from '@fortawesome/free-brands-svg-icons' // Brand icons
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue')
    const page = (await pages[`./Pages/${name}.vue`]()).default

    return page
  },
  setup({ el, App, props, plugin }) {
    library.add(fas, far, fab)
    const vueApp = createApp({ render: () => h(App, props) })

    return vueApp
      .use(plugin)
      .use(ZiggyVue)
      .use(PrimeVue)
      .component('Carousel', Carousel)
      .component('LoopingRhombusesSpinner', LoopingRhombusesSpinner) // Register global spinner
      .component('font-awesome-icon', FontAwesomeIcon)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})
