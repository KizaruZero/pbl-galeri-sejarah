<template>
    <section class="p-10 w-full bg-white dark:bg-black">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div
                class="flex flex-col items-center text-black dark:text-white mb-12"
            >
                <span class="w-full h-0.5 bg-black dark:bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Trending Content
                </h1>
                <span class="w-full h-0.5 bg-black dark:bg-white mt-6"></span>
            </div>

            <!-- Loading State -->
            <div
                v-if="loading"
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 animate-pulse"
            >
                <!-- Skeleton loading cards -->
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

            <!-- Content grid -->
            <div
                v-else
                class="grid grid-cols-3 gap-14 mx-auto max-w-[1192px] max-md:grid-cols-2 max-sm:grid-cols-1"
            >
                <ImageCard
                    v-for="content in trendingContent"
                    :key="content.slug"
                    :imageUrl="content.imageUrl"
                    :title="content.title"
                    :titleSize="content.titleSize"
                    :description="content.description"
                    :userId="content.user_id"
                    :userName="content.userName || 'Unknown Photographer'"
                    :userAvatar="
                        content.userAvatar || '/js/Assets/default-photo.jpg'
                    "
                    :videoUrl="content.videoUrl"
                    :likesCount="content.likesCount || 0"
                    :viewsCount="content.viewsCount || 0"
                    @play-video="openVideoPlayer"
                    @click="getDetailPage(content.slug)"
                />
            </div>
        </div>
    </section>

    <!-- Video Player Modal -->
    <VideoPlayer
        :isOpen="isVideoPlayerOpen"
        :videoUrl="currentVideoUrl"
        @close="closeVideoPlayer"
    />
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import ImageCard from "@/Components-landing/ListGallery/ImageCard.vue";
import VideoPlayer from "@/Components-landing/VideoPlayer.vue";

const trendingContent = ref([]);
const isVideoPlayerOpen = ref(false);
const currentVideoUrl = ref("");
const loading = ref(true); // Add loading state
const slug = window.location.pathname.split("/").pop(); // ambil slug dari URL

const getDetailPage = (slug) => {
    window.location.href = `/gallery-photo/${slug}`;
};

const openVideoPlayer = (videoUrl) => {
    currentVideoUrl.value = videoUrl;
    isVideoPlayerOpen.value = true;
};

const closeVideoPlayer = () => {
    isVideoPlayerOpen.value = false;
    currentVideoUrl.value = "";
};

onMounted(async () => {
    try {
        let photos = [];
        let videos = [];

        // Fetch popular photos
        try {
            const photoResponse = await axios.get("/api/popular-photo", {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer 123",
                },
            });

            if (photoResponse.data && Array.isArray(photoResponse.data)) {
                photos = photoResponse.data.map((photo) => ({
                    type: "photo",
                    slug: photo.slug,
                    imageUrl: photo.image_url
                        ? photo.image_url.startsWith("http")
                            ? photo.image_url
                            : `/storage/${photo.image_url.replace(
                                  /^public\//,
                                  ""
                              )}`
                        : "/js/Assets/default-photo.jpg",
                    user_id: photo.user_id,
                    userName: photo.user?.name || "Anonymous",
                    userAvatar: photo.user?.photo_profile
                        ? `/storage/${photo.user.photo_profile.replace(
                              /^public\//,
                              ""
                          )}`
                        : "/js/Assets/default-photo.jpg",
                    title: photo.title || "Untitled",
                    titleSize: "base",
                    description: photo.description || "No description",
                    likesCount:
                        photo.likes_count || photo.content_reactions_count || 0,
                    viewsCount: photo.total_views || 0,
                }));
            }
        } catch (photoError) {
            console.warn("Error fetching photos:", photoError);
            photos = [];
        }

        // Fetch popular videos
        try {
            const videoResponse = await axios.get("/api/popular-video", {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer 123",
                },
            });

            if (videoResponse.data && Array.isArray(videoResponse.data)) {
                videos = videoResponse.data.map((video) => ({
                    type: "video",
                    slug: video.slug,
                    imageUrl: video.thumbnail
                        ? video.thumbnail.startsWith("http")
                            ? video.thumbnail
                            : `/storage/${video.thumbnail.replace(
                                  /^public\//,
                                  ""
                              )}`
                        : "/js/Assets/default-photo.jpg",
                    title: video.title || "Untitled",
                    titleSize: "base",
                    description: video.description || "No description",
                    user_id: video.user_id,
                    userName: video.user?.name || "Anonymous",
                    userAvatar: video.user?.photo_profile
                        ? `/storage/${video.user.photo_profile.replace(
                              /^public\//,
                              ""
                          )}`
                        : "/js/Assets/default-photo.jpg",
                    videoUrl: video.video_url,
                }));
            }
        } catch (videoError) {
            console.warn("Error fetching videos:", videoError);
            videos = [];
        }

        // Combine available content
        trendingContent.value = [...photos.slice(0, 3), ...videos.slice(0, 3)];
    } catch (error) {
        console.error("Error in content fetching:", error);
        trendingContent.value = []; // Set empty array if there's a general error
    } finally {
        loading.value = false;
    }
});
</script>
