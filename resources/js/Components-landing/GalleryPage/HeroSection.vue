<template>
    <section class="relative flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] overflow-hidden">
        <!-- Slideshow Container -->
        <div class="relative w-full h-full">
            <!-- Slideshow Image with slide transition -->
            <transition-group name="slide" tag="div" class="w-full h-full">
                <img
                    v-for="(image, index) in galleryImages"
                    v-show="currentSlideIndex === index"
                    :key="index"
                    :src="image"
                    class="absolute inset-0 w-full h-full object-cover object-center"
                    alt="Gallery background image" />
            </transition-group>
        </div>

        <!-- Overlay Hitam -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Teks Tengah -->
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <h1 class="text-center text-white uppercase font-bellefair
                text-6xl max-md:text-4xl max-sm:text-2xl px-5 leading-tight"
                v-html="companyProfile?.gallery_text">
            </h1>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const companyProfile = ref(null);
const currentSlideIndex = ref(0);
const galleryImages = ref([]);
let slideInterval = null;

const startSlideshow = () => {
    slideInterval = setInterval(() => {
        if (galleryImages.value.length > 0) {
            currentSlideIndex.value = (currentSlideIndex.value + 1) % galleryImages.value.length;
        }
    }, 5000); // Ganti gambar setiap 5 detik
};

const stopSlideshow = () => {
    if (slideInterval) clearInterval(slideInterval);
};

const fetchCompanyProfile = async () => {
    try {
        const response = await axios.get('/api/company-profile');
        const data = response.data.data;
        console.log('Company profile response:', data);

        // Buat array gambar gallery
        galleryImages.value = [
            `/storage/${data.bg_gallery_1}`,
            `/storage/${data.bg_gallery_2}`,
            `/storage/${data.bg_gallery_3}`,
        ];

        companyProfile.value = data;
        startSlideshow();
    } catch (error) {
        console.error('Error fetching company profile:', error);
    }
};

onMounted(() => {
    fetchCompanyProfile();
});

onBeforeUnmount(() => {
    stopSlideshow();
});

const page = usePage();
const user = computed(() => page.props.auth?.user || null);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}

/* Slide transition */
.slide-enter-active,
.slide-leave-active {
    transition: transform 1s ease-in-out;
}
.slide-enter-from {
    transform: translateX(100%);
}
.slide-enter-to {
    transform: translateX(0);
}
.slide-leave-from {
    transform: translateX(0);
}
.slide-leave-to {
    transform: translateX(-100%);
}
</style>
