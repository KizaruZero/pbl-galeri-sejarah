<template>
  <div class="relative flex flex-col bg-black px-5">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <h1 class="ml-20 text-white text-4xl md:text-5xl font-serif mt-10 text-center relative">
        <span class="block w-24 h-0.5 bg-white mb-8"></span>
        Tranding Content
        <span class="block w-full h-0.5 bg-white mt-8"></span>
      </h1>
    </div>
  </div>

<section class="p-10 w-full bg-black">
  <div
    class="grid grid-cols-3 gap-14 mx-auto max-w-[1192px] max-md:grid-cols-2 max-sm:grid-cols-1"
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
