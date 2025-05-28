<template>
    <!-- Loading Spinner -->
    <section v-if="isLoading"
        class="flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] bg-black">
        <div class="w-10 h-10 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
    </section>

    <!-- Slideshow -->
    <section v-else
        class="relative flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] overflow-hidden">
        <!-- Slideshow Container -->
        <div class="relative w-full h-full">
            <transition-group name="slide" tag="div" class="w-full h-full">
                <img
                    v-for="(image, index) in companyProfile?.bg_home_urls"
                    v-show="currentSlideIndex === index"
                    :key="index"
                    :src="image"
                    class="absolute inset-0 w-full h-full object-cover object-center"
                    alt="Keraton Solo background image" />
            </transition-group>
        </div>

        <!-- Overlay Hitam -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Teks Tengah -->
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <h1 class="text-center text-white uppercase font-bellefair
                text-6xl max-md:text-4xl max-sm:text-2xl px-5 leading-tight"
                v-html="companyProfile?.cms_name">
            </h1>
        </div>
    </section>
</template>


<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import defaultPhoto from '@/Assets/default-photo.jpg';

const companyProfile = ref(null);
const currentSlideIndex = ref(0);
const isLoading = ref(true);
let slideInterval = null;

const startSlideshow = () => {
    slideInterval = setInterval(() => {
        if (companyProfile.value?.bg_home_urls?.length > 0) {
            currentSlideIndex.value = (currentSlideIndex.value + 1) % companyProfile.value.bg_home_urls.length;
        }
    }, 7000); // Ganti gambar setiap 5 detik
};

const stopSlideshow = () => {
    if (slideInterval) clearInterval(slideInterval);
};

const fetchCompanyProfile = async () => {
    try {
        const response = await axios.get('/api/company-profile/home');
        const data = response.data.data;

        // Create array of background URLs, use default image if any is missing
        data.bg_home_urls = [
            data.bg_home_1 ? `/storage/${data.bg_home_1}` : defaultPhoto,
            data.bg_home_2 ? `/storage/${data.bg_home_2}` : defaultPhoto,
            data.bg_home_3 ? `/storage/${data.bg_home_3}` : defaultPhoto,
        ];

        companyProfile.value = data;
        startSlideshow();
    } catch (error) {
        console.error('Error fetching company profile:', error);
        // If there's an error, use default image
        companyProfile.value = {
            cms_name: 'Welcome',
            bg_home_urls: [defaultPhoto]
        };
        startSlideshow();
    } finally {
        isLoading.value = false;
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
