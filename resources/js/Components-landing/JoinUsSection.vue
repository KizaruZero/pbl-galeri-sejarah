<!-- <template>
    <section class="flex flex-col items-center justify-center w-full bg-black py-6 px-5">
      <div class="relative flex flex-col items-center text-center w-full max-w-[1300px] gap-y-10">
        Garis Kiri
        <svg class="hidden md:block absolute left-10 top-10 w-40 lg:w-[447px]" viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path :d="leftLinePath" stroke="white" stroke-width="3.41894" />
        </svg> -->
  
        <!-- JOIN US -->
        <!-- <h1 class="text-white text-4xl md:text-5xl font-serif text-center relative mt-4">
          JOIN
          <span class="block">US</span>
        </h1> -->
  
        <!-- Garis Kanan -->
        <!-- <svg class="hidden md:block absolute right-10 top-10 w-40 lg:w-[447px]" viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path :d="rightLinePath" stroke="white" stroke-width="3.41894" />
        </svg>
   -->
        <!-- Banner Section -->
        <!-- <div class="relative w-full h-[460px] md:h-[600px] rounded-lg overflow-hidden">
          <img src="@assets/Logo/joinus.png" class="object-cover w-full h-full rounded-lg" alt="Join Us Banner" />
  
          <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/40 px-4">
            <h3 class="text-white text-base md:text-2xl max-w-[80%]">
              Gabung dengan kami untuk melihat dan menambah koleksi mengenai Kasunanan Surakarta.
            </h3>
            <button
              @click="$router.push('/member')"
              class="mt-4 text-white font-bellefair text-xl md:text-2xl uppercase tracking-wider 
                     px-5 py-3 border-2 border-white bg-black/50 rounded-lg transition duration-300 
                     hover:bg-white/20 cursor-pointer"
            >
              JOIN US
            </button>
          </div>
        </div>
      </div>
    </section>
  </template>  

<script setup>
import { ref } from "vue";

const leftLinePath = "M10 50 C150 100, 250 0, 400 50";
const rightLinePath = "M10 50 C150 0, 250 100, 400 50";
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}
</style> -->

<template>
  <section class="relative bg-black text-white py-24 px-6 overflow-hidden border-y border-white/10">
    <!-- Subtle background shapes -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center relative z-10">
      <!-- Left side with icon and title -->
      <div class="flex items-center space-x-4">
        <div class="text-white text-5xl bg-white/10 p-4 rounded-full shadow-md">
          <i class="fas fa-user-plus"></i>
        </div>
        <h2 class="text-4xl md:text-5xl font-bold">Become a Member</h2>
      </div>

      <!-- Right side -->
      <div>
        <p class="text-gray-400 text-lg leading-relaxed mb-6">
          Join as a member to enjoy exclusive features.<br />
          Upload images and videos of your work, and<br />
          share your creativity with the world.
        </p>
        <button
          class="px-6 py-3 border border-white rounded-full font-medium text-sm hover:bg-white hover:text-black transition-all duration-300"
        >
          Join Us
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
// Tidak ada logic
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
</style>

