<template>
    <section class="w-full bg-white dark:bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div
                class="flex flex-col items-center text-black dark:text-white mb-12"
            >
                <span class="w-full h-0.5 bg-black dark:bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Gallery
                </h1>
                <span class="w-full h-0.5 bg-black dark:bg-white mt-6"></span>
            </div>

            <!-- Loading State -->
            <div
                v-if="loading"
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 animate-pulse"
            >
                <!-- Skeleton loading cards (9 items to match pagination) -->
                <div
                    v-for="i in 6"
                    :key="i"
                    class="bg-zinc-900 rounded-lg overflow-hidden shadow-lg h-[400px]"
                >
                    <!-- Image placeholder -->
                    <div class="w-full h-48 bg-gray-800"></div>

                    <!-- Content placeholder -->
                    <div class="p-4">
                        <div class="h-6 w-3/4 bg-gray-800 rounded mb-3"></div>
                        <div class="h-4 w-full bg-gray-800 rounded mb-2"></div>
                        <div class="h-4 w-5/6 bg-gray-800 rounded mb-2"></div>
                        <div class="h-4 w-2/3 bg-gray-800 rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center text-white py-20">
                <svg
                    class="w-16 h-16 mx-auto mb-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
                <p class="text-lg">{{ error }}</p>
                <button
                    @click="fetchPhotos"
                    class="mt-4 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                >
                    Try Again
                </button>
            </div>

            <!-- Content -->
            <div v-if="!loading && !error">
                <!-- Empty State -->
                <div
                    v-if="!photos.length"
                    class="flex flex-col items-center justify-center py-20 text-white"
                >
                    <svg
                        class="w-20 h-20 mb-4 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <h3 class="text-xl font-medium mb-1">No Photos Found</h3>
                    <p class="text-gray-400">
                        There are no photos available in this gallery yet.
                    </p>
                </div>

                <!-- Grid Card -->
                <div
                    v-else
                    class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
                >
                    <ImageCard
                        v-for="photo in paginatedPhotos"
                        :key="photo.slug"
                        :imageUrl="
                            photo.imageUrl || '/js/Assets/default-photo.jpg'
                        "
                        :title="photo.title"
                        :description="
                            photo.description || 'No description available'
                        "
                        :userId="photo.user_id"
                        :userName="photo.userName || 'Unknown Photographer'"
                        :userAvatar="
                            photo.userAvatar || '/js/Assets/default-photo.jpg'
                        "
                        :viewsCount="photo.viewsCount"
                        :likesCount="photo.likeCount"
                        :titleSize="'lg'"
                        @click="getDetailPage(photo.slug)"
                    />
                </div>

                <!-- Pagination -->
                <div
                    v-if="photos.length > 0"
                    class="flex justify-center mt-8 gap-2"
                >
                    <button
                        @click="currentPage--"
                        :disabled="currentPage === 1"
                        class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                    >
                        Previous
                    </button>
                    <div
                        class="flex items-center px-4 text-black dark:text-white"
                    >
                        Page {{ currentPage }} of {{ totalPages }}
                    </div>
                    <button
                        @click="currentPage++"
                        :disabled="currentPage >= totalPages"
                        class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import ImageCard from "./ImageCard.vue";
import axios from "axios";

const photos = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedPhoto = ref(null);
const isLiked = ref(false);
const isSaved = ref(false);
const likeCount = ref(Math.floor(Math.random() * 100) + 5);
const currentPage = ref(1);
const itemsPerPage = 6;
const slug = window.location.pathname.split("/").pop();
const slug1 = window.location.pathname.split("/").pop();

// Computed properties for pagination
const totalPages = computed(() => {
    return Math.ceil(photos.value.length / itemsPerPage);
});

const paginatedPhotos = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return photos.value.slice(start, end);
});

const getDetailPage = (slug) => {
    window.location.href = `/gallery-photo/${slug1}/${slug}`;
};

// Add getMediaUrl helper function at the beginning of script section
const getMediaUrl = (url) => {
    if (!url) return "/js/Assets/default-photo.jpg";
    if (url.startsWith("http")) return url;

    const cleanPath = url
        .replace(/^storage\//, "")
        .replace(/^public\//, "")
        .replace(/^\//, "")
        .replace(/^storage\//, "");

    return cleanPath ? `/storage/${cleanPath}` : "/js/Assets/default-photo.jpg";
};

const fetchPhotos = async () => {
    loading.value = true;
    error.value = null;

    const options = {
        method: "GET",
        url: `/api/category-photo/${slug}`,
        headers: {
            Accept: "application/json",
            Authorization: "Bearer 123",
        },
    };

    try {
        const response = await axios.request(options);

        // Get the photos array from response
        const photoData = response.data.photos || [];

        // Filter and map the photos
        photos.value = photoData
            .filter((item) => item.content_photo)
            .map((item) => {
                const photo = item.content_photo;
                return {
                    id: photo.id,
                    title: photo.title || "Untitled Photo",
                    description:
                        photo.description || "No description available",
                    slug: photo.slug,
                    imageUrl: photo.image_url
                        ? `/storage/${photo.image_url.replace(/^public\//, "")}`
                        : "/js/Assets/default-photo.jpg",
                    user_id: photo.user_id,
                    userName: photo.user?.name || "Anonymous",
                    userAvatar: photo.user?.photo_profile
                        ? `/storage/${photo.user.photo_profile.replace(
                              /^public\//,
                              ""
                          )}`
                        : "/js/Assets/default-photo.jpg",
                    category: item.category || {},
                    viewsCount: photo.total_views || 0,
                    likeCount:
                        photo.likes_count || photo.content_reactions_count || 0,
                    createdAt: photo.created_at,
                };
            });

        // Sort photos by creation date (newest first)
        photos.value.sort((a, b) => {
            const dateA = new Date(a.createdAt || 0);
            const dateB = new Date(b.createdAt || 0);
            return dateB - dateA;
        });
    } catch (err) {
        console.error("Failed to fetch photos:", err);
        if (err.response) {
            error.value = err.response.data.message || "Failed to fetch photos";
        } else {
            error.value = "Network error or server unavailable";
        }
    } finally {
        loading.value = false;
    }
};

// Handle avatar image error
const handleAvatarError = (e) => {
    e.target.src = "/js/Assets/default-photo.jpg";
};
onMounted(() => {
    fetchPhotos();
});
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
