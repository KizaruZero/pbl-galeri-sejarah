<template>
    <!-- Spinner Loading -->
    <section v-if="isLoading"
        class="flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] bg-white dark:bg-black">
        <div class="w-10 h-10 border-4 border-black dark:border-white border-t-transparent rounded-full animate-spin"></div>
    </section>

    <!-- Slideshow -->
    <section v-else
        class="relative flex items-center justify-center w-full h-screen max-lg:aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] overflow-hidden">

        <!-- Slideshow Container -->
        <div class="relative w-full h-full">
            <transition-group name="slide" tag="div" class="w-full h-full">
                <img
                    v-for="(image, index) in memberImages"
                    v-show="currentSlideIndex === index"
                    :key="index"
                    :src="image"
                    class="absolute inset-0 w-full h-full object-cover object-center"
                    alt="Member background image" />
            </transition-group>
        </div>

        <!-- Overlay Hitam -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Teks Tengah -->
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <h1 class="text-center text-white uppercase font-bellefair
                text-6xl max-md:text-4xl max-sm:text-2xl px-5 leading-tight"
                v-html="companyProfile?.member_text">
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
const memberImages = ref([]);
const isLoading = ref(true); // ✅ Tambahan: loading state
let slideInterval = null;

const startSlideshow = () => {
    slideInterval = setInterval(() => {
        if (memberImages.value.length > 0) {
            currentSlideIndex.value = (currentSlideIndex.value + 1) % memberImages.value.length;
        }
    }, 7000); // Ganti gambar setiap 7 detik
};

const stopSlideshow = () => {
    if (slideInterval) clearInterval(slideInterval);
};

const fetchCompanyProfile = async () => {
    try {
        const response = await axios.get('/api/company-profile/member');
        const data = response.data.data;
        console.log('Company profile response:', data);

        // Use default photo for missing images
        memberImages.value = [
            data.bg_member_1 ? `/storage/${data.bg_member_1}` : defaultPhoto,
            data.bg_member_2 ? `/storage/${data.bg_member_2}` : defaultPhoto,
            data.bg_member_3 ? `/storage/${data.bg_member_3}` : defaultPhoto,
        ];

        companyProfile.value = data;
        startSlideshow();
    } catch (error) {
        console.error('Error fetching company profile:', error);
        // Set default values on error
        memberImages.value = [defaultPhoto];
        companyProfile.value = { member_text: 'Members' };
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

/* Spinner */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
