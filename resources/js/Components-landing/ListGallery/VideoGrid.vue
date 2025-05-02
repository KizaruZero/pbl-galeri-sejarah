<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
                    Video Gallery
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Grid Card -->
            <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <VideoCard 
                    v-for="video in videos" 
                    :key="video.id"
                    :video_url="video.video_url" 
                    :thumbnailUrl="video.thumbnailUrl"
                    :title="video.title"
                    :description="video.description"
                    :duration="video.duration"
                    :views="video.views"
                    @click="openModal(video)"
                />
            </div>

            <!-- Popup Modal -->
            <div v-if="selectedVideo"
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg max-w-4xl w-full p-6 relative">
                    <button @click="closeModal" class="absolute -top-10 right-0 text-white text-2xl p-2 hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        <video 
                            controls
                            :poster="selectedVideo.thumbnailUrl"
                            class="w-full h-full object-cover rounded-t-lg">
                            <source :src="selectedVideo.video_url" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </button>

                        <button @click="getDetailPage(selectedVideo.slug)"
                            class="absolute right-6 text-black hover:underline focus:outline-none transition-transform duration-200 active:scale-105 flex items-center">
                            view more <span class="ml-1">&gt;</span>
                        </button>
                    </div>
                    <h2 class="text-xl font-bold mb-2">{{ selectedVideo.title }}</h2>

                    <p class="text-gray-600 mb-4">
                        {{ getFirstThreeWords(selectedVideo.description) }}...
                    </p>

                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import VideoCard from './VideoCard.vue';
import axios from 'axios';

const videos = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedVideo = ref(null);
const isLiked = ref(false);
const isSaved = ref(false);
const likeCount = ref(Math.floor(Math.random() * 100) + 5);
const slug = window.location.pathname.split('/').pop(); // ambil slug dari URL
const slug1 = window.location.pathname.split('/').pop(); // ambil slug dari URL

const openModal = (video) => {
    selectedVideo.value = video;
};

const closeModal = () => {
    selectedVideo.value = null;
};

const getFirstThreeWords = (description) => {
    if (!description) return '';
    const words = description.split(' ');
    return words.slice(0, 10).join(' ');
};

const getDetailPage = (slug) => {
        window.location.href = `/gallery/${slug1}/${slug}`;
    };

onMounted(async () => {
    const options = {
        method: 'GET',
        url: `http://127.0.0.1:8000/api/category-video/${slug}`,
        headers: {
            Accept: 'application/json',
            Authorization: 'Bearer 123'
        }
    };
    
    try {
        const response = await axios.request(options);
        console.log('API Response:', response.data); // Debug response
        
        // Handle single video object response
        const videoData = response.data.content_video || response.data;
        
        // Create array even if single video
        const videoArray = videoData ? [videoData] : [];
        
        console.log('Video array:', videoArray);

        videos.value = videoArray.map(video => ({
            id: video.id,
            title: video.title || 'Untitled Video',
            description: video.description || 'No description available',
            slug: video.slug,
            video_url: video.link_youtube || '',
            thumbnailUrl: video.thumbnail 
                ? `/storage/${video.thumbnail.replace(/^public\//, '')}`
                : '/default-thumbnail.jpg',
            duration: video.metadata_video?.duration || '00:00',
            views: video.total_views || 0,
            tags: video.tag ? video.tag.split(/,\s*/) : [],
            category: response.data.category || {},
            metadata: video.metadata_video || {}
        }));

        console.log('Processed videos:', videos.value);
        
    } catch (err) {
        console.error('Failed to fetch videos:', err);
        if (err.response) {
            console.error('Error response:', err.response.data);
            error.value = `Error: ${err.response.data.message || 'Failed to fetch videos'}`;
        } else {
            error.value = 'Network error or server unavailable';
        }
    } finally {
        loading.value = false;
    }
});

const toggleLike = () => {
    if (isLiked.value) {
        likeCount.value--;
    } else {
        likeCount.value++;
    }
    isLiked.value = !isLiked.value;
};

const toggleSave = () => {
    isSaved.value = !isSaved.value;
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.aspect-w-16 {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-w-16 > * {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>