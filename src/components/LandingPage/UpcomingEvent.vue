<template>
    <main class="relative p-5 min-h-screen bg-black">
        <h1 class="mt-4 ml-5 text-7xl text-white max-md:text-5xl max-sm:mt-2.5 max-sm:text-4xl">
            Upcoming Events
        </h1>

        <!-- ViewAllButton Component -->
        <div class="absolute top-0 right-5 max-sm:hidden">
            <div>
                <svg width="94" height="94" viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="circle-svg" style="width: 93px; height: 93px">
                    <circle cx="47.3264" cy="46.6227" r="46.2385" stroke="white" stroke-width="0.768491"></circle>
                </svg>
            </div>
            <div class="absolute right-5 text-2xl text-white top-[34px]">view all</div>
        </div>

        <!-- DecorativeLines -->
        <div>
            <svg width="198" height="2" viewBox="0 0 731 2" fill="none" xmlns="http://www.w3.org/2000/svg"
                style="width: 100%; height: 1px; margin: 20px 0; stroke: #727272;">
                <path d="M0.0419922 0.688477H731.008" stroke="#727272"></path>
            </svg>
        </div>

        <div>
            <svg width="731" height="2" viewBox="0 0 731 2" fill="none" xmlns="http://www.w3.org/2000/svg"
                style="width: 100%; height: 1px; margin: 20px 0; stroke: #B9B9B9;">
                <path d="M0.0419922 0.688477H731.008" stroke="#B9B9B9"></path>
            </svg>
        </div>

        <!-- EventSlider Component -->
        <div class="card mt-10">
            <Carousel :value="slides" :numVisible="1" :numScroll="1" :responsiveOptions="responsiveOptions">
                <template #item="slotProps">
                    <div class="relative">
                        <img :src="slotProps.data.image" :alt="slotProps.data.title"
                            class="object-cover w-full h-[636px]" />
                        <div class="absolute bottom-0 left-0 p-10 text-white">
                            <div class="text-7xl text-neutral-200">
                                {{ String(slides.indexOf(slotProps.data) + 1).padStart(2, "0") }}
                            </div>
                            <div class="mt-0 ml-20 max-sm:ml-5">
                                <h2 class="mb-2.5 text-3xl uppercase max-sm:text-2xl">
                                    {{ slotProps.data.title }}
                                </h2>
                                <time class="mb-4 text-xs uppercase">
                                    {{ slotProps.data.startTime }}
                                </time>
                                <p
                                    class="text-base font-light tracking-wider leading-normal uppercase max-w-[629px] max-md:max-w-full max-sm:text-sm">
                                    {{ slotProps.data.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
            </Carousel>
        </div>
    </main>
</template>

<script setup>
import { ref, onMounted } from "vue";

const slides = ref([
    {
        image: "https://cdn.builder.io/api/v1/image/assets/TEMP/5e29d2fb9f29d8777a7e30f43319a0a5da0287fd",
        title: "Kirab Malem 1 Suro",
        startTime: "Start on : 08:00 GTS . Monday",
        description: "Kirab malem 1 Suro adalah tradisi turun temurun dari Karaton Surakarta Hadiningrat yang dilakukan untuk memperingati datangnya Tahun Baru Jawa. Sura atau Suro merupakan nama bulan pertama dalam penanggalan Jawa.",
    },
    {
        image: "https://picsum.photos/800/600?random=1",
        title: "Cultural Festival",
        startTime: "Start on : 10:00 GTS . Wednesday",
        description: "Join us for a celebration of diverse cultures featuring traditional performances, authentic cuisine, and interactive workshops showcasing heritage from around the world.",
    },
    {
        image: "https://picsum.photos/800/600?random=2",
        title: "Music Concert",
        startTime: "Start on : 19:30 GTS . Friday",
        description: "Experience an unforgettable night of live music featuring acclaimed artists performing a mix of contemporary hits and classical favorites in our state-of-the-art venue.",
    }
]);

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
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap");

:root {
    font-family: "Poppins", sans-serif;
}

h1,
h2 {
    font-family: "Bellefair", serif;
}

/* Custom styling for PrimeVue carousel */
:deep(.p-carousel) {
    background: transparent;
}

:deep(.p-carousel-indicators) {
    position: absolute;
    bottom: 20px;
    left: 20px;
}

:deep(.p-carousel-indicator > button) {
    width: 23px !important;
    height: 23px !important;
    border-radius: 50% !important;
    background-color: #7e7e7e !important;
}

:deep(.p-carousel-indicator.p-highlight > button) {
    background-color: #d4d4d4 !important;
}

:deep(.p-carousel-prev),
:deep(.p-carousel-next) {
    width: 70px !important;
    height: 70px !important;
    border-radius: 50% !important;
    background: rgba(255, 255, 255, 0.8) !important; /* Increased opacity */
    color: black !important; /* Changed color for better contrast */
    border: none !important;
    transition: background 0.3s ease; /* Smooth transition */
}

:deep(.p-carousel-prev:hover),
:deep(.p-carousel-next:hover) {
    background: rgba(255, 255, 255, 1) !important; /* Full opacity on hover */
}

:deep(.p-carousel-prev) {
    left: 20px !important; /* Adjusted position */
}

:deep(.p-carousel-next) {
    right: 20px !important; /* Adjusted position */
}

:deep(.p-carousel-container) {
    position: relative;
}
</style>