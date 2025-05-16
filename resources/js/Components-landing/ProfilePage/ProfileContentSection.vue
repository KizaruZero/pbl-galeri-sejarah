<template>
  <div class="min-h-screen text-white">
    <!-- Navigation Tabs -->
    <div class="border-b border-gray-700 py-3 sm:py-4">
      <div class="container flex justify-center space-x-3 sm:space-x-6 md:space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          :class="['font-medium flex items-center text-sm sm:text-base', activeTab === tab.id ? 'text-white' : 'text-gray-400']"
          @click="$emit('change-tab', tab.id)">
          <span class="mr-1 sm:mr-2">
            <component :is="tab.icon" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" />
          </span>
          {{ tab.label }}
        </button>
      </div>
    </div>

    <!-- Media Grid -->
    <div class="mt-6 sm:mt-8 md:mt-12 container mx-auto">
      <!-- Photo Grid -->
      <div v-if="activeTab === 'photo'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-1 sm:gap-2 md:gap-4">
        <div v-for="photo in photos" :key="'photo-' + photo.id"
          class="aspect-square overflow-hidden rounded-md sm:rounded-lg shadow-md cursor-pointer">
          <img :src="photo.image_url" :alt="photo.title"
            class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
        </div>
      </div>

      <!-- Video Grid -->
      <div v-if="activeTab === 'video'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-1 sm:gap-2 md:gap-4">
        <div v-for="video in videos" :key="'video-' + video.id"
          class="aspect-square overflow-hidden relative rounded-md sm:rounded-lg shadow-md cursor-pointer">
          <img :src="video.thumbnail" :alt="video.title"
            class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
          <div class="absolute inset-0 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
              class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-white opacity-80">
              <path fill-rule="evenodd"
                d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286l-11.54 6.347c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Favorites Empty State -->
      <div v-if="activeTab === 'favorit'" class="text-center text-gray-400 py-10 sm:py-16 md:py-20">
        <div class="text-sm sm:text-base md:text-lg">Konten yang ditandai akan tampil di sini.</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

// Tab Icons
const PhotoIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path fill-rule="evenodd"
        d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
        clip-rule="evenodd" />
    </svg>
  `
}

const VideoIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path fill-rule="evenodd"
        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z"
        clip-rule="evenodd" />
    </svg>
  `
}

const FavoriteIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path fill-rule="evenodd"
        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
        clip-rule="evenodd" />
    </svg>
  `
}

const tabs = [
  { id: 'photo', label: 'PHOTO', icon: PhotoIcon },
  { id: 'video', label: 'VIDEO', icon: VideoIcon },
  { id: 'favorit', label: 'FAVORITE', icon: FavoriteIcon }
]

defineProps({
  activeTab: String,
  photos: Array,
  videos: Array
})

defineEmits(['change-tab'])
</script>
