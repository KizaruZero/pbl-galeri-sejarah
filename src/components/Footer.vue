<template>
    <footer class="bg-black text-white py-10 px-12 flex flex-wrap justify-center gap-12 text-center md:text-left">
      <!-- Bagian Kiri: Logo dan Navigasi -->
      <div class="md:w-1/3 w-full flex flex-col gap-6 items-center md:items-start">
        <div class="flex items-center gap-4">
          <img
            src="../assets/img/Logo/LOGO.png"
            class="object-contain w-[72px]"
            alt="Kasunanan logo"
          />
          <h2 class="text-3xl uppercase font-semibold">Kasunanan Surakarta</h2>
        </div>
        <nav class="grid grid-cols-2 gap-8 text-sm uppercase">
          <router-link to="/" class="hover:underline">Home</router-link>
          <router-link to="/updates" class="hover:underline">Updates</router-link>
          <router-link to="/events" class="hover:underline">Events</router-link>
          <router-link to="/history" class="hover:underline">History</router-link>
          <router-link to="/gallery" class="hover:underline">Gallery</router-link>
          <router-link to="/contact" class="hover:underline">Contact</router-link>
        </nav>
      </div>
  
      <!-- Bagian Tengah: Newsletter -->
      <div class="md:w-1/3 w-full flex flex-col items-center">
        <h3 class="text-xl uppercase font-semibold">Newsletter</h3>
        <p class="text-sm font-light mt-2">Get daily updates about events, products, and more</p>
        <form class="mt-4 w-full max-w-sm">
          <input
            type="email"
            placeholder="Enter your Email"
            class="w-full px-4 py-2 border border-white bg-transparent text-white text-sm focus:outline-none"
            required
          />
          <button
            type="submit"
            class="mt-2 w-full py-2 border border-white bg-neutral-800 text-white text-sm hover:bg-neutral-700 transition-colors"
          >
            Subscribe
          </button>
        </form>
        
        <h3 class="text-xl uppercase font-semibold mt-6">Reach us</h3>
        <div class="mt-4 space-y-3">
          <div class="flex items-center gap-2">
            <img src="../assets/icons/email.png" class="w-5" alt="Email icon" />
            <a href="mailto:work@uxmcreativestudio.com" class="text-sm font-light">
              work@uxmcreativestudio.com
            </a>
          </div>
          <div class="flex items-center gap-2">
            <img src="../assets/icons/location.png" class="w-5" alt="Location icon" />
            <p class="text-sm font-light">New York, USA</p>
          </div>
        </div>
      </div>
    </footer>
  </template>
  