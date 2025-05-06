<template>
    <MainLayout>
        <div class="min-h-screen bg-black py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Back Button -->
                <button @click="$router.go(-1)"
                    class="mb-6 flex items-center text-gray-400 hover:text-blue-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Gallery
                </button>

                <!-- Main Video Container -->
                <div class="bg-gray-900 rounded-lg shadow-xl overflow-hidden border border-gray-800">
                    <!-- Video Player -->
                    <div class="relative pt-[56.25%] bg-black">
                        <video v-if="isLocalVideo" controls :poster="video.thumbnailUrl"
                            class="absolute inset-0 w-full h-full object-contain">
                            <source :src="video.video_url" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>

                        <iframe v-else class="absolute inset-0 w-full h-full" :src="video.video_url" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Video Info Section -->
                    <div class="p-6">
                        <!-- Title and Actions -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-white">{{ video.title }}</h1>
                                <div class="flex items-center mt-2 space-x-4 text-sm text-gray-400">
                                    <span>{{ video.views }} views</span>
                                    <span>{{ formatDate(video.created_at) }}</span>
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <!-- Like Button -->
                                <button @click="toggleLike" class="flex items-center space-x-1 group">
                                    <div class="p-1 rounded-full group-hover:bg-red-500/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                            :class="isLiked ? 'text-red-500 fill-current' : 'text-gray-400 group-hover:text-red-500'"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                    <span :class="isLiked ? 'text-red-500' : 'text-gray-400 group-hover:text-red-500'">
                                        {{ likeCount }}
                                    </span>
                                </button>

                                <!-- Bookmark Button -->
                                <button @click="toggleBookmark" class="flex items-center group">
                                    <div class="p-1 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                            :class="isBookmarked ? 'text-blue-500 fill-current' : 'text-gray-400 group-hover:text-blue-500'"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Creator Info -->
                        <div class="flex items-center gap-3 mb-6 p-3 bg-gray-800 rounded-lg">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white/20">
                                <img :src="video.user?.avatar" :alt="video.user?.name"
                                    class="w-full h-full object-cover" </div> <div>
                                <p class="text-white font-medium">{{ video.user?.name || 'Unknown Creator' }}</p>
                                <p class="text-gray-300 text-xs">Content Creator</p>
                            </div>
                            <button
                                class="ml-auto px-4 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                Follow
                            </button>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <p class="text-gray-300 whitespace-pre-line">{{ video.description }}</p>
                        </div>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mb-6" v-if="video.tags && video.tags.length">
                            <router-link v-for="tag in video.tags" :key="tag" :to="`/tags/${tag.toLowerCase()}`"
                                class="px-3 py-1 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-full text-sm transition-colors">
                                #{{ tag }}
                            </router-link>
                        </div>

                        <!-- Comments Section -->
                        <div class="border-t border-gray-800 pt-6">
                            <h2 class="text-xl font-semibold text-white mb-4">Comments ({{ comments.length }})</h2>

                            <!-- New Comment Form -->
                            <div class="mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-700 mt-1">
                                        <img :src="currentUser.avatar" :alt="currentUser.name"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <textarea v-model="newComment" placeholder="Add a comment..."
                                            class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                            rows="3"></textarea>
                                        <button @click="addComment" :disabled="!newComment.trim()"
                                            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed transition-colors">
                                            Post Comment
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Comments List -->
                            <div class="space-y-4">
                                <div v-for="comment in comments" :key="comment.id" class="bg-gray-800 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-700">
                                                <img :src="comment.user.avatar" :alt="comment.user.name"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ comment.user.name }}</p>
                                                <p class="text-gray-400 text-xs">{{ formatRelativeDate(comment.date) }}
                                                </p>
                                            </div>
                                        </div>
                                        <button v-if="comment.canDelete" @click="deleteComment(comment.id)"
                                            class="text-gray-400 hover:text-red-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-3 text-gray-300 pl-11">{{ comment.text }}</p>
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
    import MainLayout from '@/Layouts/MainLayout.vue';
    import {
        ref,
        computed,
        onMounted
    } from 'vue';
    import {
        useRoute,
        useRouter
    } from 'vue-router';
    import axios from 'axios';

    const route = useRoute();
    const router = useRouter();

    const video = ref({
        title: '',
        description: '',
        video_url: '',
        thumbnailUrl: '',
        duration: '00:00',
        views: 0,
        tags: [],
        user: null,
        created_at: null
    });

    const isLiked = ref(false);
    const likeCount = ref(0);
    const isBookmarked = ref(false);
    const comments = ref([]);
    const newComment = ref('');
    const loading = ref(true);

    const currentUser = ref({
        id: 1,
        name: 'You',
        avatar: ''
    });

    const isLocalVideo = computed(() => {
        return !video.value.video_url.includes('youtube.com') &&
            !video.value.video_url.includes('vimeo.com');
    });

    const formatDate = (dateString) => {
        if (!dateString) return '';
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const formatRelativeDate = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) return interval + " year" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) return interval + " month" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + " day" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " hour" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " minute" + (interval === 1 ? "" : "s") + " ago";

        return Math.floor(seconds) + " second" + (seconds === 1 ? "" : "s") + " ago";
    };

    const toggleLike = () => {
        isLiked.value = !isLiked.value;
        likeCount.value += isLiked.value ? 1 : -1;
    };

    const toggleBookmark = () => {
        isBookmarked.value = !isBookmarked.value;
    };

    const addComment = () => {
        if (newComment.value.trim()) {
            comments.value.unshift({
                id: Date.now(),
                user: {
                    ...currentUser.value
                },
                text: newComment.value,
                date: new Date().toISOString(),
                canDelete: true
            });
            newComment.value = '';
        }
    };

    const deleteComment = (id) => {
        comments.value = comments.value.filter(comment => comment.id !== id);
    };

    onMounted(async () => {
        try {
            const slug = window.location.pathname.split('/').pop();
            const response = await axios.get(
                `http://127.0.0.1:8000/api/content-video/${slug}`, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer 123'
                    }
                }
            );

            const videoData = response.data;

            video.value = {
                ...videoData,
                video_url: videoData.video_url ?
                    `/storage/${videoData.video_url.replace(/^public\//, '')}` :
                    convertToEmbedUrl(videoData.link_youtube) || '',
                thumbnailUrl: videoData.thumbnail ?
                    `/storage/${videoData.thumbnail.replace(/^public\//, '')}` :
                    '/default-thumbnail.jpg',
                tags: videoData.tag ? videoData.tag.split(/,\s*/) : [],
                user: videoData.user || null,
                created_at: videoData.created_at
            };

            // Set dummy data
            likeCount.value = Math.floor(Math.random() * 100);
            isLiked.value = Math.random() > 0.5;
            isBookmarked.value = Math.random() > 0.5;

            // Set dummy comments
            comments.value = [{
                    id: 1,
                    user: {
                        id: 2,
                        name: 'John Doe',
                        avatar: '/default-avatar.jpg'
                    },
                    text: 'This is an amazing video! The editing is fantastic.',
                    date: '2023-05-15T10:30:00Z',
                    canDelete: false
                },
                {
                    id: 2,
                    user: {
                        id: 3,
                        name: 'Jane Smith',
                        avatar: '/default-avatar.jpg'
                    },
                    text: 'Great content and production quality!',
                    date: '2023-05-14T16:45:00Z',
                    canDelete: false
                }
            ];

        } catch (error) {
            console.error('Error fetching video:', error);
            router.push('/404');
        } finally {
            loading.value = false;
        }
    });

    function convertToEmbedUrl(url) {
        if (!url) return null;
        if (url.includes('youtube.com/watch')) {
            const videoId = url.split('v=')[1] ?.split('&')[0];
            return videoId ? `https://www.youtube.com/embed/${videoId}` : null;
        }
        return url;
    }

</script>

<style scoped>
    /* Smooth transitions for interactive elements */
    button {
        transition: all 0.2s ease;
    }

    button:active {
        transform: scale(0.95);
    }

    textarea {
        resize: none;
    }

</style>
