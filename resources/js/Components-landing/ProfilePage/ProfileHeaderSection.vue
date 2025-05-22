<template>
  <div class="flex flex-col mb-6 sm:mb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-8">
      <!-- Profile Info -->
      <div class="flex flex-col col-span-1 items-center md:items-start">
        <div class="relative w-28 h-28 sm:w-40 sm:h-40 md:w-48 md:h-48 mb-3 sm:mb-4">
          <img :src="profilePhoto" alt="Profile Photo"
            class="w-full h-full object-cover rounded-full z-10 relative" />
          <div class="absolute top-0 left-0 w-full h-full border-4 border-white rounded-full blur-sm z-0" />
          <div class="absolute top-0 left-0 w-full h-full border border-white rounded-full z-10" />
        </div>

        <div class="text-white text-center md:text-left px-2 sm:px-0">
          <h1 class="text-lg sm:text-2xl md:text-3xl font-medium mb-1 sm:mb-2">{{ userData?.name }}</h1>
          <p class="text-sm sm:text-lg md:text-xl mb-1">{{ userData?.role }}</p>
          <p class="text-sm sm:text-lg md:text-xl">{{ userData?.email }}</p>
        </div>
      </div>

      <!-- Stats -->
      <div class="flex col-span-2 justify-center md:justify-start items-center mt-4 sm:mt-0">
        <div class="flex flex-wrap justify-between w-full sm:justify-around gap-1 sm:gap-8 md:gap-12 lg:mb-20">
          <div class="flex flex-col items-center px-2 sm:px-0">
            <span class="text-white text-lg sm:text-2xl md:text-3xl font-semibold">{{ stats.posts }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">UNGGAHAN</span>
          </div>
          <div class="flex flex-col items-center px-2 sm:px-0">
            <span class="text-white text-lg sm:text-2xl md:text-3xl font-semibold">{{ stats.likes }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">LIKE</span>
          </div>
          <div class="flex flex-col items-center px-2 sm:px-0">
            <span class="text-white text-lg sm:text-2xl md:text-3xl font-semibold">{{ stats.favorites }}</span>
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
