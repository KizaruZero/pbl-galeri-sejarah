<template>
    <main class="relative p-5 bg-black text-white">
      <!-- Title & Button -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-5 md:px-20 mt-10">
        <h1 class="text-4xl md:text-5xl font-serif text-center md:text-left relative">
          <span class="block w-24 h-0.5 bg-white mb-6 md:mb-8 mx-auto md:mx-0"></span>
          Upcoming Events
          <span class="block w-full h-0.5 bg-white mt-6 md:mt-8"></span>
        </h1>
        <Link href="/events" class="flex items-center justify-center w-[60px] h-[60px] md:w-[70px] md:h-[70px] rounded-full border border-white text-white text-base md:text-lg transition-all duration-300 hover:scale-105 hover:bg-white hover:text-black"
        >View All
        </Link>
      </div>
  
      <!-- Carousel -->
      <div class="mt-16 pb-20">
        <Carousel
          :value="slides"
          :numVisible="3"
          :numScroll="1"
          :responsiveOptions="responsiveOptions"
          circular
          :autoplayInterval="3000"
        >
          <template #item="slotProps">
            <div
              class="relative mx-4 md:mx-10 bg-zinc-900 rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-[1.01] flex flex-col"
            >
              <!-- Gambar -->
              <div class="relative w-full h-[200px] sm:h-[250px] md:h-[500px] bg-black">
                <img
                  :src="slotProps.data.image"
                  :alt="slotProps.data.title"
                  class="object-cover w-full h-full transition-opacity duration-300"
                />
                <div class="absolute inset-0 bg-black bg-opacity-60"></div>
              </div>
  
              <!-- Konten -->
              <div class="p-4 sm:p-6 md:p-10 flex flex-col gap-2 sm:gap-3 max-[375px]:p-3">
                <div class="text-xl sm:text-2xl md:text-4xl text-neutral-200">
                  {{ String(slides.indexOf(slotProps.data) + 1).padStart(2, "0") }}
                </div>
                <h2 class="text-lg sm:text-xl md:text-3xl font-bold uppercase">
                  {{ slotProps.data.title }}
                </h2>
                <time class="text-xs uppercase">
                  {{ slotProps.data.startTime }}
                </time>
                <p
                  class="text-xs sm:text-sm md:text-base font-light tracking-wide leading-snug text-justify"
                >
                  {{ slotProps.data.description }}
                </p>
              </div>
            </div>
          </template>
        </Carousel>
      </div>
    </main>
</template>
  
  
  

<script setup>
import { ref } from "vue";
import { Link } from '@inertiajs/vue3';
import KirabImage from "@assets/landing/kirab.png";
import Wilujengan from "@assets/landing/wilujengan.png";
import Grebeg from "@assets/landing/grebeg.png";

const slides = ref([
  {
    image: KirabImage,
    title: "Kirab Malem 1 Suro",
    startTime: "Start on : 08:00 GTS . Monday",
    description:
      "Kirab malem 1 Suro adalah tradisi turun temurun dari Karaton Surakarta Hadiningrat yang dilakukan untuk memperingati datangnya Tahun Baru Jawa.",
  },
  {
    image: Wilujengan,
    title: "Cultural Festival",
    startTime: "Start on : 10:00 GTS . Wednesday",
    description:
      "Join us for a celebration of diverse cultures featuring traditional performances, authentic cuisine, and interactive workshops showcasing heritage from around the world.",
  },
  {
    image: Grebeg,
    title: "Cultural Festival",
    startTime: "Start on : 10:00 GTS . Wednesday",
    description:
      "Join us for a celebration of diverse cultures featuring traditional performances, authentic cuisine, and interactive workshops showcasing heritage from around the world.",
  },
  {
    image: "https://picsum.photos/800/600?random=2",
    title: "Music Concert",
    startTime: "Start on : 19:30 GTS . Friday",
    description:
      "Experience an unforgettable night of live music featuring acclaimed artists performing a mix of contemporary hits and classical favorites in our state-of-the-art venue.",
  },
]);

const responsiveOptions = ref([
  { breakpoint: "1400px", numVisible: 2, numScroll: 1 },
  { breakpoint: "1024px", numVisible: 1, numScroll: 1 },
  { breakpoint: "768px", numVisible: 1, numScroll: 1 },
  { breakpoint: "576px", numVisible: 1, numScroll: 1 },
]);
</script>


<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap");
</style>
