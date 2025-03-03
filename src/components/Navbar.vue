<template>
    <nav
      class="flex justify-between items-center w-full px-20 py-5 text-base tracking-widest text-center text-white uppercase whitespace-nowrap bg-black max-md:px-5"
    >
      <!-- Bagian Kiri -->
      <div class="flex gap-6 flex-1 justify-start">
        <router-link to="/" class="border border-solid border-neutral-700 py-2 px-4">
          Home
        </router-link>
        <router-link to="/events" class="border border-solid border-neutral-700 py-2 px-4">
          Events
        </router-link>
        <router-link to="/gallery" class="border border-solid border-neutral-700 py-2 px-4">
          Gallery
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
        <router-link to="/member" class="border border-solid border-neutral-700 py-2 px-4">
          Member
        </router-link>
        <router-link to="/history" class="border border-solid border-neutral-700 py-2 px-4">
          History
        </router-link>
        <router-link to="/contact" class="border border-solid border-neutral-700 py-2 px-4">
          Contact
        </router-link>
      </div>
    </nav>
  </template>
  