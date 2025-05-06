<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
                    Highlight Events
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Grid Card -->
            <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <EventCard v-for="photo in photos" :key="photo.slug" :imageUrl="photo.imageUrl" :title="photo.title"
                    :description="photo.description || 'No description available'" :titleSize="'lg'"
                    @click="openModal(photo)" />
            </div>

            <!-- Popup Modal -->
            <div v-if="selectedPhoto"
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
                    <button @click="closeModal" class="absolute -top-10 right-0 text-white text-2xl p-2 hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <img :src="selectedPhoto.imageUrl" :alt="selectedPhoto.altText"
                        class="w-full h-48 object-cover rounded-t-lg mb-4">
                    <div class="flex items-center space-x-4 mb-3">
                        <button @click="toggleLike"
                            class="focus:outline-none transition-transform duration-200 active:scale-125"
                            :class="{ 'text-red-500': isLiked }">
                            <svg v-if="isLiked" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>

                        <button @click="toggleSave"
                            class="focus:outline-none transition-transform duration-200 active:scale-125"
                            :class="{ 'text-yellow-500': isSaved }">
                            <svg v-if="isSaved" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke- width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </button>

                        <button @click="getDetailPage(selectedPhoto.slug)"
                            class="absolute right-6 text-black hover:underline focus:outline-none transition-transform duration-200 active:scale-105 flex items-center">
                            view more <span class="ml-1">&gt;</span>
                        </button>
                    </div>
                    <h2 class="text-xl font-bold mb-2">{{ selectedPhoto.title }}</h2>

                    <p class="text-gray-600 mb-4">
                        {{ getFirstThreeWords(selectedPhoto.description) }}...
                    </p>

                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    import {
        ref,
        onMounted
    } from 'vue';
    import EventCard from './EventCard.vue';
    import axios from 'axios';

    const photos = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const selectedPhoto = ref(null);
    const isLiked = ref(false);
    const isSaved = ref(false);
    const likeCount = ref(Math.floor(Math.random() * 100) + 5); // Random initial like count
    const openModal = (photo) => {
        selectedPhoto.value = photo;
    };

    const closeModal = () => {
        selectedPhoto.value = null;
    };

    const getFirstThreeWords = (description) => {
        if (!description) return '';
        const words = description.split(' ');
        return words.slice(0, 10).join(' ');
    };

    const getDetailPage = (slug) => {
        window.location.href = `/events/${slug}`;
    };

    onMounted(async () => {
        const options = {
            method: 'GET',
            url: 'http://127.0.0.1:8000/api/events',
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer 123'
            }
        };

        try {
            const response = await axios.request(options);
            const photoArray = Array.isArray(response.data) ?
                response.data :
                response.data.data || [];

            photos.value = photoArray.map(photo => ({
                imageUrl: photo.image_url ?
                    (photo.image_url.startsWith('http') ?
                        photo.image_url :
                        `/storage/${photo.image_url.replace(/^public\//, '')}`) :
                    '/default-photo.jpg',
                title: photo.title || 'Untitled',
                titleSize: 'base',
                description: photo.description || 'No description available',
                slug: photo.slug,
                altText: photo.alt_text || '',
                tags: photo.tags || []
            }));
        } catch (err) {
            console.error('Gagal mengambil data photo:', err);
            error.value = 'Gagal mengambil data';
        } finally {
            loading.value = false;
        }
    });

    // Toggle like status
    const toggleLike = () => {
        if (isLiked.value) {
            likeCount.value--;
        } else {
            likeCount.value++;
        }
        isLiked.value = !isLiked.value;

        // Here you would typically send an API request to update the like status
        // saveReaction('like', isLiked.value);
    };

    // Toggle save status
    const toggleSave = () => {
        isSaved.value = !isSaved.value;

        // Here you would typically send an API request to update the save status
        // saveReaction('save', isSaved.value);
    };

</script>

<style scoped>
    /* Tambahan animasi untuk modal */
    .modal-enter-active,
    .modal-leave-active {
        transition: opacity 0.3s ease;
    }

    .modal-enter-from,
    .modal-leave-to {
        opacity: 0;
    }

</style>
