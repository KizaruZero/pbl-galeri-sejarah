<template>
    <main class="relative p-5 min-h-screen bg-black">
        <div class="flex items-center justify-between">
            <h1 class="mt-4 ml-5 text-7xl text-white max-md:text-5xl max-sm:mt-2.5 max-sm:text-4xl">
                <span class="block w-50 h-0.5 bg-white mt-2"></span>
                Upcoming Events
                <span class="block w-full h-0.5 bg-white mt-2"></span>
            </h1>

            <!-- View All Button -->
            <button
                class="absolute top-5 right-5 flex items-center justify-center w-[94px] h-[94px] rounded-full border border-white text-white text-2xl transition-all duration-300 hover:scale-105 hover:bg-white hover:text-black">
                <span class="relative z-10">View All</span>
            </button>
        </div>

        <!-- EventSlider Component -->
        <div class="card mt-18 pb-20">
            <Carousel :value="slides" :numVisible="3" :numScroll="1" :responsiveOptions="responsiveOptions" circular :autoplayInterval="3000">
                <template #item="slotProps">
                    <div class="relative mx-16 max-sm:mx-5">
                        <div class="relative w-full h-[636px] max-sm:h-[300px] overflow-hidden">
                            <img :src="slotProps.data.image" :alt="slotProps.data.title"
                                class="object-cover w-full h-full" />
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-[var(--color-black)]/60"></div>
                        </div>
                        <div class="absolute bottom-0 left-0 p-10 text-white max-sm:p-5">
                            <div class="text-7xl text-neutral-200 max-sm:text-4xl">
                                {{ String(slides.indexOf(slotProps.data) + 1).padStart(2, "0") }}
                            </div>
                            <div class="mt-0 ml-20 max-sm:ml-5">
                                <h2 class="mb-2.5 text-4xl uppercase max-sm:text-xl">
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
import { ref } from "vue";
import KirabImage from "../../assets/img/landing/kirab.png";
import Wilujengan from "../../assets/img/landing/wilujengan.png";
import Grebeg from "../../assets/img/landing/grebeg.png";

const slides = ref([
    {
        image: KirabImage,
        title: "Kirab Malem 1 Suro",
        startTime: "Start on : 08:00 GTS . Monday",
        description: "Kirab malem 1 Suro adalah tradisi turun temurun dari Karaton Surakarta Hadiningrat yang dilakukan untuk memperingati datangnya Tahun Baru Jawa.",
    },
    {
        image: Wilujengan,
        title: "Cultural Festival",
        startTime: "Start on : 10:00 GTS . Wednesday",
        description: "Join us for a celebration of diverse cultures featuring traditional performances, authentic cuisine, and interactive workshops showcasing heritage from around the world.",
    },
    {
        image: Grebeg,
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
    { breakpoint: '1400px', numVisible: 1, numScroll: 1 },
    { breakpoint: '1199px', numVisible: 1, numScroll: 1 },
    { breakpoint: '767px', numVisible: 1, numScroll: 1 },
    { breakpoint: '575px', numVisible: 1, numScroll: 1 }
]);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap");
</style>