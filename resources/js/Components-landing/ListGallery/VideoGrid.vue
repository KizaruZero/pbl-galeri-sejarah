<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Video Gallery
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
                v-else-if="!videos.length"
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
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                    />
                </svg>
                <h3 class="text-xl font-medium mb-1">No Videos Found</h3>
                <p class="text-gray-400">
                    There are no videos available in this gallery yet.
                </p>
            </div>

            <!-- Grid Card -->
            <div
                v-else
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
            >
                <VideoCard
                    v-for="video in videos"
                    :key="video.id"
                    :video_url="video.video_url"
                    :thumbnailUrl="video.thumbnailUrl"
                    :title="video.title"
                    :description="video.description"
                    :duration="video.duration"
                    :views="video.views"
                    :userName="video.userName"
                    :userAvatar="video.userAvatar"
                    @click="getDetailPage(video.slug)"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VideoCard from "./VideoCard.vue";
import axios from "axios";

const videos = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedVideo = ref(null);
const isLiked = ref(false);
const isSaved = ref(false);
const likeCount = ref(Math.floor(Math.random() * 100) + 5);
const slug = window.location.pathname.split("/").pop(); // ambil slug dari URL
const slug1 = window.location.pathname.split("/").pop(); // ambil slug dari URL

const getDetailPage = (slug) => {
    window.location.href = `/gallery-video/${slug1}/${slug}`;
};

onMounted(async () => {
    const options = {
        method: "GET",
        url: `/api/category-video/${slug}`,
        headers: {
            Accept: "application/json",
            Authorization: "Bearer 123",
        },
    };

    try {
        const response = await axios.request(options);

        // Filter only items with content_video (exclude photos and null content)
        const validItems = response.data.filter(item => item.content_video !== null);
        
        // Map the filtered items to our video format
        videos.value = validItems.map(item => {
            const video = item.content_video;
            return {
                id: video.id,
                title: video.title || "Untitled Video",
                description: video.description || "No description available",
                slug: video.slug,
                video_url: video.video_url
                    ? `/storage/${video.video_url.replace(/^public\//, "")}`
                    : convertToEmbedUrl(video.link_youtube) || "",
                thumbnailUrl: video.thumbnail
                    ? `/storage/${video.thumbnail.replace(/^public\//, "")}`
                    : "/js/Assets/default-photo.jpg",
                duration: video.metadata_video?.duration || "00:00",
                views: video.total_views || 0,
                tags: video.tag ? video.tag.split(/,\s*/) : [],
                category: item.category || {},
                metadata: video.metadata_video || {},
                userName: video.user?.name || "Anonymous",
                userAvatar: video.user?.photo_profile
                    ? `/storage/${video.user.photo_profile.replace(/^public\//, "")}`
                    : "/js/Assets/default-avatar.jpg",
                reactions: video.content_reactions || [],
                comments: video.user_comments || [],
                note: video.note || "",
                source: video.source || "",
                createdAt: video.created_at,
            };
        });

        // Helper function untuk YouTube
        function convertToEmbedUrl(url) {
            if (!url) return null;
            if (url.includes("youtube.com/watch")) {
                const videoId = url.split("v=")[1]?.split("&")[0];
                return videoId
                    ? `https://www.youtube.com/embed/${videoId}`
                    : null;
            }
            return url;
        }

    } catch (err) {
        console.error("Failed to fetch videos:", err);
        if (err.response) {
            console.error("Error response:", err.response.data);
            error.value = ` ${
                err.response.data.message || "Failed to fetch videos"
            }`;
        } else {
            error.value = "Network error or server unavailable";
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
