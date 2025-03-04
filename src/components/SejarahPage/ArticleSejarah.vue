<template>
    <div class="flex flex-wrap justify-center gap-6 bg-black p-6">
      <article
        v-for="(item, index) in articles"
        :key="index"
        @click="goToDetail(item)"
        class="overflow-hidden px-6 pt-5 pb-16 text-white w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] max-w-[470px] cursor-pointer transition hover:scale-105"
      >
        <img
          :src="item.image"
          class="object-contain w-full aspect-[1.38]"
          :alt="item.title"
        />
        <h2 class="mt-5 text-3xl">
          {{ item.title }}
        </h2>
        <p class="mt-3 text-base">
          {{ item.content }}
        </p>
      </article>
    </div>
  </template>
  
  <script>
  import { useRouter } from "vue-router";
  import pusakaImage from "@/assets/img/Sejarah/pusaka.png";
  import gerbongImage from "@/assets/img/Sejarah/gerbong.png";
  import latbelImage from "@/assets/img/Sejarah/latbel.png";
  import bregadaImage from "@/assets/img/Sejarah/bregada.png";
  import meriamImage from "@/assets/img/Sejarah/meriam.png";
  import latbel2Image from "@/assets/img/Sejarah/latbel2.png";
  import gangsaImage from "@/assets/img/Sejarah/gangsa.png";
  import sriImage from "@/assets/img/Sejarah/sri.png";
  import jejakImage from "@/assets/img/Sejarah/jejak.png";
  
  export default {
    name: "KeratonCard",
    setup() {
      const router = useRouter();
  
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
          image: gerbongImage,
          slug: "gerbong-pesiar",
        },
        {
          title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat",
          content: "Mataram merupakan sebuah dinasti kerajaan Islam...",
          image: latbelImage,
          slug: "latar-belakang-keraton",
        },
        {
          title: "Bregada - Prajurit Karaton Surakarta Hadiningrat",
          content: "Prajurit keraton Surakarta adalah...",
          image: bregadaImage,
          slug: "bregada-keraton",
        },
        {
          title: "Pusaka - Meriam Nyai Setomi",
          content: "Meriam yang konon ikut serta dalam penggempuran...",
          image: meriamImage,
          slug: "meriam-nyai-setomi",
        },
        {
          title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat",
          content: "Penangkapan Pangeran Diponegoro...",
          image: latbel2Image,
          slug: "latar-belakang-keraton-2",
        },
        {
          title: "Gangsa Pakurmatan Sekaten",
          content: "Kehadiran gamelan Sekaten tidak terlepas...",
          image: gangsaImage,
          slug: "gangsa-sekaten",
        },
        {
          title: "Sri Susuhunan Pakubuwana XIII",
          content: "Sinuhun Pakubuwana XIII lahir pada 28 Juni 1948...",
          image: sriImage,
          slug: "pakubuwana-xiii",
        },
        {
          title: "Jejak Keberanian Pakubuwana IV - Pakepung",
          content: "Dominasi kongsi dagang Belanda di Hindia Timur...",
          image: jejakImage,
          slug: "pakubuwana-iv-pakepung",
        },
      ];
  
      const goToDetail = (item) => {
        router.push({ name: "Detail", params: { slug: item.slug } });
      };
  
      return { articles, goToDetail };
    },
  };
  </script>
  