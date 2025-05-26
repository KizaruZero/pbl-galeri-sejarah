<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Highlight Events
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Grid Card -->
            <div
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
            >
                <EventCard
                    v-for="photo in photos"
                    :key="photo.slug"
                    :imageUrl="photo.imageUrl"
                    :title="photo.title"
                    :description="
                        photo.description || 'No description available'
                    "
                    :location="photo.location || 'No location available'"
                    :date_start="photo.date_start || 'No date available'"
                    :date_end="photo.date_end || 'No date available'"
                    :instagramUrl="photo.instagramUrl"
                    :youtubeUrl="photo.youtubeUrl"
                    :websiteUrl="photo.websiteUrl"
                    :contactPerson="photo.contactPerson"
                    :googleMapsUrl="photo.googleMapsUrl"
                    :titleSize="'lg'"
                    @click="getDetailPage(photo.slug)"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import EventCard from "./EventCard.vue";
import axios from "axios";

const photos = ref([]);
const loading = ref(true);
const error = ref(null);
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

        // Get current date
        const currentDate = new Date();

        // Filter and map the events
        photos.value = photoArray
            .filter(photo => {
                // Convert date_start to Date object
                const startDate = new Date(photo.date_start);
                // Only include events where start date is in the future
                return startDate >= currentDate;
            })
            // Add sorting here
            .sort((a, b) => new Date(a.date_start) - new Date(b.date_start))
            .map((photo) => ({
                imageUrl: photo.image_url
                    ? photo.image_url.startsWith("http")
                        ? photo.image_url
                        : `/storage/${photo.image_url.replace(/^public\//, "")}`
                    : "/js/Assets/default-photo.jpg",
                title: photo.title || "Untitled",
                titleSize: "base",
                description: photo.description || "No description available",
                slug: photo.slug,
                date_start: photo.date_start,
                date_end: photo.date_end,
                instagramUrl: photo.instagram_url,
                youtubeUrl: photo.youtube_url,
                websiteUrl: photo.website_url,
                contactPerson: photo.contact_person,
                location: photo.location,
                googleMapsUrl: photo.google_maps_url,
                altText: photo.alt_text || "",
                tags: photo.tags || [],
            }));
    } catch (err) {
        if (err.response && err.response.status === 404) {
            error.value = "Event tidak ditemukan";
        } else {
            error.value = "Gagal mengambil data";
        }
        console.error("Error:", err);
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
