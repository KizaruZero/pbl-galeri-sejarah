<template>
    <MainLayout>
        <div
            class="min-h-screen flex flex-col items-center justify-center bg-black text-white p-6"
        >
            <button
                @click="$router.back()"
                class="absolute top-4 left-4 flex items-center text-white text-lg hover:text-gray-400"
            >
                <span class="text-2xl">‹</span>
                <span class="ml-2">Kembali</span>
            </button>

            <template v-if="article">
                <h1 class="text-4xl font-bold">{{ article.title }}</h1>
                <img
                    :src="article.image"
                    class="mt-5 w-full max-w-lg"
                    :alt="article.title"
                />
                <p class="mt-4 text-lg">{{ article.content }}</p>
            </template>
            <template v-else>
                <p class="text-red-500 text-xl">Artikel tidak ditemukan.</p>
            </template>
        </div>
    </MainLayout>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

import MainLayout from "@/Layouts/MainLayout.vue";
import pusakaImage from "@assets/Sejarah/pusaka.png";
import gerbongImage from "@assets/Sejarah/gerbong.png";
import latbelImage from "@assets/Sejarah/latbel.png";
import bregadaImage from "@assets/Sejarah/bregada.png";
import meriamImage from "@assets/Sejarah/meriam.png";
import latbel2Image from "@assets/Sejarah/latbel2.png";
import gangsaImage from "@assets/Sejarah/gangsa.png";
import sriImage from "@assets/Sejarah/sri.png";
import jejakImage from "@assets/Sejarah/jejak.png";

const articles = [
    {
        title: "Pusaka - Canthik Kyai Rajamala",
        content:
            "Dalam Babad Sangkala, diceritakan bahwa Sinuhun Pakubuwana IV memerintahkan putranya...",
        image: pusakaImage,
        slug: "pusaka-kyai-rajamala",
    },
    {
        title: "Gerbong pesiar Paku Buwana X",
        content: "Industri kereta api di Surakarta yang mulai berkembang...",
        image: gerbongImage,
        slug: "gerbong-pesiar",
    },
    {
        title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat",
        content: "Mataram merupakan...",
        image: latbelImage,
        slug: "latar-belakang-keraton",
    },
    {
        title: "Bregada - Prajurit Karaton Surakarta Hadiningrat",
        content: "Prajurit keraton...",
        image: bregadaImage,
        slug: "bregada-keraton",
    },
    {
        title: "Pusaka - Meriam Nyai Setomi",
        content: "Meriam yang konon...",
        image: meriamImage,
        slug: "meriam-nyai-setomi",
    },
    {
        title: "Latar Belakang Berdirinya Keraton Surakarta Hadiningrat",
        content: "Dominasi kongsi dagang...",
        image: latbel2Image,
        slug: "latar-belakang-keraton-2",
    },
    {
        title: "Gangsa Pakurmatan Sekaten",
        content: "Dominasi kongsi dagang...",
        image: gangsaImage,
        slug: "gangsa-sekaten",
    },
    {
        title: "Jejak Keberanian Pakubuwana IV - Pakepung",
        content: "Dominasi kongsi dagang...",
        image: jejakImage,
        slug: "pakubuwana-iv-pakepung",
    },
    {
        title: "Sri Susuhunan Pakubuwana XIII",
        content: "Dominasi kongsi dagang...",
        image: sriImage,
        slug: "pakubuwana-xiii",
    },
];

const article = computed(() =>
    articles.find((item) => item.slug === route.params.slug)
);
</script>
