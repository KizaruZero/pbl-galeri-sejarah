<template>
    <main class="overflow-hidden relative w-full min-h-screen bg-black">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap"
            rel="stylesheet" />
        <article class="relative px-5 py-20 mx-auto my-0 max-w-screen-xl">
            <Carousel :value="royalItems" :numVisible="1" :numScroll="1" :responsiveOptions="responsiveOptions" circular
                :autoplayInterval="5000">
                <template #item="slotProps">
                    <div class="relative carousel-item">
                        <!-- Hero Image -->
                        <img :src="slotProps.data.image" :alt="slotProps.data.title"
                            class="object-cover absolute top-0 right-0 h-[858px] w-[735px] max-md:relative max-md:mt-8 max-md:w-full max-md:h-auto" />

                        <!-- Hero Content -->
                        <section class="relative">
                            <h1
                                class="mb-10 text-8xl text-white uppercase max-w-[764px] max-md:text-7xl max-sm:text-5xl">
                                {{ slotProps.data.title }}
                            </h1>
                            <p
                                class="mb-8 text-3xl leading-10 text-white max-w-[442px] max-md:max-w-full max-md:text-2xl max-sm:text-lg">
                                {{ slotProps.data.description }}
                            </p>
                            <p
                                class="text-2xl leading-10 text-white max-w-[811px] ml-[466px] max-md:max-w-full max-md:text-xl max-sm:text-base max-md:ml-0 max-md:mt-4">
                                {{ slotProps.data.detailedDescription }}
                            </p>
                        </section>

                        <!-- Circle Overlay -->
                        <div
                            class="absolute rounded-full border-white border-solid blur-[15px] border-[10px] h-[853px] left-[457px] top-[213px] w-[853px] z-[1] max-md:top-2/4 max-md:left-2/4 max-md:-translate-x-2/4 max-md:-translate-y-2/4 max-md:h-[600px] max-md:w-[600px] max-sm:h-[300px] max-sm:w-[300px]">
                        </div>

                        <!-- Status Tag if needed -->
                        <Tag v-if="slotProps.data.status" :value="slotProps.data.status"
                            :severity="getSeverity(slotProps.data.status)" class="absolute"
                            style="left:20px; top: 20px" />
                    </div>
                </template>

                <template #header>
                    <div class="flex justify-between items-center px-4 py-2">
                        <div>
                            <Button icon="pi pi-chevron-left" @click="prevSlide" class="mr-2" severity="secondary"
                                outlined />
                            <Button icon="pi pi-chevron-right" @click="nextSlide" severity="secondary" outlined />
                        </div>
                    </div>
                </template>
            </Carousel>
        </article>
    </main>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Carousel from 'primevue/carousel';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

// Carousel data
const royalItems = ref([
    {
        id: 1,
        title: "PAKUBUWANA XIII",
        image: "https://cdn.builder.io/api/v1/image/assets/TEMP/569654c5c9a6a46ea9e26d4337f2a66408b9e6ca",
        description: "Pakubuwana XIII (nama lengkap: Sampeyandalem Ingkang Sinuhun Kanjeng Susuhunan Pakubuwana XIII) adalah raja Kasunanan Surakarta yang bertahta sejak 2004. Ia lahir dengan nama Hangabehi dan merupakan putra dari Pakubuwana XII.",
        detailedDescription: "Masa pemerintahannya diwarnai dengan berbagai dinamika internal keraton, termasuk dualisme kepemimpinan sebelum akhirnya dikukuhkan sebagai satu-satunya raja yang sah. Sebagai penguasa, ia berupaya melestarikan budaya dan tradisi Jawa di tengah tantangan modernisasi."
    },
    {
        id: 2,
        title: "PAKUBUWANA XII",
        image: "https://cdn.builder.io/api/v1/image/assets/TEMP/569654c5c9a6a46ea9e26d4337f2a66408b9e6ca", // Replace with actual image
        description: "Pakubuwana XII memerintah Kasunanan Surakarta dalam periode yang penting dalam sejarah Indonesia. Beliau dinobatkan sebagai Susuhunan pada tahun 1944 dan menjadi saksi transisi Indonesia dari masa kolonial ke kemerdekaan.",
        detailedDescription: "Selama masa pemerintahannya, Pakubuwana XII memainkan peran penting dalam pelestarian budaya Jawa serta hubungan antara Keraton Surakarta dengan Republik Indonesia. Beliau dikenal sebagai sosok yang bijaksana dalam menghadapi perubahan politik Indonesia."
    },
    {
        id: 3,
        title: "PAKUBUWANA XI",
        image: "https://cdn.builder.io/api/v1/image/assets/TEMP/569654c5c9a6a46ea9e26d4337f2a66408b9e6ca", // Replace with actual image
        description: "Pakubuwana XI memerintah Kasunanan Surakarta pada masa kolonial Belanda. Beliau adalah raja yang memperjuangkan eksistensi keraton di tengah kebijakan pemerintah kolonial yang semakin ketat.",
        detailedDescription: "Di masa pemerintahannya, banyak tradisi dan upacara keraton yang terus dijaga dan diperkuat. Meski menghadapi berbagai tekanan politik, Pakubuwana XI berhasil mempertahankan kedaulatan budaya Keraton Surakarta."
    }
]);

// Carousel responsive options
const responsiveOptions = ref([
    {
        breakpoint: '1400px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '1199px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '767px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '575px',
        numVisible: 1,
        numScroll: 1
    }
]);

// Carousel reference for manual navigation
const carouselRef = ref(null);

// Navigation methods
const nextSlide = () => {
    if (carouselRef.value) {
        carouselRef.value.next();
    }
};

const prevSlide = () => {
    if (carouselRef.value) {
        carouselRef.value.prev();
    }
};

// Tag severity function
const getSeverity = (status) => {
    switch (status) {
        case 'CURRENT':
            return 'success';
        case 'HISTORICAL':
            return 'info';
        case 'LEGENDARY':
            return 'warning';
        default:
            return null;
    }
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap");

:root {
    font-family: "Poppins", sans-serif;
}

h1 {
    font-family: "Bellefair", serif;
}

/* Additional carousel styling */
.p-carousel .p-carousel-indicators {
    bottom: 20px;
}

.p-carousel .p-carousel-indicators .p-carousel-indicator button {
    background-color: rgba(255, 255, 255, 0.3);
}

.p-carousel .p-carousel-indicators .p-carousel-indicator.p-highlight button {
    background-color: white;
}

.carousel-item {
    min-height: 600px;
}
</style>