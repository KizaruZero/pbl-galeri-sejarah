<template>
    <MainLayout>
        <div class="min-h-screen bg-black py-12 px-4 sm:px-6 lg:px-8 mt-12">
            <div class="max-w-4xl mx-auto mt-6">
                <!-- Back button -->
                <button @click="$router.go(-1)" class="mb-6 flex items-center text-gray-600 hover:text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back
                </button>

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
                    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <!-- Event Image -->
                        <img :src="event.imageUrl" :alt="event.title" class="w-full h-auto max-h-[70vh] rounded-image object-contain mt-6">

                        <!-- Event Info -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h1 class="text-2xl font-bold text-white">{{ event.title }}</h1>
                                <div class="flex space-x-4">
                                    <!-- Like Button -->
                                    <button @click="toggleLike"
                                        class="flex items-center space-x-1 text-gray-600 hover:text-red-500"
                                        :class="{ 'text-red-500': isLiked }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                            :fill="isLiked ? 'currentColor' : 'none'" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span class="text-white">{{ likeCount }}</span>
                                    </button>

                                    <!-- Bookmark Button -->
                                    <button @click="toggleBookmark"
                                        class="flex items-center space-x-1 text-gray-600 hover:text-blue-500"
                                        :class="{ 'text-blue-500': isBookmarked }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                            :fill="isBookmarked ? 'currentColor' : 'none'" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Event Date -->
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-300">{{ formatDate(event.date) }}</span>
                            </div>

                            <!-- Event Location -->
                            <div class="flex items-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-gray-300">{{ event.location }}</span>
                            </div>

                            <!-- Event Description -->
                            <p class="text-gray-200 mb-6 whitespace-pre-line">{{ event.description }}</p>

                            <!-- Comments Section -->
                            <div class="border-t border-gray-700 pt-6">
                                <h2 class="text-xl font-semibold text-white mb-4">Comments ({{ comments.length }})</h2>

                                <!-- New Comment Form -->
                                <div class="mb-6">
                                    <textarea v-model="newComment" placeholder="Add a comment..."
                                        class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        rows="3"></textarea>
                                    <button @click="addComment" :disabled="!newComment.trim()"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-blue-300">
                                        Post Comment
                                    </button>
                                </div>

                                <!-- Comments List -->
                                <div class="space-y-4">
                                    <div v-for="comment in comments" :key="comment.id"
                                        class="bg-gray-700 p-4 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-white">{{ comment.user }}</p>
                                                <p class="text-gray-400 text-sm">{{ formatDate(comment.date) }}</p>
                                            </div>
                                            <button v-if="comment.canDelete" @click="deleteComment(comment.id)"
                                                class="text-gray-400 hover:text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-2 text-gray-200">{{ comment.text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue'
    import {
        ref,
        onMounted
    } from 'vue';
    import {
        useRoute
    } from 'vue-router';
    import axios from 'axios';

    const route = useRoute();
    const event = ref({
        title: '',
        description: '',
        date: '',
        location: '',
        imageUrl: ''
    });
    const loading = ref(true);
    const error = ref(null);
    const isLiked = ref(false);
    const likeCount = ref(0);
    const isBookmarked = ref(false);
    const comments = ref([]);
    const newComment = ref('');
    const slug = window.location.pathname.split('/').pop(); // ambil slug dari URL
    // Format date
    const formatDate = (dateString) => {
        if (!dateString) return 'No date specified';

        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
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
                user: 'You',
                text: newComment.value,
                date: new Date().toISOString(),
                canDelete: true
            });
            newComment.value = '';
        }
    };

    // Delete comment
    const deleteComment = (id) => {
        comments.value = comments.value.filter(comment => comment.id !== id);
    };

    // Fetch event data
    onMounted(async () => {
        try {
            const response = await axios.get(
                `http://127.0.0.1:8000/api/events/${slug}`, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer 123'
                    }
                }
            );

            const eventData = response.data.data || response.data;

            // Map the API response to our component's expected structure
            event.value = {
                title: eventData.title || 'Untitled Event',
                description: eventData.description || 'No description available',
                date: eventData.date || null,
                location: eventData.location || 'Location not specified',
                imageUrl: eventData.image_url ?
                    (eventData.image_url.startsWith('http') ?
                        eventData.image_url :
                        `/storage/${eventData.image_url.replace(/^public\//, '')}`) :
                    '/default-event.jpg'
            };

            // Set dummy data for demo
            likeCount.value = Math.floor(Math.random() * 100);
            isLiked.value = Math.random() > 0.5;
            isBookmarked.value = Math.random() > 0.5;

            // Set dummy comments
            comments.value = [{
                    id: 1,
                    user: 'Event Attendee',
                    text: 'This was an amazing event! The organization was perfect.',
                    date: '2023-05-15T10:30:00Z',
                    canDelete: false
                },
                {
                    id: 2,
                    user: 'Another Guest',
                    text: 'Looking forward to the next edition of this event!',
                    date: '2023-05-14T16:45:00Z',
                    canDelete: false
                }
            ];
        } catch (err) {
            console.error('Error fetching event:', err);
            error.value = 'Failed to load event details. Please try again later.';
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
</style>
