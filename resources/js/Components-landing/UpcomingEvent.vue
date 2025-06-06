<template>
  <main class="relative p-5 bg-white dark:bg-black text-white">
    <!-- Title & Button -->
    <div
      class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 sm:px-6 md:px-10 lg:px-20 py-6 md:py-10"
    >
      <!-- Container untuk title + button (mobile) -->
      <div class="w-full flex flex-row items-center justify-between md:contents">
        <!-- Title Section -->
        <h1
          class="text-2xl sm:text-xl md:text-3xl lg:text-4xl xl:text-5xl font-serif text-center md:text-left relative flex-1"
        >
          <!-- Garis atas (untuk semua ukuran layar) -->
          <span
            class="block w-16 xs:w-20 sm:w-24 md:w-20 lg:w-24 h-0.5 bg-black dark:bg-white mb-3 xs:mb-4 sm:mb-5 md:mb-4 lg:mb-6 mx-auto md:mx-0"
          ></span>

          <span class="inline-block text-black dark:text-white">Upcoming Events</span>

          <!-- Garis bawah (untuk semua ukuran layar) -->
          <span
            class="block w-full h-0.5 bg-black dark:bg-white mt-3 xs:mt-4 sm:mt-5 md:mt-4 lg:mt-6"
          ></span>
        </h1>

        <!-- Button Section (mobile - lebih kecil) -->
        <div class="md:hidden ml-2">
          <a
            href="/events"
            class="inline-flex items-center justify-center w-12 h-12 text-[10px] xs:text-xs sm:text-sm rounded-full border border-black dark:border-white text-black dark:text-white text-nowrap leading-none text-center transition-all duration-300 hover:scale-105 hover:bg-black dark:hover:bg-white hover:text-white dark:hover:text-black focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
          >
            View All
          </a>
        </div>
      </div>

      <!-- Button Section (desktop - ukuran normal) -->
      <div class="hidden md:block md:ml-4">
        <a
          href="/events"
          class="inline-flex items-center justify-center w-16 h-16 md:w-16 md:h-16 lg:w-20 lg:h-20 rounded-full border border-black dark:border-white text-black dark:text-white text-base md:text-base lg:text-lg transition-all duration-300 hover:scale-105 hover:bg-black dark:hover:bg-white hover:text-white dark:hover:text-black focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
        >
          View All
        </a>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="mt-16 pb-20 animate-pulse">
      <div class="flex gap-6 px-4">
        <!-- Repeat loading skeleton for 3 items (or however many you want to show) -->
        <div v-for="i in 3" :key="i" class="w-full">
          <div
            class="relative mx-4 bg-zinc-900 rounded-xl overflow-hidden shadow-lg flex flex-col h-[450px]"
          >
            <!-- Image placeholder -->
            <div class="w-full h-[300px] bg-gray-800 rounded-t-xl"></div>

            <!-- Content placeholder -->
            <div class="px-4 mt-4 flex flex-col gap-4 h-[150px]">
              <div class="h-6 w-3/4 bg-gray-800 rounded"></div>
              <div class="h-4 w-1/2 bg-gray-800 rounded"></div>
              <div class="h-4 w-full bg-gray-800 rounded"></div>
              <div class="h-4 w-2/3 bg-gray-800 rounded"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Carousel -->
    <div v-else class="mt-16 pb-20">
      <Carousel
        v-if="slides.length > 0"
        :value="slides"
        :numVisible="3"
        :numScroll="1"
        :responsiveOptions="responsiveOptions"
        circular
        :autoplayInterval="3000"
        class="events-carousel"
      >
        <template #item="slotProps">
          <div
            class="relative mx-4 bg-zinc-200 dark:bg-zinc-900 rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-[1.02] flex flex-col h-[450px] cursor-pointer"
            @click="getDetailPage(slotProps.data.slug)"
          >
            <!-- Image section (60%) -->
            <div class="relative w-full h-[300px] bg-black">
              <img
                :src="slotProps.data.imageUrl ? slotProps.data.imageUrl : '/js/Assets/default-photo.jpg'"
                :alt="slotProps.data.altText || slotProps.data.title"
                class="object-cover w-full h-full transition-opacity duration-300"
                @error="$event.target.src = '/js/Assets/default-photo.jpg'"
              />
              <div class="absolute inset-0 bg-black dark:bg-white bg-opacity-10 dark:bg-opacity-20"></div>
            </div>

            <!-- Content section (40%) -->
            <div class="px-4 mt-4 flex flex-col gap-2 h-[150px]">
              <h2 class="text-base text-black dark:text-white font-bold uppercase line-clamp-1">
                {{ slotProps.data.title }}
              </h2>
              <time class="text-xs uppercase text-black dark:text-white">
                {{ formatDate(slotProps.data.date_start) }}
              </time>
              <p
                class="text-xs font-light text-black dark:text-white tracking-wide leading-snug text-justify line-clamp-2"
              >
                {{ slotProps.data.description || "No description available" }}
              </p>
            </div>
          </div>
        </template>
      </Carousel>

      <!-- Empty state -->
      <div v-if="slides.length === 0" class="text-center py-20">
        <p class="text-xl text-black dark:text-white">No upcoming events found</p>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";

const slides = ref([]);
const loading = ref(true);
const error = ref(null);

const isDarkTheme = ref(true);
const toggleTheme = () => {
  isDarkTheme.value = !isDarkTheme.value;
};

const responsiveOptions = ref([
  { breakpoint: "2560px", numVisible: 4, numScroll: 1 },
  { breakpoint: "1440px", numVisible: 4, numScroll: 1 },
  { breakpoint: "1024px", numVisible: 3, numScroll: 1 },
  { breakpoint: "768px", numVisible: 2, numScroll: 1 },
  { breakpoint: "576px", numVisible: 1, numScroll: 1 },
]);

const formatDate = (dateString) => {
  if (!dateString) return "Date not specified";

  const options = {
    hour: "2-digit",
    minute: "2-digit",
    weekday: "long",
    timeZone: "UTC",
  };

  try {
    const date = new Date(dateString);
    return `Start on: ${date.toLocaleTimeString("en-US", options)}`;
  } catch (e) {
    return dateString;
  }
};

const getDetailPage = (slug) => {
  window.location.href = `/events/${slug}`;
};

onMounted(async () => {
  const options = {
    method: "GET",
    url: "/api/events",
    headers: {
      Accept: "application/json",
      Authorization: "Bearer 123",
    },
  };

  try {
    const response = await axios.request(options);
    const photoArray = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    // Get current date (without time)
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    slides.value = photoArray
      .map((photo) => ({
        imageUrl: photo.image_url
          ? photo.image_url.startsWith("http")
            ? photo.image_url
            : `/storage/${photo.image_url.replace(/^public\//, "")}`
          : "/js/Assets/default-photo.jpg",
        title: photo.title || "Untitled Event",
        description: photo.description || "No description available",
        date_start: photo.date_start,
        date_end: photo.date_end,
        altText: photo.alt_text || photo.title || "Event image",
        slug: photo.slug,
      }))
      // Filter events that are today or in the future
      .filter((event) => {
        const eventDate = new Date(event.date_start);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate >= today;
      })
      // Sort by date (soonest first)
      .sort((a, b) => new Date(a.date_start) - new Date(b.date_start));

    console.log("Filtered Slides:", slides.value);
  } catch (err) {
    console.error("Failed to fetch events:", err);
    error.value = "Failed to load events";
  } finally {
    loading.value = false;
  }
});
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&family=Poppins:wght@300;400&display=swap");
</style>
