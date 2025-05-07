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

            <!-- Grid Card -->
            <div
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
        console.log("API Response:", response.data); // Debug response

        // Handle single video object response
        const videoData = response.data.content_video || response.data;

        // Create array even if single video
        const videoArray = videoData ? [videoData] : [];

        console.log("Video array:", videoArray);

        videos.value = videoArray.map((video) => ({
            id: video.id,
            title: video.title || "Untitled Video",
            description: video.description || "No description available",
            slug: video.slug,
            video_url: video.video_url // Gunakan video lokal sebagai prioritas
                ? `/storage/${video.video_url.replace(/^public\//, "")}`
                : convertToEmbedUrl(video.link_youtube) || "", // Fallback ke YouTube
            thumbnailUrl: video.thumbnail
                ? `/storage/${video.thumbnail.replace(/^public\//, "")}`
                : "/default-thumbnail.jpg",
            duration: video.metadata_video?.duration || "00:00",
            views: video.total_views || 0,
            tags: video.tag ? video.tag.split(/,\s*/) : [],
            category: video.category || {},
            metadata: video.metadata_video || {},
        }));

        // Helper function untuk YouTube (sebagai fallback)
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

        console.log("Processed videos:", videos.value);
    } catch (err) {
        console.error("Failed to fetch videos:", err);
        if (err.response) {
            console.error("Error response:", err.response.data);
            error.value = `Error: ${
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
