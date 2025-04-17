<template>
  <section class="w-full bg-black py-16 px-6 md:px-10">
    <div class="max-w-[1192px] mx-auto">
      <!-- Judul -->
      <div class="flex flex-col items-center text-white mb-12">
        <span class="w-full h-0.5 bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
         Articles
        </h1>
        <span class="w-full h-0.5 bg-white mt-6"></span>
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
  </section>
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
