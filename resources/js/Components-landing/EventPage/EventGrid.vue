<template>
  <section class="w-full bg-black py-16 px-6 md:px-10">
    <div class="max-w-[1192px] mx-auto">
      <!-- Judul -->
      <div class="flex flex-col items-center text-white mb-12">
        <span class="w-full h-0.5 bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
          Highlight Events
        </h1>
        <span class="w-full h-0.5 bg-white mt-6"></span>
      </div>

      <!-- Grid Card -->
      <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <EventCard
          v-for="photo in photos"
          :key="photo.slug"
          :imageUrl="photo.imageUrl"
          :title="photo.title"
          :description="photo.description || 'No description available'"
          :titleSize="'lg'"
          @click="openModal(photo)"
        />
      </div>

      <!-- Popup Modal -->
      <div v-if="selectedPhoto" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white bg-opacity-75 rounded-lg max-w-md w-full p-6 relative">
          <button 
            @click="closeModal"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <img 
            :src="selectedPhoto.imageUrl" 
            :alt="selectedPhoto.altText" 
            class="w-full h-48 object-cover rounded-t-lg mb-4"
          >
          
          <h2 class="text-xl font-bold mb-2">{{ selectedPhoto.title }}</h2>
          
          <p class="text-gray-600 mb-4">
            {{ getFirstThreeWords(selectedPhoto.description) }}...
          </p>
          
          <button
            @click="goToDetailPage(selectedPhoto.slug)"
            class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition"
          >
            View All
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import EventCard from './EventCard.vue';
import axios from 'axios';

const router = useRouter();
const photos = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedPhoto = ref(null);

const openModal = (photo) => {
  selectedPhoto.value = photo;
};

const closeModal = () => {
  selectedPhoto.value = null;
};

const getFirstThreeWords = (description) => {
  if (!description) return '';
  const words = description.split(' ');
  return words.slice(0, 3).join(' ');
};

const goToDetailPage = (slug) => {
  router.push(`/photo/${slug}`);
};

onMounted(async () => {
  const options = {
    method: 'GET',
    url: 'http://127.0.0.1:8000/api/content-photos',
    headers: {
      Accept: 'application/json',
      Authorization: 'Bearer 123' // Sesuaikan dengan token sebenarnya jika dibutuhkan
    }
  };

  try {
    const response = await axios.request(options);
    const photoArray = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    photos.value = photoArray.map(photo => ({
      imageUrl: photo.image_url
        ? (photo.image_url.startsWith('http')
            ? photo.image_url
            : `/storage/${photo.image_url.replace(/^public\//, '')}`)
        : '/default-photo.jpg',
      title: photo.title || 'Untitled',
      titleSize: 'base',
      description: photo.description || 'No description available',
      slug: photo.slug,
      altText: photo.alt_text || '',
      tags: photo.tags || []
    }));
  } catch (err) {
    console.error('Gagal mengambil data photo:', err);
    error.value = 'Gagal mengambil data';
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