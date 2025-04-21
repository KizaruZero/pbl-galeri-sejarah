<template>
  <nav class="bg-black text-white py-3 px-4 md:px-6 shadow-md fixed top-0 left-0 w-full z-50 font-poppins">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center w-full">

      <!-- Mobile: Menu & Logo -->
      <div class="flex lg:hidden w-full items-center justify-between">
        <button @click="toggleMenu" class="text-white focus:outline-none">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
        <img src="@assets/Logo/LOGO.png" alt="Logo" class="w-10 h-auto object-contain" />
      </div>

      <!-- Desktop: Menu Kiri -->
      <div class="hidden lg:flex flex-1 justify-start gap-20 xl:gap-40 text-sm tracking-wider uppercase font-medium">
        <Link href="/" class="relative group py-1">Home
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </Link>
        <Link href="/events" class="relative group py-1">Events
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </Link>
        <Link href="/gallery" class="relative group py-1">Gallery
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </Link>
      </div>

      <!-- Desktop: Logo Tengah -->
      <div class="hidden lg:block mx-6">
        <img src="@assets/Logo/LOGO.png" alt="Logo" class="w-[50px] h-auto object-contain" />
      </div>

      <!-- Desktop: Menu Kanan -->
      <div class="hidden lg:flex flex-1 justify-end gap-20 xl:gap-40 text-sm tracking-wider uppercase font-medium">
        <Link href="/article" class="relative group py-1">Article
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </Link>
        <Link href="/contact" class="relative group py-1">Contact
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </Link>
        <Link href="/login" class="relative group py-1">Login
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </Link>
      </div>
    </div>
  </nav>

  <!-- Sidebar (Mobile) -->
  <transition name="slide">
    <div v-if="menuOpen" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex">
      <div class="w-64 bg-black h-full p-6 flex flex-col gap-6">
        <button @click="toggleMenu" class="text-white self-end text-2xl">✕</button>
        <Link href="/" class="text-white text-lg py-1" @click="toggleMenu">Home</Link>
        <Link href="/events" class="text-white text-lg py-1" @click="toggleMenu">Events</Link>
        <Link href="/gallery" class="text-white text-lg py-1" @click="toggleMenu">Gallery</Link>
        <Link href="/article" class="text-white text-lg py-1" @click="toggleMenu">Article</Link>
        <Link href="/contact" class="text-white text-lg py-1" @click="toggleMenu">Contact</Link>
        <Link href="/login" class="text-white text-lg py-1" @click="toggleMenu">Login</Link>
      </div>
      <div class="flex-1" @click="toggleMenu"></div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const menuOpen = ref(false);
const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from {
  transform: translateX(-100%);
}
.slide-enter-to {
  transform: translateX(0);
}
.slide-leave-from {
  transform: translateX(0);
}
.slide-leave-to {
  transform: translateX(-100%);
}
</style>
