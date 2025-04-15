<template>
  <nav class="bg-black text-white p-4 flex justify-between items-center">
    <!-- Tombol menu untuk mode mobile -->
    <div class="flex items-center w-full md:w-auto">
      <button @click="toggleMenu" class="md:hidden text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
      </button>
      <img src="@assets/Logo/LOGO.png" alt="Logo" class="md:hidden ml-auto object-contain w-[50px] h-auto">
    </div>

    <!-- Navbar (desktop) -->
    <div class="hidden md:flex flex-1 justify-start gap-6 gap-x-20 px-40 text-base tracking-widest uppercase">
      <Link href="/" class="relative group py-2">Home
        <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
      </Link>
      <Link href="/events" class="relative group py-2">Events
        <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
      </Link>
      <Link href="/gallery" class="relative group py-2">Gallery
        <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
      </Link>
    </div>

    <!-- Logo (desktop) -->
    <img src="@assets/Logo/LOGO.png" alt="Logo" class="hidden md:block object-contain w-[80px] h-auto">

    <!-- Bagian Kanan -->
    <div class="hidden md:flex flex-1 justify-end gap-6 gap-x-20 px-40 text-base tracking-widest uppercase">
      <Link href="/member" class="relative group">Member
        <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
      </Link>
      <Link href="/sejarah" class="relative group">History
        <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
      </Link>
      <Link href="/contact" class="relative group">Contact
        <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
      </Link>
    </div>
  </nav>

  <!-- Sidebar (mobile) -->
  <div v-if="menuOpen" class="fixed inset-0 bg-black bg-opacity-75 z-50">
    <div class="w-64 bg-black h-full p-6 flex flex-col gap-4">
      <button @click="toggleMenu" class="text-white self-end">✕</button>
      <Link href="/" class="text-white py-2" @click="toggleMenu">Home</Link>
      <Link href="/events" class="text-white py-2" @click="toggleMenu">Events</Link>
      <Link href="/gallery" class="text-white py-2" @click="toggleMenu">Gallery</Link>
      <Link href="/member" class="text-white py-2" @click="toggleMenu">Member</Link>
      <Link href="/sejarah" class="text-white py-2" @click="toggleMenu">History</Link>
      <Link href="/contact" class="text-white py-2" @click="toggleMenu">Contact</Link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const menuOpen = ref(false);

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};
</script>