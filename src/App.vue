<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Loading from "@/components/Loading.vue"; // Pastikan path sesuai dengan proyekmu

const isLoading = ref(false);
const router = useRouter();

// Tampilkan loading saat berpindah halaman
router.beforeEach((to, from, next) => {
  isLoading.value = true;
  setTimeout(() => next(), 500); // Simulasi delay 500ms
});

router.afterEach(() => {
  isLoading.value = false;
});
</script>

<template>
  <Loading :isLoading="isLoading" />

  <transition name="fade" mode="out-in">
    <RouterView />
  </transition>
</template>

<style>
/* Efek transisi halaman */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease-in-out;
}

.fade-enter, 
.fade-leave-to {
  opacity: 0;
}
</style>
