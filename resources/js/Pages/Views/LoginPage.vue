<template>
  <main class="bg-white dark:bg-black">
    <!-- Add FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="flex flex-col lg:flex-row min-h-screen">
      <!-- Image section - Smaller height on mobile -->
      <section class="w-full lg:w-1/2 h-[40vh] lg:h-screen">
        <ImageSection />
      </section>

      <!-- Login Form section -->
      <section class="w-full lg:w-1/2 flex items-center justify-center py-2">
        <LoginForm />
      </section>
    </div>
  </main>
</template>

<script setup>
import ImageSection from "@/Components-landing/LoginPage/ImageSection.vue";
import ThemeToggle from "../../Components/ThemeToogle.vue";
import { ref } from "vue";
import LoginForm from "@/Components-landing/LoginPage/LoginForm.vue";

const isDarkTheme = ref(localStorage.getItem('theme') === 'dark' || true);
const toggleTheme = () => {
  isDarkTheme.value = !isDarkTheme.value;
};
</script>

<style scoped>
.theme-toggle {
    position: fixed; /* Tetap di posisi layar meskipun di-scroll */
    top: 25px; /* Jarak dari atas */
    right: 10px; /* Jarak dari kanan */
    z-index: 1000; /* Pastikan berada di atas elemen lainnya */
}

#dark-toggle:checked + label .toggle-circle {
  transform: translateX(100%);
}
</style>