<template>
  <footer class="bg-black text-white py-10 px-30 flex flex-wrap justify-between text-center md:text-left">
    <!-- Bagian Kiri: Logo -->
    <div class="md:w-1/3 w-full flex flex-col gap-4 items-center md:items-start">
      <div class="flex items-center gap-4">
        <img src="../assets/img/Logo/LOGO.png" class="object-contain w-16" alt="Kasunanan logo" />
        <div class="flex flex-col">
          <h2 class="text-2xl uppercase font-semibold leading-tight">Kasunanan</h2>
          <h2 class="text-2xl uppercase font-semibold leading-tight">Surakarta</h2>
        </div>
      </div>
    </div>

    <!-- Bagian Tengah: Navigasi -->
    <div class="md:w-1/3 w-full flex flex-col items-center md:items-center order-2 md:order-none mt-6 md:mt-0">
      <nav class="grid grid-cols-2 md:grid-cols-3 gap-6 text-sm uppercase">
        <router-link to="/" class="hover:underline">Home</router-link>
        <router-link to="/member" class="hover:underline">Member</router-link>
        <router-link to="/events" class="hover:underline">Events</router-link>
        <router-link to="/history" class="hover:underline">History</router-link>
        <router-link to="/gallery" class="hover:underline">Gallery</router-link>
        <router-link to="/contact" class="hover:underline">Contact</router-link>
      </nav>
    </div>

    <!-- Bagian Kanan: Info Kontak -->
    <div class="w-full md:w-1/3 flex flex-col gap-3 text-sm font-light items-center md:items-end order-3 mt-6 md:mt-0">
      <div class="flex items-center gap-2">
        <img src="../assets/img/Logo/Vector.png" class="w-4" alt="Email icon" />
        <a href="mailto:work@uxmcreativestudio.com">work@uxmcreativestudio.com</a>
      </div>
      <div class="flex items-center gap-2">
        <img src="../assets/img/Logo/Vector2.png" class="w-4" alt="Location icon" />
        <p>New York, USA</p>
      </div>
    </div>
  </footer>
</template>