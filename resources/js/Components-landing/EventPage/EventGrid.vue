<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Title Section -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
                    Highlight Events
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Events Grid -->
            <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <EventCard v-for="event in paginatedEvents" :key="event.slug" :imageUrl="event.imageUrl"
                    :title="event.title" :description="event.description" :location="event.location"
                    :date_start="event.date_start" :date_end="event.date_end" :instagramUrl="event.instagramUrl"
                    :youtubeUrl="event.youtubeUrl" :websiteUrl="event.websiteUrl" :contact_person="event.contactPerson"
                    :googleMapsUrl="event.googleMapsUrl" :is_upcoming="event.isUpcoming"
                    @click="navigateToEvent(event.slug)" />
            </div>
            <!-- Pagination -->
            <div v-if="!loading && !error && events.length > 0" class="flex justify-center mt-8 gap-2">
                <button @click="currentPage--" :disabled="currentPage === 1"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300">
                    Previous
                </button>
                <div class="flex items-center px-4 text-white">
                    Page {{ currentPage }} of {{ totalPages }}
                </div>
                <button @click="currentPage++" :disabled="currentPage >= totalPages"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300">
                    Next
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
    import {
        ref,
        onMounted,
        computed
    } from 'vue';
    import EventCard from './EventCard.vue';
    import axios from 'axios';

    const events = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const currentPage = ref(1);
    const itemsPerPage = 9;

    const navigateToEvent = (slug) => {
        window.location.href = `/events/${slug}`;
    };

    // Computed property for paginated event
    const paginatedEvents = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return events.value.slice(start, end);
    });

    // Computed property for total pages
    const totalPages = computed(() => {
        return Math.ceil(events.value.length / itemsPerPage);
    });

    const getDateStatus = (dateString) => {
        if (!dateString) return false;
        const eventEndDate = new Date(dateString);
        const today = new Date();
        return eventEndDate > today;
    };

    onMounted(async () => {
        try {
            const response = await axios.get('/api/events', {
                headers: {
                    Accept: 'application/json',
                    Authorization: 'Bearer 123'
                }
            });

            const eventData = Array.isArray(response.data) ?
                response.data :
                response.data.data || [];

            events.value = eventData.map(event => ({
                imageUrl: event.image_url ?
                    event.image_url.startsWith('http') ?
                    event.image_url :
                    `/storage/${event.image_url.replace(/^public\//, '')}` :
                    '/js/Assets/default-photo.jpg',
                title: event.title || 'Untitled',
                description: event.description || 'No description available',
                slug: event.slug,
                date_start: event.date_start,
                date_end: event.date_end,
                instagramUrl: event.instagram_url,
                youtubeUrl: event.youtube_url,
                websiteUrl: event.website_url,
                contactPerson: event.contact_person,
                location: event.location || 'No location available',
                googleMapsUrl: event.google_maps_url,
                isUpcoming: getDateStatus(event.date_end) // Changed from date_start to date_end
            })).sort((a, b) => {
                const dateA = new Date(a.date_end); // Changed from date_start to date_end
                const dateB = new Date(b.date_end); // Changed from date_start to date_end
                const now = new Date();

                // Future events first (closest to ending first)
                if (dateA > now && dateB > now) {
                    return dateA - dateB;
                }
                // Past events (most recently ended first)
                if (dateA <= now && dateB <= now) {
                    return dateB - dateA;
                }
                // Future before past
                return dateA > now ? -1 : 1;
            });

        } catch (err) {
            error.value = err.response ?.status === 404 ?
                'Event tidak ditemukan' :
                'Gagal mengambil data';
            console.error('Error fetching events:', err);
        } finally {
            loading.value = false;
        }
    });

</script>
