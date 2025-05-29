<template>
  <section class="w-full bg-black py-16 px-6 md:px-10">
    <div class="max-w-[1192px] mx-auto">
      <!-- Header -->
      <div class="flex flex-col items-center text-white mb-12">
        <span class="w-full h-0.5 bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">Unggahan Terbaru</h1>
        <span class="w-full h-0.5 bg-white mt-6"></span>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 animate-pulse">
        <div v-for="i in 6" :key="i" class="bg-zinc-900 rounded-lg overflow-hidden shadow-lg h-[400px]">
          <div class="w-full h-48 bg-gray-800"></div>
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
        <svg class="w-16 h-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-lg">{{ error }}</p>
        <button @click="fetchRecentMedia" class="mt-4 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
          Coba Lagi
        </button>
      </div>

      <!-- Empty State -->
      <div v-else-if="!photos.length && !videos.length" class="flex flex-col items-center justify-center py-20 text-white">
        <svg class="w-20 h-20 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="text-xl font-medium mb-1">Belum ada unggahan</h3>
        <p class="text-gray-400">Tidak ada foto atau video yang tersedia saat ini.</p>
      </div>

      <!-- Content -->
      <div v-else>
        <!-- Photos Section -->
        <div v-if="photos.length > 0">
          <h2 class="text-2xl font-serif text-white mb-6">Foto Terbaru</h2>
          <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mb-12">
            <div v-for="item in photos.slice(0, 3)" :key="item.id" 
                 class="bg-zinc-900 rounded-lg overflow-hidden shadow-lg hover:scale-105 transition-transform cursor-pointer"
                 @click="navigateToDetail(item)">
              <div class="w-full h-48">
                <img :src="item.image_url" :alt="item.title" class="w-full h-full object-cover">
              </div>
              <div class="p-4 text-white">
                <h3 class="font-medium text-lg mb-2">{{ item.title }}</h3>
                <p class="text-sm text-gray-400">{{ formatDate(item.uploaded_at) }}</p>
                <p class="text-gray-400 mt-2" v-if="item.description">{{ item.description }}</p>
                <p v-else class="text-gray-400 mt-2">Tidak ada deskripsi</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Videos Section -->
        <div v-if="videos.length > 0">
          <div class="w-full h-0.5 bg-gray-700 my-8"></div>
          <h2 class="text-2xl font-serif text-white mb-6">Video Terbaru</h2>
          <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="item in videos.slice(0, 3)" :key="item.id" 
                 class="bg-zinc-900 rounded-lg overflow-hidden shadow-lg hover:scale-105 transition-transform cursor-pointer"
                 @click="navigateToDetail(item)">
              <div class="relative">
                <img :src="item.thumbnail" :alt="item.title" class="w-full h-48 object-cover">
                <div class="absolute top-2 right-2 bg-black/70 p-1 rounded">
                  <span class="text-white text-sm">🎥</span>
                </div>
              </div>
              <div class="p-4 text-white">
                <h3 class="font-medium text-lg mb-2">{{ item.title }}</h3>
                <p class="text-sm text-gray-400">{{ formatDate(item.uploaded_at) }}</p>
                <p class="text-gray-400 mt-2" v-if="item.description">{{ item.description }}</p>
                <p v-else class="text-gray-400 mt-2">Tidak ada deskripsi</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const photos = ref([]);
const videos = ref([]);
const loading = ref(true);
const error = ref(null);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const navigateToDetail = (item) => {
  if (item.type === 'photo') {
    window.location.href = `/gallery-photo/${item.slug}`;
  } else if (item.type === 'video') {
    window.location.href = `/gallery-video/${item.slug}`;
  }
};

const fetchRecentMedia = async () => {
  loading.value = true;
  error.value = null;

  const photoOptions = {
    method: 'GET',
    url: 'http://127.0.0.1:8000/api/content-photo',
    headers: { Accept: 'application/json', Authorization: 'Bearer 123' }
  };

  const videoOptions = {
    method: 'GET',
    url: 'http://127.0.0.1:8000/api/content-video',
    headers: { Accept: 'application/json', Authorization: 'Bearer 123' }
  };

  try {
    // Fetch both photos and videos concurrently
    const [photoResponse, videoResponse] = await Promise.all([
      axios.request(photoOptions),
      axios.request(videoOptions)
    ]);

    // Process photos
    photos.value = photoResponse.data.map(item => ({
      id: item.id,
      title: item.title || 'Untitled Photo',
      description: item.description || null,
      type: 'photo',
      slug: item.slug,
      image_url: item.image_url?.startsWith('http') 
           ? item.image_url 
           : `/storage/${item.image_url?.replace(/^public\//, '')}`,
      uploaded_at: item.created_at
    })).sort((a, b) => new Date(b.uploaded_at) - new Date(a.uploaded_at));

    // Process videos
    videos.value = videoResponse.data.map(item => ({
      id: item.id,
      title: item.title || 'Untitled Video',
      description: item.description || null,
      type: 'video',
      slug: item.slug,
      url: item.video_url?.startsWith('http') 
           ? item.video_url 
           : `/storage/${item.video_url?.replace(/^public\//, '')}`,
      thumbnail: item.thumbnail?.startsWith('http')
                ? item.thumbnail
                : `/storage/${item.thumbnail?.replace(/^public\//, '')}`,
      uploaded_at: item.created_at
    })).sort((a, b) => new Date(b.uploaded_at) - new Date(a.uploaded_at));

  } catch (err) {
    console.error('Failed to fetch media:', err);
    error.value = 'Gagal memuat data. Silakan coba lagi nanti.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchRecentMedia();
});
</script>