<template>
    <nav
      class="flex justify-between items-center w-full px-20 py-5 text-base tracking-widest text-center text-white uppercase whitespace-nowrap bg-black max-md:px-5"
    >
      <!-- Bagian Kiri -->
      <div class="flex gap-6 flex-1 justify-start">
        <router-link to="/" class=" border-neutral-700 py-2 px-4 relative group">
          Home
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </router-link>
        <router-link to="/events" class=" border-neutral-700 py-2 px-4 relative group">
          Events
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </router-link>
        <router-link to="/gallery" class=" border-neutral-700 py-2 px-4 relative group">
          Gallery
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </router-link>
      </div>
  
      <!-- Logo -->
      <img
        src="../assets/img/Logo/LOGO.png"
        alt="Logo"
        class="object-contain shrink-0 w-[80px] h-auto"
      />
  
      <!-- Bagian Kanan -->
      <div class="flex gap-6 flex-1 justify-end">
        <router-link to="/member" class=" border-neutral-700 py-2 px-4 relative group">
          Member
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </router-link>
        <router-link to="/sejarah" class=" border-neutral-700 py-2 px-4 relative group">
          History
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </router-link>
        <router-link to="/contact" class=" border-neutral-700 py-2 px-4 relative group">
          Contact
          <span class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform"></span>
        </router-link>
      </div>
    </nav>
  </template>
