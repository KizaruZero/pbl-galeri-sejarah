<template>
    <footer class="bg-black text-white py-8 px-8 flex flex-wrap justify-center gap-16 text-center md:text-left">
      <!-- Bagian Kiri: Logo dan Navigasi -->
      <div class="md:w-1/3 w-full flex flex-col gap-4 items-center md:items-start">
        <div class="flex items-center gap-4">
          <img src="../assets/img/Logo/LOGO.png" class="object-contain w-16" alt="Kasunanan logo" />
          <h2 class="text-2xl uppercase font-semibold">Kasunanan Surakarta</h2>
        </div>
  
        <h3 class="text-lg uppercase font-semibold">Newsletter</h3>
        <p class="text-sm font-light">Get daily updates about events, products, and more</p>
        <form class="mt-3 w-full max-w-sm flex gap-3">
          <input type="email" placeholder="Enter your Email" class="w-full px-3 py-2 border border-white bg-transparent text-white text-sm focus:outline-none" required />
          <button type="submit" class="px-4 py-2 border border-white bg-neutral-800 text-white text-sm hover:bg-neutral-700 transition-colors">
            Subscribe
          </button>
        </form>
  
        <h3 class="text-lg uppercase font-semibold mt-4">Reach us</h3>
        <div class="flex gap-6 items-center text-sm font-light mt-2">
          <div class="flex items-center gap-2">
            <img src="../assets/img/Logo/Vector.png" class="w-4" alt="Email icon" />
            <a href="mailto:work@uxmcreativestudio.com">work@uxmcreativestudio.com</a>
          </div>
          <div class="flex items-center gap-2">
            <img src="../assets/img/Logo/Vector2.png" class="w-4" alt="Location icon" />
            <p>New York, USA</p>
          </div>
        </div>
      </div>
  
      <!-- Bagian Tengah: Navigasi -->
      <div class="md:w-1/3 w-full flex flex-col items-center">
        <nav class="grid grid-cols-2 gap-6 text-sm uppercase">
          <router-link to="/" class="hover:underline">Home</router-link>
          <router-link to="/member" class="hover:underline">Member</router-link>
          <router-link to="/events" class="hover:underline">Events</router-link>
          <router-link to="/history" class="hover:underline">History</router-link>
          <router-link to="/gallery" class="hover:underline">Gallery</router-link>
          <router-link to="/contact" class="hover:underline">Contact</router-link>
        </nav>
      </div>
    </footer>
  </template>
  