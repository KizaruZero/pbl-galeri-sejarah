<template>
  <main>
    <MainLayout>
      <div class="flex flex-col items-center justify-center bg-black text-white p-6">
        <template v-if="article">
          <h1 class="text-4xl font-bold">{{ article.title }}</h1>
          <img :src="article.image" class="mt-5 w-full max-w-lg" :alt="article.title" />
          <p class="mt-4 text-lg">{{ article.content }}</p>
        </template>

        <template v-else>
          <p class="text-xl mt-10">Artikel tidak ditemukan.</p>
        </template>
      </div>
    </MainLayout>
  </main>
</template>

<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import MainLayout from '../components/layouts/MainLayout.vue';

// Import gambar
import pusakaImage from "@/assets/img/Sejarah/pusaka.png";
import gerbongImage from "@/assets/img/Sejarah/gerbong.png";
import latbelImage from "@/assets/img/Sejarah/latbel.png";
import bregadaImage from "@/assets/img/Sejarah/bregada.png";
import meriamImage from "@/assets/img/Sejarah/meriam.png";
import latbel2Image from "@/assets/img/Sejarah/latbel2.png";
import gangsaImage from "@/assets/img/Sejarah/gangsa.png";
import sriImage from "@/assets/img/Sejarah/sri.png";
import jejakImage from "@/assets/img/Sejarah/jejak.png";

// Ambil parameter route
const route = useRoute();

// Data artikel
const articles = [
  { title: "Pusaka - Canthik Kyai Rajamala", content: "...", image: pusakaImage, slug: "pusaka-kyai-rajamala" },
  { title: "Gerbong pesiar Paku Buwana X", content: "...", image: gerbongImage, slug: "gerbong-pesiar" },
  { title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat", content: "...", image: latbelImage, slug: "latar-belakang-keraton" },
  { title: "Bregada - Prajurit Karaton Surakarta Hadiningrat", content: "...", image: bregadaImage, slug: "bregada-keraton" },
  { title: "Pusaka - Meriam Nyai Setomi", content: "...", image: meriamImage, slug: "meriam-nyai-setomi" },
  { title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat", content: "...", image: latbel2Image, slug: "latar-belakang-keraton-2" },
  { title: "Gangsa Pakurmatan Sekaten", content: "...", image: gangsaImage, slug: "gangsa-sekaten" },
  { title: "Jejak Keberanian Pakubuwana IV - Pakepung", content: "...", image: jejakImage, slug: "pakubuwana-iv-pakepung" },
  { title: "Sri Susuhunan Pakubuwana XIII", content: "...", image: sriImage, slug: "pakubuwana-xiii" },
];

// Cari artikel berdasarkan slug
const article = computed(() => articles.find(item => item.slug === route.params.slug));
</script>
