<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Gallery
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Loading State -->
            <div
                v-if="loading"
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 animate-pulse"
            >
                <!-- Skeleton loading cards -->
                <div
                    v-for="i in 3"
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
            </div>

            <!-- Empty State -->
            <div
                v-else-if="!photos.length"
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
                    v-for="photo in photos"
                    :key="photo.slug"
                    :imageUrl="photo.imageUrl"
                    :title="photo.title"
                    :description="
                        photo.description || 'No description available'
                    "
                    :userId="photo.user_id"
                    :userName="photo.userName || 'Unknown Photographer'"
                    :userAvatar="
                        photo.userAvatar || '/js/Assets/default-avatar.jpg'
                    "
                    :titleSize="'lg'"
                    @click="getDetailPage(photo.slug)"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ImageCard from "./ImageCard.vue";
import axios from "axios";

const photos = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedPhoto = ref(null);
const isLiked = ref(false);
const isSaved = ref(false);
const likeCount = ref(Math.floor(Math.random() * 100) + 5); // Random initial like count
const slug = window.location.pathname.split("/").pop(); // ambil slug dari URL
const slug1 = window.location.pathname.split("/").pop(); // ambil slug dari URL

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

onMounted(async () => {
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
                        : "/js/Assets/default-avatar.jpg",
                    category: item.category || {},
                    createdAt: photo.created_at,
                };
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
