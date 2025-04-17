<template>
    <main class="relative p-5 bg-black">
        <!-- Title + View All -->
        <div class="flex items-center justify-between flex-wrap gap-4">
        <!-- Judul -->
        <h1 class="ml-20 text-white text-4xl md:text-5xl font-serif mt-10 text-center relative">
            <span class="block w-24 h-0.5 bg-white mb-8"></span>
            Upcoming Events
            <span class="block w-full h-0.5 bg-white mt-8"></span>
        </h1>

        <!-- View All Button -->
        <button
            class="mr-20 flex items-center justify-center w-[70px] h-[70px] rounded-full border border-white text-white text-lg transition-all duration-300 hover:scale-105 hover:bg-white hover:text-black max-sm:w-[50px] max-sm:h-[50px] max-sm:text-sm max-sm:mr-10"
        >
            <span class="relative z-10">View</span>
        </button>
    </div>


        <!-- Carousel Cards -->
        <div class="card mt-20 pb-20">
            <Carousel
                :value="slides"
                :numVisible="3"
                :numScroll="1"
                :responsiveOptions="responsiveOptions"
                circular
                :autoplayInterval="3000"
            >
                <template #item="slotProps">
                    <div
                        class="relative mx-16 max-sm:mx-5 rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-[1.01] max-sm:rounded-md"
                    >
                        <!-- Image Container -->
                        <div class="relative w-full h-[636px] max-sm:h-[350px] bg-black">
                            <img
                                :src="slotProps.data.image"
                                :alt="slotProps.data.title"
                                class="object-cover w-full h-full transition-opacity duration-300"
                            />
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                        </div>

                        <!-- Text Content -->
                        <div class="absolute bottom-0 left-0 p-10 text-white max-sm:p-5">
                            <!-- Nomor -->
                            <div class="text-5xl text-neutral-200 max-sm:text-4xl">
                                {{ String(slides.indexOf(slotProps.data) + 1).padStart(2, "0") }}
                            </div>

                            <!-- Detail -->
                            <div class="mt-0 ml-10 max-sm:ml-5">
                                <h2 class="mb-8 text-4xl uppercase max-sm:text-xl">
                                    {{ slotProps.data.title }}
                                </h2>
                                <time class="mb-5 text-xs uppercase">
                                    {{ slotProps.data.startTime }}
                                </time>
                                <p
                                    class="text-base font-light tracking-wider leading-normal uppercase max-w-[629px] max-md:max-w-full max-sm:text-sm text-justify"
                                >
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
import KirabImage from "@assets/landing/kirab.png";
import Wilujengan from "@assets/landing/wilujengan.png";
import Grebeg from "@assets/landing/grebeg.png";

const slides = ref([
    {
        image: KirabImage,
        title: "Kirab Malem 1 Suro",
        startTime: "Start on : 08:00 GTS . Monday",
        description:
            "Kirab malem 1 Suro adalah tradisi turun temurun dari Karaton Surakarta Hadiningrat yang dilakukan untuk memperingati datangnya Tahun Baru Jawa.",
    },
    {
        image: Wilujengan,
        title: "Cultural Festival",
        startTime: "Start on : 10:00 GTS . Wednesday",
        description:
            "Join us for a celebration of diverse cultures featuring traditional performances, authentic cuisine, and interactive workshops showcasing heritage from around the world.",
    },
    {
        image: Grebeg,
        title: "Cultural Festival",
        startTime: "Start on : 10:00 GTS . Wednesday",
        description:
            "Join us for a celebration of diverse cultures featuring traditional performances, authentic cuisine, and interactive workshops showcasing heritage from around the world.",
    },
    {
        image: "https://picsum.photos/800/600?random=2",
        title: "Music Concert",
        startTime: "Start on : 19:30 GTS . Friday",
        description:
            "Experience an unforgettable night of live music featuring acclaimed artists performing a mix of contemporary hits and classical favorites in our state-of-the-art venue.",
    },
]);

const responsiveOptions = ref([
    { breakpoint: "1400px", numVisible: 1, numScroll: 1 },
    { breakpoint: "1199px", numVisible: 1, numScroll: 1 },
    { breakpoint: "767px", numVisible: 1, numScroll: 1 },
    { breakpoint: "575px", numVisible: 1, numScroll: 1 },
]);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap");
</style>
