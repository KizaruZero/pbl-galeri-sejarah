<template>
  <div class="relative flex flex-col bg-black px-5">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <h1 class="ml-20 text-white text-4xl md:text-5xl font-serif mt-10 text-center relative">
        <span class="block w-24 h-0.5 bg-white mb-8"></span>
        Articles
        <span class="block w-full h-0.5 bg-white mt-8"></span>
      </h1>
    </div>

  <div class="grid grid-cols-2 sm:flex sm:flex-wrap justify-center gap-4 sm:gap-6 bg-black p-4 sm:p-6">
    <article
      v-for="(item, index) in articles"
      :key="index"
      @click="goToDetail(item)"
      class="overflow-hidden px-4 pt-4 pb-6 text-white cursor-pointer transition hover:scale-105 
             w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] max-w-[470px] text-center"
        >
        <img
          :src="item.image"
          class="object-cover w-full aspect-[1.38] rounded-t-lg"
          :alt="item.title"
        />
        <h2 class="mt-3 text-base sm:text-lglg:text-2xl font-semibold leading-tight">
        {{ item.title }}
        </h2>
        <p class="mt-2 text-xs sm:text-sm text-gray-300 leading-relaxed">
        {{ item.content }}
        </p>
      </article>
    </div>
  </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import pusakaImage from "@assets/Sejarah/pusaka.png";
import gerbongImage from "@assets/Sejarah/gerbong.png";
import latbelImage from "@assets/Sejarah/latbel.png";


const articles = [
  {
    title: "Pusaka - Canthik Kyai Rajamala",
    content: "Di sebuah negeri yang konon bernama Wirata...",
    image: pusakaImage,
    slug: "pusaka-kyai-rajamala",
  },
  {
    title: "Gerbong pesiar Paku Buwana X",
    content: "Industri kereta api di Surakarta...",
    image: pusakaImage,
    slug: "gerbong-pesiar",
  },
  {
    title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat",
    content: "Mataram merupakan sebuah dinasti kerajaan Islam...",
    image: pusakaImage,
    slug: "latar-belakang-keraton",
  },
];

const goToDetail = (item) => {
  router.visit(route("Detail", { slug: item.slug } ));
};
</script>
