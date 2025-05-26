<template>
  <section class="p-10 w-full bg-black">
    <div class="max-w-[1192px] mx-auto">
      <!-- Judul -->
      <div class="flex flex-col items-center text-white mb-12">
        <span class="w-full h-0.5 bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
          Trending Content
        </h1>
        <span class="w-full h-0.5 bg-white mt-6"></span>
      </div>
      <div
        class="grid grid-cols-3 gap-14 mx-auto max-w-[1192px] max-md:grid-cols-2 max-sm:grid-cols-1"
      >
        <ImageCard
          v-for="event in events"
          :key="event.slug"
          :imageUrl="event.imageUrl"
          :title="event.title"
          :titleSize="event.titleSize"
          :description="event.description"
          :videoUrl="event.videoUrl"
          @play-video="openVideoPlayer"
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

const events = ref([]);
const isVideoPlayerOpen = ref(false);
const currentVideoUrl = ref("");

const openVideoPlayer = (videoUrl) => {
  currentVideoUrl.value = videoUrl;
  isVideoPlayerOpen.value = true;
};

const closeVideoPlayer = () => {
  isVideoPlayerOpen.value = false;
  currentVideoUrl.value = "";
};

onMounted(async () => {
  const options = {
    method: "GET",
    url: "/api/content-video",
    headers: {
      Accept: "application/json",
      Authorization: "Bearer 123", // sesuaikan token jika diperlukan
    },
  };

  try {
    const { data } = await axios.request(options);

    // map ke struktur event
    events.value = data.map((video) => ({
      imageUrl: video.thumbnail
        ? video.thumbnail.startsWith("http")
          ? video.thumbnail
          : `/storage/${video.thumbnail.replace(/^public\//, "")}`
        : "/js/Assets/default-photo.jpg",
      title: video.title,
      titleSize: "base",
      description: video.description,
      slug: video.slug,
      videoUrl: video.video_url
        ? video.video_url.startsWith("http")
          ? video.video_url
          : `/storage/${video.video_url.replace(/^public\//, "")}`
        : "",
    }));
  } catch (error) {
    console.error("Gagal mengambil data video:", error);
  }
});
</script>
