<template>
  <div class="flex flex-col mb-8 sm:mb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
      <!-- Profile Info -->
      <div class="flex flex-col col-span-1 items-center md:items-start">
        <div class="relative w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 mb-4">
          <img :src="profilePhoto" alt="Profile Photo"
            class="w-full h-full object-cover rounded-full z-10 relative" />
          <div class="absolute top-0 left-0 w-full h-full border-4 border-white rounded-full blur-sm z-0" />
          <div class="absolute top-0 left-0 w-full h-full border border-white rounded-full z-10" />
        </div>

        <div class="text-white text-center md:text-left">
          <h1 class="text-xl sm:text-2xl md:text-3xl font-medium mb-2">{{ userData?.name }}</h1>
          <p class="text-base sm:text-lg md:text-xl mb-1">{{ userData?.role }}</p>
          <p class="text-base sm:text-lg md:text-xl">{{ userData?.email }}</p>
        </div>
      </div>

      <!-- Stats -->
      <div class="flex col-span-2 justify-center md:align-left items-center">
        <div class="flex flex-wrap justify-around gap-4 sm:gap-8 md:gap-12 lg:mb-20">
          <div class="flex flex-col items-center">
            <span class="text-white text-xl sm:text-2xl md:text-3xl font-semibold">{{ stats.posts }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">POSTINGAN</span>
          </div>
          <div class="flex flex-col items-center">
            <span class="text-white text-xl sm:text-2xl md:text-3xl font-semibold">{{ stats.likes }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">LIKE</span>
          </div>
          <div class="flex flex-col items-center">
            <span class="text-white text-xl sm:text-2xl md:text-3xl font-semibold">{{ stats.favorites }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">FAVORITE</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  userData: Object,
  profilePhoto: String,
  stats: {
    type: Object,
    required: true,
    default: () => ({ posts: 0, likes: 0, favorites: 0 })
  }
})
</script>
