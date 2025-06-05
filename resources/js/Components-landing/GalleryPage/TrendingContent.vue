<template>
  <section class="p-10 w-full bg-white dark:bg-black">
    <div class="max-w-[1192px] mx-auto">
      <!-- Judul -->
      <div class="flex flex-col items-center text-black dark:text-white mb-12">
        <span class="w-full h-0.5 bg-black dark:bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
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
          :videoUrl="content.videoUrl"
          :totalLikes="content.totalLikes"
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
import ImageCard from "./ImageCard.vue";
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
    // Fetch popular photos
    const photoResponse = await axios.get("/api/popular-photo", {
      headers: {
        Accept: "application/json",
        Authorization: "Bearer 123",
      },
    });

    // Fetch popular videos
    const videoResponse = await axios.get("/api/popular-video", {
      headers: {
        Accept: "application/json",
        Authorization: "Bearer 123",
      },
    });

    // Combine and process photos and videos
    const photos = (photoResponse.data || []).map((photo) => ({
      type: "photo",
      slug: photo.slug,
      imageUrl: photo.image_url
        ? photo.image_url.startsWith("http")
          ? photo.image_url
          : `/storage/${photo.image_url.replace(/^public\//, "")}`
        : "/js/Assets/default-photo.jpg",
      title: photo.title || "Untitled",
      titleSize: "base",
      description: photo.description || "No description",
    }));

    const videos = (videoResponse.data || []).map((video) => ({
      type: "video",
      slug: video.slug,
      imageUrl: video.thumbnail
        ? video.thumbnail.startsWith("http")
          ? video.thumbnail
          : `/storage/${video.thumbnail.replace(/^public\//, "")}`
        : "/js/Assets/default-photo.jpg",
      title: video.title || "Untitled",
      titleSize: "base",
      description: video.description || "No description",
      videoUrl: video.video_url,
    }));

    // Take top 3 from each and combine
    trendingContent.value = [...photos.slice(0, 3), ...videos.slice(0, 3)];
  } catch (error) {
    console.error("Error fetching content:", error);
  } finally {
    loading.value = false; // Set loading to false when done
  }
});
</script>
