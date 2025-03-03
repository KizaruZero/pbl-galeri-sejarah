import { createRouter, createWebHistory } from 'vue-router'
import BudayaView from '../views/BudayaView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/budaya',
      name: 'budaya',
      component: BudayaView,
    },
  ],
})

export default router
