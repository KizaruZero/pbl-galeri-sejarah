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
            <div v-if="loading" class="flex justify-center items-center py-20">
                <div
                    class="w-10 h-10 border-4 border-white border-t-transparent rounded-full animate-spin"
                ></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center text-red-500 py-20">
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
                    :userName="photo.user?.name || 'Unknown Photographer'"
                    :userAvatar="photo.user?.avatar"
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
        console.log("API Response:", response.data);

        const photoArray = Array.isArray(response.data.photos)
            ? response.data.photos
            : response.data.photos.data || [];

        console.log("Photo Array before mapping:", photoArray);

        photos.value = photoArray.map((item) => {
            const photo = item.content_photo;
            console.log("Individual photo item:", item);
            console.log("User data:", photo.user);

            const photoUser = photo.user || null;
            console.log("Photo user profile:", photoUser?.photo_profile);

            return {
                ...photo,
                imageUrl: photo.image_url
                    ? photo.image_url.startsWith("http")
                        ? photo.image_url
                        : `/storage/${photo.image_url.replace(/^public\//, "")}`
                    : "/js/Assets/default-photo.jpg",
                title: photo.title || "Untitled",
                titleSize: "base",
                description: photo.description || "No description available",
                slug: photo.slug,
                altText: photo.alt_text || "",
                tags: photo.tag ? photo.tag.split(", ") : [],
                user_id: photo.user_id,
                user: photoUser ? {
                    ...photoUser,
                    name: photoUser.name || "Unknown Photographer",
                    avatar: getMediaUrl(photoUser.photo_profile)
                } : null,
                category: item.category,
            };
        });

        console.log("Final processed photos:", photos.value);

        // Set dummy data untuk demo - applied to each photo if needed
        photos.value = photos.value.map((photo) => ({
            ...photo,
            likeCount: Math.floor(Math.random() * 100),
            isLiked: Math.random() > 0.5,
            isBookmarked: Math.random() > 0.5,

            // Set dummy komentar for each photo if needed
            comments: [
                {
                    id: 1,
                    user: "John Doe",
                    text: "This is an amazing photo! The colors are fantastic.",
                    date: "2023-05-15T10:30:00Z",
                    canDelete: false,
                },
                {
                    id: 2,
                    user: "Jane Smith",
                    text: "Great composition and lighting!",
                    date: "2023-05-14T16:45:00Z",
                    canDelete: false,
                },
            ],
        }));
    } catch (err) {
        if (err.response && err.response.status === 404) {
            error.value = "Tidak ada konten ditemukan untuk kategori ini";
        } else {
            error.value = "Gagal mengambil data";
        }
        console.error("Error:", err);
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
