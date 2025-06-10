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
                <button
                    @click="fetchVideos"
                    class="mt-4 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                >
                    Try Again
                </button>
            </div>

            <!-- Content -->
            <div v-if="!loading && !error">
                <!-- Empty State -->
                <div
                    v-if="!videos.length"
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
                        v-for="video in paginatedVideos"
                        :key="video.id"
                        :video_url="video.video_url"
                        :thumbnailUrl="video.thumbnailUrl"
                        :title="video.title"
                        :description="video.description"
                        :duration="video.duration"
                        :views="video.views"
                        :userName="video.userName"
                        :userAvatar="video.userAvatar"
                        :viewsCount="video.viewsCount"
                        :likesCount="video.likeCount"
                        @click="getDetailPage(video.slug)"
                    />
                </div>

                <!-- Pagination -->
                <div v-if="videos.length > 0" class="flex justify-center mt-8 gap-2">
                    <button
                        @click="currentPage--"
                        :disabled="currentPage === 1"
                        class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                    >
                        Previous
                    </button>
                    <div class="flex items-center px-4 text-white">
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
import VideoCard from "./VideoCard.vue";
import axios from "axios";

const videos = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedVideo = ref(null);
const isLiked = ref(false);
const isSaved = ref(false);
const likeCount = ref(Math.floor(Math.random() * 100) + 5);
const currentPage = ref(1);
const itemsPerPage = 6;
const slug = window.location.pathname.split("/").pop();
const slug1 = window.location.pathname.split("/").pop();

// Computed properties for pagination
const totalPages = computed(() => {
    return Math.ceil(videos.value.length / itemsPerPage);
});

const paginatedVideos = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return videos.value.slice(start, end);
});

const getDetailPage = (slug) => {
    window.location.href = `/gallery-video/${slug1}/${slug}`;
};

const fetchVideos = async () => {
    loading.value = true;
    error.value = null;

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
        const validItems = response.data.filter(
            (item) => item.content_video !== null
        );

        // Map the filtered items to our video format
        videos.value = validItems.map((item) => {
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
                    ? `/storage/${video.user.photo_profile.replace(
                          /^public\//,
                          ""
                      )}`
                    : "/js/Assets/default-photo.jpg",
                reactions: video.content_reactions || [],
                comments: video.user_comments || [],
                note: video.note || "",
                source: video.source || "",
                createdAt: video.created_at,
                viewsCount: video.total_views || 0,
                likeCount:
                    video.likes_count || video.content_reactions_count || 0,
            };
        });

        // Sort videos by creation date (newest first)
        videos.value.sort((a, b) => {
            const dateA = new Date(a.createdAt || 0);
            const dateB = new Date(b.createdAt || 0);
            return dateB - dateA;
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
};

onMounted(() => {
    fetchVideos();
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
