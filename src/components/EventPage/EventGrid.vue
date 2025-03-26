<template>
      <div class="relative flex flex-col bg-black bg-opacity-70 px-5">
        <!-- Judul -->
        <h1 class="text-white text-4xl md:text-5xl font-serif mt-10 text-center relative">
            <span class="block w-20 h-0.5 bg-white mb-4 mx-auto"></span>
            Higlight Events
            <span class="block w-20 h-0.5 bg-white mt-4 mx-auto"></span>
        </h1>
    </div>

    <section class="p-6 w-full bg-black">
      <div
        class="grid grid-cols-3 gap-14 mx-auto max-w-[1192px] max-md:grid-cols-2 max-sm:grid-cols-1"
      >
        <EventCard
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
  import EventCard from "./EventCard.vue"; // Import komponen EventCard
  
  const events = ref([
    {
      imageUrl:
        "https://cdn.builder.io/api/v1/image/assets/TEMP/bbce6064e372b518016b5bd89ff3d1e08d9bf725",
      title: "Kirab Malam Selikuran",
      titleSize: "xl",
      description:
        "Kirab malam selikuran yang merupakan tradisi Keraton Solo pada malam ke-21 Ramadan menjadi AGENDA yang menarik untuk disaksikan di Kota solo malam hari ini",
    },
    {
      imageUrl:
        "https://cdn.builder.io/api/v1/image/assets/TEMP/d2d9e37b40369f6b616466bc50e1394d5a3d2e42",
      title: "Siniar Lokananta bersama Ngobryls",
      titleSize: "sm",
      description:
        "Merayakan Hari Musik Nasional, Lokananta Bloc menggelar event live podcast bertajuk Siniar Lokananta #1 di Tribune Undakan Nada, Lokananta.",
    },
    {
      imageUrl:
        "https://cdn.builder.io/api/v1/image/assets/TEMP/15ffec671add0876b639a0ce0d9b000b76dddac4",
      title: "Pameran Seni Lukis Imlek",
      titleSize: "sm",
      description:
        "Pameran Seni Lukis Imlek 2025 digelar di lantai II Solo Grand Mall selama lima hari pada 1-5 Februari 2025",
    },
    {
      imageUrl:
        "https://cdn.builder.io/api/v1/image/assets/TEMP/1ca90f60a4bfd48b46c5bd6ee960cd242b054ec7",
      title: "Lari Sore di Solo Safari",
      titleSize: "base",
      description:
        "Mau healing sekaligus berolahraga selepas beraktivitas seharian? Lari sore di Solo Safari bisa jadi agenda yang layak dicoba.",
    },
    {
      imageUrl:
        "https://cdn.builder.io/api/v1/image/assets/TEMP/b87ebeeefa999d6ac1a43b2013f4bba5419d7997",
      title: "Solopos Youth Forum",
      titleSize: "base",
      description:
        "Solopos Media Group menggelar acara talkshow spektakuler Solopos Youth Forum 2025: Young People and Sustainability, Kreatif Menguasai Teknologi dan Kolaboratif.",
    },
    {
      imageUrl:
        "https://cdn.builder.io/api/v1/image/assets/TEMP/b682f325f1187b9fdfbeab4a3f38fa218b4554e2",
      title: "Sendratari Ramayana di Taman Balekambang",
      titleSize: "sm",
      description:
        "Sendratari Candra Purnama Ramayana malam ini menampilkan lakon Sayembara Alengka. Pertunjukan berlangsung di Amphitheater TAMAN BALEKAMBANG.",
    },
  ]);
</script>  