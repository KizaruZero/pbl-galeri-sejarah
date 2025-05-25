<template>
    <MainLayout>
        <div class="min-h-screen bg-black mt-8">

                <!-- Loading state -->
                <div v-if="loading" class="text-center text-white">
                    Loading event details...
                </div>

                <!-- Error state -->
                <div v-else-if="error" class="text-center text-red-500">
                    {{ error }}
                </div>

                <!-- Content -->
                <div v-else class="card">
                    <div class="max-w-full mx-auto">
                        <div class="relative">
                            <!-- Back Button -->
                            <button @click="goBack"
                                class="absolute top-4 left-4 z-10 p-2 bg-black/50 rounded-full hover:bg-black/75 transition-colors mt-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </button>

                            <img :src="event.imageUrl" :alt="event.altText"
                                class="w-full h-auto max-h-[70vh] object-contain bg-gray-950" />
                        </div>
                    </div>
                </div>

                <!-- Event Info -->
                <div class="p-6 ml-24 mr-24">
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-2xl font-bold text-white">
                            {{ event.title }}
                        </h1>
                        <div class="flex space-x-4">
                            <!-- Like Button -->
                            <button @click="toggleLike"
                                class="flex items-center space-x-1 text-gray-600 hover:text-red-500"
                                :class="{ 'text-red-500': isLiked }">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="
                                                isLiked
                                                    ? 'currentColor'
                                                    : 'none'
                                            " viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="text-white">{{
                                            likeCount
                                        }}</span>
                            </button>

                            <!-- Bookmark Button -->
                            <button @click="toggleBookmark"
                                class="flex items-center space-x-1 text-gray-600 hover:text-blue-500" :class="{
                                            'text-blue-500': isBookmarked,
                                        }">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="
                                                isBookmarked
                                                    ? 'currentColor'
                                                    : 'none'
                                            " viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Event Dates -->
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-300">
                            {{ formatDate(event.date_start) }}
                            <span v-if="event.date_end">
                                - {{ formatDate(event.date_end) }}</span>
                        </span>
                    </div>

                    <!-- Event Location -->
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-gray-300">{{
                                    event.location
                                }}</span>
                        <a v-if="event.googleMapsUrl" :href="event.googleMapsUrl" target="_blank"
                            class="ml-2 text-blue-400 hover:underline">
                            (View on Map)
                        </a>
                    </div>

                    <!-- Contact Person -->
                    <div v-if="event.contactPerson" class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-gray-300">Contact: {{ event.contactPerson }}</span>
                    </div>

                    <!-- Social Links -->
                    <div v-if="
                                    event.instagramUrl ||
                                    event.youtubeUrl ||
                                    event.websiteUrl
                                " class="flex flex-wrap gap-4 mb-6">
                        <a v-if="event.instagramUrl" :href="event.instagramUrl" target="_blank"
                            class="flex items-center text-pink-500 hover:text-pink-400">
                            <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                            Instagram
                        </a>
                        <a v-if="event.youtubeUrl" :href="event.youtubeUrl" target="_blank"
                            class="flex items-center text-red-500 hover:text-red-400">
                            <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                            </svg>
                            YouTube
                        </a>
                        <a v-if="event.websiteUrl" :href="event.websiteUrl" target="_blank"
                            class="flex items-center text-blue-400 hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            Website
                        </a>
                    </div>

                    <!-- Event Description -->
                    <p class="text-gray-200 mb-6 whitespace-pre-line">
                        {{ event.description }}
                    </p>
                </div>
            </div>
    </MainLayout>
</template>

<script setup>
    import MainLayout from "@/Layouts/MainLayout.vue";
    import {
        ref,
        onMounted
    } from "vue";
    import axios from "axios";

    const event = ref({
        title: "",
        description: "",
        date_start: "",
        date_end: "",
        location: "",
        imageUrl: "",
        instagramUrl: "",
        youtubeUrl: "",
        websiteUrl: "",
        contactPerson: "",
        googleMapsUrl: "",
    });
    const loading = ref(true);
    const handleImageError = (e) => {
        e.target.src = "/default-event.jpg"; // Path ke gambar default
    };
    const error = ref(null);
    const isLiked = ref(false);
    const likeCount = ref(0);
    const isBookmarked = ref(false);
    const comments = ref([]);
    const newComment = ref("");
    const slug = window.location.pathname.split("/").pop();

    const goBack = () => {
        window.history.back();
    };

    // Format date
    const formatDate = (dateString) => {
        if (!dateString) return "No date specified";

        const options = {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    // Toggle like
    const toggleLike = () => {
        isLiked.value = !isLiked.value;
        likeCount.value += isLiked.value ? 1 : -1;
    };

    // Toggle bookmark
    const toggleBookmark = () => {
        isBookmarked.value = !isBookmarked.value;
    };

    // Add comment
    const addComment = () => {
        if (newComment.value.trim()) {
            comments.value.unshift({
                id: Date.now(),
                user: "You",
                text: newComment.value,
                date: new Date().toISOString(),
                canDelete: true,
            });
            newComment.value = "";
        }
    };

    // Delete comment
    const deleteComment = (id) => {
        comments.value = comments.value.filter((comment) => comment.id !== id);
    };

    // Fetch event data
    onMounted(async () => {
        try {
            console.log("Fetching event with slug:", slug);
            const response = await axios.get(`/api/events/${slug}`, {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer 123",
                },
            });

            const eventData = response.data.data || response.data;

            // Map the API response to our component's expected structure
            event.value = {
                title: eventData.title || "Untitled Event",
                description: eventData.description || "No description available",
                date_start: eventData.date_start || null,
                date_end: eventData.date_end || null,
                location: eventData.location || "Location not specified",
                imageUrl: eventData.image_url ?
                    eventData.image_url.startsWith("http") ?
                    eventData.image_url :
                    `/storage/${eventData.image_url.replace(/^public\//, "")}` : "/default-event.jpg",
                instagramUrl: eventData.instagram_url || "",
                youtubeUrl: eventData.youtube_url || "",
                websiteUrl: eventData.website_url || "",
                contactPerson: eventData.contact_person || "",
                googleMapsUrl: eventData.google_maps_url || "",
            };

            // Set dummy data for demo
            likeCount.value = Math.floor(Math.random() * 100);
            isLiked.value = Math.random() > 0.5;
            isBookmarked.value = Math.random() > 0.5;

            // Set dummy comments
            comments.value = [{
                    id: 1,
                    user: "Event Attendee",
                    text: "This was an amazing event! The organization was perfect.",
                    date: "2023-05-15T10:30:00Z",
                    canDelete: false,
                },
                {
                    id: 2,
                    user: "Another Guest",
                    text: "Looking forward to the next edition of this event!",
                    date: "2023-05-14T16:45:00Z",
                    canDelete: false,
                },
            ];
        } catch (err) {
            console.error("Error fetching event:", err);
            error.value = "Failed to load event details. Please try again later.";
        } finally {
            loading.value = false;
        }
    });

</script>

<style scoped>
    /* Animations for buttons */
    button:active svg {
        transform: scale(1.2);
        transition: transform 0.2s ease;
    }

    /* Ensure images don't overflow */
    img {
        max-width: 100%;
        height: auto;
    }

    .rounded-image {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 15px;
    }

    /* Style for social links */
    .social-link {
        transition: all 0.2s ease;
    }

    .social-link:hover {
        transform: translateY(-1px);
    }

</style>
