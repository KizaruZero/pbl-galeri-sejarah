<template>
    <div class="relative flex flex-col bg-black bg-opacity-70 px-5">
        <!-- Judul -->
        <h1 class="text-white text-4xl md:text-5xl font-serif mt-10 text-center relative">
            <span class="block w-20 h-0.5 bg-white mb-2 mx-auto"></span>
            Trending Events
            <span class="block w-20 h-0.5 bg-white mt-2 mx-auto"></span>
        </h1>
    </div>

    <!-- Container utama -->
    <div class="relative flex flex-col items-center w-full bg-black bg-opacity-70 py-10">
        <div class="relative flex flex-col items-center w-full max-w-[1300px] bg-neutral-900 rounded-lg shadow-lg px-5 py-10">
            <!-- Gambar Slider -->
            <div class="relative flex items-center justify-center w-full max-w-[900px] gap-5">
                <!-- Gambar Kiri -->
                <img 
                    :src="images[getPreviousIndex()]" 
                    :alt="altTexts[getPreviousIndex()]"
                    class="hidden md:block absolute left-0 w-[200px] h-[350px] object-cover rounded-2xl blur-md opacity-70 transition-all duration-500" 
                />

                <!-- Gambar Tengah -->
                <img 
                    :src="images[currentIndex]" 
                    :alt="altTexts[currentIndex]"
                    class="w-[250px] md:w-[300px] h-[400px] md:h-[500px] object-cover rounded-2xl border border-white shadow-lg transition-all duration-500" 
                />

                <!-- Gambar Kanan -->
                <img 
                    :src="images[getNextIndex()]" 
                    :alt="altTexts[getNextIndex()]"
                    class="hidden md:block absolute right-0 w-[200px] h-[350px] object-cover rounded-2xl blur-md opacity-70 transition-all duration-500" 
                />
            </div>

            <!-- Tombol Navigasi -->
            <nav class="absolute flex justify-between w-full px-5 md:px-20 top-1/2 transform -translate-y-1/2">
                <button @click="previousSlide" class="nav-button">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button @click="nextSlide" class="nav-button">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18l6-6-6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </nav>

            <!-- Indicator Dots -->
            <div class="flex gap-2 mt-5">
                <button 
                    v-for="(image, index) in images" 
                    :key="index" 
                    @click="selectSlide(index)"
                    :class="['w-6 h-1 rounded-full transition-all', currentIndex === index ? 'bg-white' : 'bg-gray-500']"
                ></button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

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
const previousSlide = () => { currentIndex.value = getPreviousIndex(); };
const nextSlide = () => { currentIndex.value = getNextIndex(); };
const selectSlide = (index) => { currentIndex.value = index; };
</script>

<style scoped>
.nav-button {
    background: transparent;
    border: 2px solid white;
    padding: 8px;
    border-radius: 50%;
    cursor: pointer;
    transition: background 0.3s;
}

.nav-button:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-button {
        padding: 5px;
        width: 35px;
        height: 35px;
    }

    .nav-button svg {
        width: 24px;
        height: 24px;
    }
}
</style>
