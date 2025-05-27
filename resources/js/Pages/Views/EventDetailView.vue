<template>
    <MainLayout>
        <div class="min-h-screen bg-black">
            <!-- Loading state -->
            <div v-if="loading" class="animate-pulse">
                <!-- Image placeholder -->
                <div class="w-full h-[30vh] md:h-[70vh] bg-gray-800"></div>

                <!-- Content placeholder -->
                <div class="p-4 md:p-6 mx-4 md:mx-24">
                    <div class="flex justify-between mb-6">
                        <div class="h-8 w-3/4 bg-gray-700 rounded"></div>
                        <div class="flex space-x-4">
                            <div class="h-8 w-8 bg-gray-700 rounded-full"></div>
                            <div class="h-8 w-8 bg-gray-700 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Date placeholder -->
                    <div class="flex items-center mb-4">
                        <div class="h-4 w-4 bg-gray-700 rounded-full mr-2"></div>
                        <div class="h-4 w-1/2 bg-gray-700 rounded"></div>
                    </div>

                    <!-- Location placeholder -->
                    <div class="flex items-center mb-4">
                        <div class="h-4 w-4 bg-gray-700 rounded-full mr-2"></div>
                        <div class="h-4 w-2/3 bg-gray-700 rounded"></div>
                    </div>

                    <!-- Contact placeholder -->
                    <div class="flex items-center mb-4">
                        <div class="h-4 w-4 bg-gray-700 rounded-full mr-2"></div>
                        <div class="h-4 w-1/3 bg-gray-700 rounded"></div>
                    </div>

                    <!-- Social links placeholder -->
                    <div class="flex space-x-4 mb-6">
                        <div class="h-6 w-20 bg-gray-700 rounded"></div>
                        <div class="h-6 w-20 bg-gray-700 rounded"></div>
                        <div class="h-6 w-20 bg-gray-700 rounded"></div>
                    </div>

                    <!-- Description placeholder -->
                    <div class="space-y-2">
                        <div class="h-4 w-full bg-gray-700 rounded"></div>
                        <div class="h-4 w-5/6 bg-gray-700 rounded"></div>
                        <div class="h-4 w-4/6 bg-gray-700 rounded"></div>
                        <div class="h-4 w-3/4 bg-gray-700 rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Error state -->
            <div v-else-if="error" class="text-center py-12 text-red-500">
                <p class="text-xl mb-4">{{ error }}</p>
                <button @click="fetchEvent" class="px-6 py-2 bg-white text-black rounded-md hover:bg-gray-200 transition">
                    Try Again
                </button>
            </div>

            <!-- Content -->
            <div v-else>
                <div class="max-w-full mx-auto">
                    <div class="relative">
                        <!-- Back Button -->
                        <button @click="goBack"
                            class="absolute top-4 left-4 z-10 p-2 bg-black/50 rounded-full hover:bg-black/75 transition-colors mt-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>

                        <!-- Image container with overlay -->
                        <div class="w-full overflow-hidden">
                            <!-- Empty state for image -->
                            <div v-if="!event.imageUrl || imageError"
                                class="w-full h-[30vh] md:h-[70vh] bg-zinc-900 flex items-center justify-center">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-16 w-16 md:h-24 md:w-24 mx-auto text-gray-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-4 text-gray-400 text-sm md:text-base">No image available</p>
                                </div>
                            </div>

                            <!-- Image with overlay -->
                            <div v-else class="relative">
                                <img :src="event.imageUrl"
                                    :alt="event.title || 'Event image'"
                                    @error="handleImageError"
                                    class="w-full h-[30vh] md:h-[70vh] object-cover bg-gray-950" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-70 mix-blend-multiply"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Event Card -->
                <div class="bg-black rounded-lg shadow-xl overflow-hidden">
                    <!-- Event Details -->
                    <div class="p-6 md:p-10 mx-4 md:mx-24">

                        <!-- Event Title -->
                        <div class="mb-6">
                            <h1 class="text-2xl md:text-3xl font-bold text-white">
                                {{ event.title }}
                            </h1>
                        </div>

                        <!-- Event Dates -->
                        <div class="flex items-center mb-3 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-gray-400 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-300 text-sm md:text-base">
                                {{ formatDate(event.date_start) }}
                                <span v-if="event.date_end"> - {{ formatDate(event.date_end) }}</span>
                            </span>
                        </div>

                        <!-- Event Location -->
                        <div class="flex items-start md:items-center mb-3 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-gray-400 mr-2 mt-1 md:mt-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div class="flex flex-col md:flex-row md:items-center">
                                <span class="text-gray-300 text-sm md:text-base">{{ event.location }}</span>
                                <a v-if="event.googleMapsUrl" :href="event.googleMapsUrl" target="_blank"
                                    class="text-sm md:ml-2 text-blue-400 hover:underline">
                                    (View on Map)
                                </a>
                            </div>
                        </div>

                        <!-- Contact Person -->
                        <div v-if="event.contactPerson" class="flex items-center mb-3 md:mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-gray-400 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-gray-300 text-sm md:text-base">Contact: {{ event.contactPerson }}</span>
                        </div>

                        <!-- Social Links -->
                        <div v-if="event.instagramUrl || event.youtubeUrl || event.websiteUrl"
                            class="flex flex-wrap gap-2 md:gap-4 mb-4 md:mb-6">
                            <a v-if="event.instagramUrl" :href="event.instagramUrl" target="_blank"
                                class="flex items-center text-pink-500 hover:text-pink-400 text-sm md:text-base">
                                <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z" />
                            </svg>
                            <span class="ml-1">Instagram</span>
                        </a>
                        <a v-if="event.youtubeUrl" :href="event.youtubeUrl" target="_blank"
                            class="flex items-center text-red-500 hover:text-red-400 text-sm md:text-base">
                            <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                            </svg>
                            <span class="ml-1">YouTube</span>
                        </a>
                        <a v-if="event.websiteUrl" :href="event.websiteUrl" target="_blank"
                            class="flex items-center text-blue-400 hover:text-blue-300 text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            <span class="ml-1">Website</span>
                        </a>
                    </div>

                    <!-- Event Description -->
                    <p class="text-gray-200 text-sm md:text-base mb-6 whitespace-pre-line">
                        {{ event.description }}
                    </p>
                </div>
            </div>
        </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

const event = ref({
    title: "",
    description: "",
    date_start: "",
    date_end: "",
    location: "",
    imageUrl: "",
    instagramUrl: "",
    youtubeUrl: "",
    websiteUrl: "",
    contactPerson: "",
    googleMapsUrl: "",
});
const loading = ref(true);
const error = ref(null);
const isLiked = ref(false);
const likeCount = ref(0);
const isBookmarked = ref(false);
const slug = window.location.pathname.split("/").pop();

const imageError = ref(false);

const handleImageError = (e) => {
    imageError.value = true;
    e.target.style.display = 'none';
};

const goBack = () => {
    window.history.back();
};

const formatDate = (dateString) => {
    if (!dateString) return "No date specified";
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const toggleLike = () => {
    isLiked.value = !isLiked.value;
    likeCount.value += isLiked.value ? 1 : -1;
};

const toggleBookmark = () => {
    isBookmarked.value = !isBookmarked.value;
};

const fetchEvent = async () => {
    loading.value = true;
    error.value = null;
    imageError.value = false; // Reset image error state

    try {
        const response = await axios.get(`/api/events/${slug}`, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer 123",
            },
        });

        const eventData = response.data.data || response.data;
        event.value = {
            title: eventData.title || "Untitled Event",
            description: eventData.description || "No description available",
            date_start: eventData.date_start || null,
            date_end: eventData.date_end || null,
            location: eventData.location || "Location not specified",
            imageUrl: eventData.image_url ?
                eventData.image_url.startsWith("http") ?
                eventData.image_url :
                `/storage/${eventData.image_url.replace(/^public\//, "")}` :
                "/default-event.jpg",
            instagramUrl: eventData.instagram_url || "",
            youtubeUrl: eventData.youtube_url || "",
            websiteUrl: eventData.website_url || "",
            contactPerson: eventData.contact_person || "",
            googleMapsUrl: eventData.google_maps_url || "",
        };

        // Set random like data for demo
        likeCount.value = Math.floor(Math.random() * 100);
        isLiked.value = Math.random() > 0.5;
        isBookmarked.value = Math.random() > 0.5;

    } catch (err) {
        console.error("Error fetching event:", err);
        error.value = "Failed to load event details. Please try again later.";
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchEvent();
});
</script>

<style scoped>
button:active svg {
    transform: scale(1.2);
    transition: transform 0.2s ease;
}

img {
    max-width: 100%;
    height: auto;
}

.rounded-image {
    width: 100%;
    height: auto;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 15px;
}

.social-link {
    transition: all 0.2s ease;
}

.social-link:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .social-link {
        padding: 0.5rem;
    }
    .social-link svg {
        width: 1rem;
        height: 1rem;
    }
}
</style>
