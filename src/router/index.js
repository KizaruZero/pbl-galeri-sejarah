import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import BudayaView from '../views/BudayaView.vue'
import SejarahView from '../views/SejarahView.vue'
import DetailSejarah from '@/views/DetailSejarah.vue'
import GalleryView from '../views/GalleryView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/events',
      name: 'events',
      component: BudayaView,
    },
    {
      path: '/sejarah',
      name: 'sejarah',
      component: SejarahView,
    },
    {
      path: '/gallery',
      name: 'gallery',
      component: GalleryView,
    },
    {
      path: '/detail/:slug',
      name: 'Detail',
      component: DetailSejarah, props: true,
    },
  ],
})

export default router
