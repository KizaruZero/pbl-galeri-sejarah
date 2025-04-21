<template>
  <section class="w-full bg-black py-16 px-6 md:px-10">
    <div class="max-w-[1192px] mx-auto">
      <!-- Judul -->
      <div class="flex flex-col items-center text-white mb-12">
        <span class="w-full h-0.5 bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
          Highlight Events
        </h1>
        <span class="w-full h-0.5 bg-white mt-6"></span>
      </div>

      <!-- Grid Card -->
      <div
        class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
        >
          <LandingCard
          v-for="event in events"
          :key="event.title"
          :imageUrl="event.imageUrl"
          :title="event.title"
          :titleSize="event.titleSize"
          :description="event.description"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue";
import LandingCard from "./LandingCard.vue";

// Import lokal asset image
import KirabImage from "@assets/Sejarah/gangsa.png";
import SiniarImage from "@assets/Sejarah/gangsa.png";
import ImlekImage from "@assets/Sejarah/gangsa.png";

// Data events
const events = ref([
  {
    imageUrl: KirabImage,
    title: "Kirab Malam Selikuran",
    titleSize: "xl",
    description:
      "Kirab malam selikuran yang merupakan tradisi Keraton Solo pada malam ke-21 Ramadan menjadi AGENDA yang menarik untuk disaksikan di Kota solo malam hari ini",
  },
  {
    imageUrl: SiniarImage,
    title: "Siniar Lokananta bersama Ngobryls",
    titleSize: "sm",
    description:
      "Merayakan Hari Musik Nasional, Lokananta Bloc menggelar event live podcast bertajuk Siniar Lokananta #1 di Tribune Undakan Nada, Lokananta.",
  },
  {
    imageUrl: ImlekImage,
    title: "Pameran Seni Lukis Imlek",
    titleSize: "sm",
    description:
      "Pameran Seni Lukis Imlek 2025 digelar di lantai II Solo Grand Mall selama lima hari pada 1-5 Februari 2025",
  },
]);
</script>
