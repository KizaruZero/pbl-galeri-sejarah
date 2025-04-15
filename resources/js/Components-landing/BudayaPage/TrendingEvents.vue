<template>
    <div class="relative flex flex-col bg-black bg-opacity-70">
        <h1 class="text-white text-5xl font-serif mt-10 ml-20 relative">
            <span class="block w-20 h-0.5 bg-white mb-2"></span>
            Trending Events
            <span class="block w-70 h-0.5 bg-white mt-2"></span>
        </h1>
    </div>
    <div class="relative flex flex-col items-center w-full h-screen bg-black bg-opacity-70">
        <div class="relative flex flex-col items-center w-full max-w-[1300px] h-[650px] bg-neutral-900 mt-5">
            <div class="relative flex justify-center items-center w-full max-w-[900px] h-[650px] mt-5">
                <img :src="images[getPreviousIndex()]" :alt="altTexts[getPreviousIndex()]"
                    class="absolute left-10 w-[250px] h-[400px] object-cover rounded-2xl blur-md opacity-70 transition-all duration-500" />
                <img :src="images[currentIndex]" :alt="altTexts[currentIndex]"
                    class="w-[300px] h-[500px] object-cover rounded-2xl border border-white z-10 shadow-lg transition-all duration-500" />
                <img :src="images[getNextIndex()]" :alt="altTexts[getNextIndex()]"
                    class="absolute right-10 w-[250px] h-[400px] object-cover rounded-2xl blur-md opacity-70 transition-all duration-500" />
            </div>

            <nav class="absolute flex justify-between w-full px-20 top-1/2 transform -translate-y-1/2">
                <button @click="previousSlide" class="nav-button">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <button @click="nextSlide" class="nav-button">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18l6-6-6-6" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </nav>

            <div class="absolute bottom-5 flex gap-2">
                <button v-for="(image, index) in images" :key="index" @click="selectSlide(index)"
                    :class="['w-8 h-1 rounded-full transition-all', currentIndex === index ? 'bg-white' : 'bg-gray-500']"></button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {
        ref
    } from "vue";

    const images = [
        "https://cdn.builder.io/api/v1/image/assets/TEMP/738964cd2ad5923c90b95b0dcaf78c595b7c6d9b",
        "https://cdn.builder.io/api/v1/image/assets/TEMP/da8098ae4c7548dc33c7e2c97bf8b544e8c1111e",
        "https://cdn.builder.io/api/v1/image/assets/TEMP/658922214adecaf978dbc99dca3c98ca7abd6dba",
    ];
    const altTexts = [
        "Traditional batik artisan",
        "Batik artisan at work",
        "Traditional batik patterns",
    ];

    const currentIndex = ref(1);

    const getPreviousIndex = () => (currentIndex.value - 1 + images.length) % images.length;
    const getNextIndex = () => (currentIndex.value + 1) % images.length;
    const previousSlide = () => {
        currentIndex.value = getPreviousIndex();
    };
    const nextSlide = () => {
        currentIndex.value = getNextIndex();
    };
    const selectSlide = (index) => {
        currentIndex.value = index;
    };
</script>

<style scoped>
    .nav-button {
        background: transparent;
        border: 2px solid white;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s;
    }

    .nav-button:hover {
        background: purple;
    }
</style>