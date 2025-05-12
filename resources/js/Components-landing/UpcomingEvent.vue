<template>
    <main class="relative p-5 bg-black text-white">
      <!-- Title & Button -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-5 md:px-20 mt-10">
        <h1 class="text-4xl md:text-5xl font-serif text-center md:text-left relative">
          <span class="block w-24 h-0.5 bg-white mb-6 md:mb-8 mx-auto md:mx-0"></span>
          Upcoming Events
          <span class="block w-full h-0.5 bg-white mt-6 md:mt-8"></span>
        </h1>
        <Link href="/events" class="flex items-center justify-center w-[60px] h-[60px] md:w-[70px] md:h-[70px] rounded-full border border-white text-white text-base md:text-lg transition-all duration-300 hover:scale-105 hover:bg-white hover:text-black"
        >View All
        </Link>
      </div>

      <!-- Carousel -->
      <div class="mt-16 pb-20">
        <Carousel
          v-if="!loading && slides.length > 0"
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
              class="relative mx-4 bg-zinc-900 rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-[1.02] flex flex-col h-[450px] cursor-pointer" 
              @click="getDetailPage(slotProps.data.slug)"
            >
              <!-- Image section (60%) -->
              <div class="relative w-full h-[300px] bg-black">
                <img
                  :src="slotProps.data.imageUrl"
                  :alt="slotProps.data.altText || slotProps.data.title"
                  class="object-cover w-full h-full transition-opacity duration-300"
                />
                <div class="absolute inset-0 bg-black bg-opacity-60"></div>
              </div>

              <!-- Content section (40%) -->
              <div class="px-4 mt-4 flex flex-col gap-2 h-[150px]">
                <h2 class="text-base font-bold uppercase line-clamp-1">
                  {{ slotProps.data.title }}
                </h2>
                <time class="text-xs uppercase">
                  {{ formatDate(slotProps.data.date_start) }}
                </time>
                <p class="text-xs font-light tracking-wide leading-snug text-justify line-clamp-2">
                  {{ slotProps.data.description || "No description available" }}
                </p>
              </div>
            </div>
          </template>
        </Carousel>

        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center items-center h-64">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>

        <!-- Empty state -->
        <div v-if="!loading && slides.length === 0" class="text-center py-20">
          <p class="text-xl">No upcoming events found</p>
        </div>
      </div>
    </main>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Link } from '@inertiajs/vue3';
import axios from "axios";

const slides = ref([]);
const loading = ref(true);
const error = ref(null);


const responsiveOptions = ref([
  { breakpoint: "1400px", numVisible: 2, numScroll: 1 },
  { breakpoint: "1024px", numVisible: 1, numScroll: 1 },
  { breakpoint: "768px", numVisible: 1, numScroll: 1 },
  { breakpoint: "576px", numVisible: 1, numScroll: 1 },
]);

const formatDate = (dateString) => {
  if (!dateString) return "Date not specified";

  const options = {
    hour: '2-digit',
    minute: '2-digit',
    weekday: 'long',
    timeZone: 'UTC'
  };

  try {
    const date = new Date(dateString);
    return `Start on: ${date.toLocaleTimeString('en-US', options)}`;
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
                    : "/default-photo.jpg",
                title: photo.title || "Untitled Event",
                description: photo.description || "No description available",
                date_start: photo.date_start,
                date_end: photo.date_end,
                altText: photo.alt_text || photo.title || "Event image",
                slug: photo.slug,
            }))
            // Filter events that are today or in the future
            .filter(event => {
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