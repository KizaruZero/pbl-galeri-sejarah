<template>
    <MainLayout>
        <div class="min-h-screen bg-black">
            <div class="text-white">
                <!-- Back Button - Adjusted padding for mobile -->
                <div class="p-4 md:p-6">
                    <button @click="goBack" class="flex items-center text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        BACK
                    </button>
                </div>

                <!-- Main Content -->
                <div class="max-w-full mx-auto">
                    <div class="relative">
                        <img :src="photo.imageUrl" :alt="photo.altText"
                            class="w-full h-auto max-h-[60vh] md:max-h-[70vh] object-contain bg-gray-950" />

                        <!-- User Info Overlay - Adjusted padding for mobile -->
                        <div
                            class="absolute bottom-0 left-0 right-0 p-3 md:p-4 bg-gradient-to-t from-black/80 to-transparent">
                            <div class="flex items-center gap-2 md:gap-3">
                                <div
                                    class="w-8 h-8 md:w-10 md:h-10 rounded-full overflow-hidden border-2 border-white/20">
                                    <img :src="getMediaUrl(photo.user?.photo_profile)" :alt="photo.user?.name"
                                        class="w-full h-full object-cover" @error="handleAvatarError" />
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm md:text-base">
                                        {{ photo.user?.name || "Unknown Photographer" }}
                                    </p>
                                    <p class="text-gray-300 text-xs" v-if="photo.created_at">
                                        Uploaded {{ formatRelativeDate(photo.created_at) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Details - Adjusted margins and padding for mobile -->
            <div class="p-4 md:ml-24 md:mr-24">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-white">
                            {{ photo.title }}
                        </h1>
                        <p v-if="photo.source" class="text-gray-400 text-xs md:text-sm mt-1">
                            Source: {{ photo.source }}
                        </p>
                    </div>
                    <div class="flex space-x-2 md:space-x-4">
                        <!-- Like Button -->
                        <button @click="toggleLike" class="flex items-center space-x-1 group">
                            <div class="p-1 rounded-full group-hover:bg-red-500/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" :class="
                                        isLiked
                                            ? 'text-red-500 fill-current'
                                            : 'text-gray-400 group-hover:text-red-500'
                                    " viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span :class="
                                    isLiked
                                        ? 'text-red-500'
                                        : 'text-gray-400 group-hover:text-red-500'
                                " class="text-xs md:text-base">
                                {{ likeCount }}
                            </span>
                        </button>

                        <!-- Bookmark Button -->
                        <button @click="toggleBookmark" :disabled="bookmarkLoading"
                            class="flex items-center group disabled:opacity-50">
                            <div class="p-1 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" :class="
                                        isBookmarked
                                            ? 'text-blue-500 fill-current'
                                            : 'text-gray-400 group-hover:text-blue-500'
                                    " viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-gray-300 mb-6 whitespace-pre-line text-sm md:text-base">
                    {{ photo.description }}
                </p>
                <div class="border-t border-gray-800 pt-6" />

                <!-- Photo Details - Changed grid layout for mobile -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">Collection Date</h3>
                        <p class="text-white text-sm md:text-base">{{ photo.collection_date || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">Location</h3>
                        <p class="text-white text-sm md:text-base">{{ photo.location || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">Model Camera</h3>
                        <p class="text-white text-sm md:text-base">{{ photo.camera_model || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">Dimensions</h3>
                        <p class="text-white text-sm md:text-base">{{ photo.dimensions || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">File Size</h3>
                        <p class="text-white text-sm md:text-base">
                            {{ photo.file_size ? formatFileSize(parseInt(photo.file_size)) : 'Not specified' }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">Aperture</h3>
                        <p class="text-white text-sm md:text-base">{{ photo.aperture || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-xs md:text-sm">ISO</h3>
                        <p class="text-white text-sm md:text-base">{{ photo.ISO || 'Not specified' }}</p>
                    </div>
                </div>

                <!-- Tags - Adjusted size for mobile -->
                <div class="flex flex-wrap gap-2 mb-6" v-if="photo.tags && photo.tags.length">
                    <router-link v-for="tag in photo.tags" :key="tag" :to="`/tags/${tag.toLowerCase()}`"
                        class="px-2 py-0.5 md:px-3 md:py-1 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-full text-xs md:text-sm transition-colors">
                        #{{ tag }}
                    </router-link>
                </div>

                <!-- Photo Stats - Adjusted text size for mobile -->
                <div class="flex items-center gap-3 md:gap-4 text-xs md:text-sm text-gray-400 mb-6">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ photo.total_views || 0 }} views</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>{{ comments.length }} comments</span>
                    </div>
                </div>

                <!-- Comments Section - Improved spacing -->
                <div class="border-t border-gray-800 pt-5 sm:pt-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-5">
                        Comments ({{ comments.length }})
                    </h2>

                    <!-- New Comment Form - Better mobile layout -->
                    <div class="mb-6 sm:mb-7" v-if="UserId">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-full overflow-hidden bg-gray-700">
                                <img :src="currentUser.photo_profile" :alt="currentUser.name"
                                    class="w-full h-full object-cover" @error="handleAvatarError" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="relative">
                                    <textarea v-model="newComment" placeholder="Add a comment..."
                                        class="w-full px-3 py-2 sm:px-4 sm:py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder-gray-400 text-sm sm:text-base"
                                        rows="3" maxlength="500"></textarea>
                                    <div class="absolute bottom-2 right-2 sm:bottom-3 sm:right-3 flex items-center">
                                        <span
                                            class="text-xs text-gray-400 mr-2 sm:mr-3">{{ newComment.length }}/500</span>
                                        <button @click="addComment" :disabled="!newComment.trim()"
                                            class="px-3 py-1 sm:px-4 sm:py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-600 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors text-xs sm:text-sm font-medium">
                                            Post
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-3">
                                    <div v-if="!loadingReactions" class="p-3 bg-gray-800 rounded-lg">
                                        <span class="text-xs text-gray-400">Add reaction:</span>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            <button v-for="reaction in reactions" :key="reaction.id"
                                                class="px-2 py-1 bg-gray-700 rounded-full text-xs sm:text-sm hover:scale-110 transform transition-transform"
                                                @click="newComment += reaction.icon">
                                                {{ reaction.icon }}
                                            </button>
                                        </div>
                                    </div>
                                    <div v-else class="p-3 bg-gray-800 rounded-lg">
                                        <p class="text-gray-400 text-sm">Loading reactions...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else
                        class="mb-6 sm:mb-7 p-3 sm:p-4 bg-gray-800/50 rounded-lg text-center border border-gray-700">
                        <p class="text-gray-300 text-sm sm:text-base">
                            Please
                            <router-link to="/login" class="text-blue-400 hover:underline font-medium">login
                            </router-link>
                            to post a comment.
                        </p>
                    </div>

                    <!-- Comments List - Better spacing and transitions -->
                    <div class="space-y-4 sm:space-y-5">
                        <!-- Loading state -->
                        <div v-for="comment in comments" :key="comment.id" class="group">
                            <div v-if="comment.isLoading"
                                class="p-3 sm:p-4 bg-gray-800/50 rounded-lg flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-700 animate-pulse"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-3 bg-gray-700 rounded w-1/3 animate-pulse"></div>
                                    <div class="h-2 bg-gray-700 rounded w-2/3 animate-pulse"></div>
                                </div>
                            </div>

                            <!-- Actual comment -->
                            <div v-else
                                :class="[
                                    'p-3 sm:p-4 rounded-lg hover:bg-gray-800/70 transition-colors border border-gray-700',
                                    getCommentStatusClass(comment.status)
                                ]"
                            >
                                <!-- Add status indicator for user's own hidden comments -->
                                <div v-if="comment.status === 'hidden' && comment.user.id === UserId" 
                                    class="text-yellow-500 text-xs mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Your comment is pending moderation</span>
                                </div>
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                        <router-link :to="`/users/${comment.user.id}`" class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden bg-gray-700">
                                                <img :src="getMediaUrl(comment.user?.photo_profile)"
                                                    :alt="comment.user?.name" class="w-full h-full object-cover"
                                                    @error="handleAvatarError" />
                                            </div>
                                        </router-link>
                                        <div class="min-w-0">
                                            <router-link :to="`/users/${comment.user.id}`"
                                                class="font-medium text-white hover:underline text-sm sm:text-base truncate">
                                                {{ comment.user.name }}
                                            </router-link>
                                            <p class="text-gray-400 text-xs mt-0.5">
                                                {{ formatRelativeDate(comment.date) }}
                                            </p>
                                        </div>
                                    </div>
                                    <button v-if="comment.canDelete" @click="deleteComment(comment.id)"
                                        class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all p-1 rounded-full hover:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <p
                                    class="mt-2 sm:mt-3 text-gray-200 pl-10 sm:pl-12 whitespace-pre-line text-sm sm:text-base break-words">
                                    {{ comment.text }}
                                </p>

                                <!-- Comment Reactions - Improved mobile layout -->
                                <div class="mt-2 sm:mt-3 pl-10 sm:pl-12 flex flex-wrap items-center gap-2 sm:gap-3">
                                    <!-- Reaction buttons -->
                                    <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                                        <button v-for="reaction in comment.reactions" :key="reaction.reaction_type_id"
                                            @click.stop="UserId ? toggleReaction(comment.id, reaction.react_type) : null"
                                            class="flex items-center text-xs bg-gray-700/50 hover:bg-gray-600 rounded-full px-2 py-1 transition-colors"
                                            :class="{
                                                    'bg-blue-900/50': reaction.userReacted,
                                                    'cursor-default': !UserId,
                                                    'hover:bg-gray-700/50': !UserId
                                                }">
                                            <span class="mr-1">{{ reaction.icon }}</span>
                                            <span>{{ reaction.count }}</span>
                                        </button>
                                    </div>

                                    <!-- Add reaction button -->
                                    <div class="relative" v-if="UserId">
                                        <button
                                            class="text-gray-400 hover:text-gray-200 text-xs flex items-center gap-1 p-1 rounded-full hover:bg-gray-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span class="hidden sm:inline">Add reaction</span>
                                        </button>

                                        <!-- Reaction picker -->
                                        <div
                                            class="absolute bottom-full left-0 mb-2 hidden group-hover:flex bg-gray-800 rounded-full shadow-lg p-1 space-x-1 border border-gray-700 z-10">
                                            <button v-for="reaction in reactions" :key="reaction.id"
                                                @click.stop="toggleReaction(comment.id, reaction.react_type)"
                                                class="hover:scale-125 transform transition-transform text-sm"
                                                :title="reaction.react_type">
                                                {{ reaction.icon }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div v-if="comments.length === 0" class="text-center py-6 sm:py-8">
                            <div class="text-gray-400 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 mx-auto"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-gray-300 font-medium mb-1 text-sm sm:text-base">No comments yet</h3>
                            <p class="text-gray-500 text-xs sm:text-sm">Be the first to share what you think!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
    import MainLayout from "@/Layouts/MainLayout.vue";
    import {
        ref,
        onMounted,
        computed
    } from "vue";
    import {
        useRouter
    } from "vue-router";
    import axios from "axios";
    import {
        usePage
    } from "@inertiajs/vue3";
    import Swal from 'sweetalert2'; // Add this import

    const props = defineProps({
        auth: {
            type: Object,
            default: () => ({}),
        },
    });

    const router = useRouter();

    // Helper functions
    const getMediaUrl = (url) => {
        if (!url) return "/js/Assets/default-photo.jpg";
        if (url.startsWith("http")) return url;

        const cleanPath = url
            .replace(/^storage\//, "")
            .replace(/^public\//, "")
            .replace(/^\//, "")
            .replace(/^storage\//, "");

        return cleanPath ? `/storage/${cleanPath}` : "/js/Assets/default-photo.jpg";
    };

    // State variables
    const photo = ref({
        id: null,
        title: "",
        description: "",
        imageUrl: "",
        altText: "",
        tags: [],
        photoProfile: "",
        user: null,
        created_at: null,
    });
    const loading = ref(true);
    const isLiked = ref(false);
    const likeCount = ref(0);
    const isBookmarked = ref(false);
    const comments = ref([]);
    const newComment = ref("");
    const currentUser = ref(null);
    const bookmarkLoading = ref(false);
    const bookmarkId = ref(null);
    const reactions = ref([]);
    const loadingReactions = ref(false);
    const fetchReactions = async () => {
        try {
            loadingReactions.value = true;
            const response = await axios.get('/api/reactions', {
                headers: {
                    Accept: 'application/json',
                    Authorization: 'Bearer 123'
                }
            });

            reactions.value = response.data.data || [];
        } catch (error) {
            console.error('Error fetching reactions:', error);
            // Fallback data jika API error
            reactions.value = [{
                    "id": 1,
                    "react_type": "like",
                    "created_at": "2025-05-13T13:40:57.000000Z",
                    "updated_at": "2025-05-13T13:40:57.000000Z"
                },
                {
                    "id": 2,
                    "react_type": "love",
                    "created_at": "2025-05-14T10:30:00.000000Z",
                    "updated_at": "2025-05-14T10:30:00.000000Z"
                }
            ];
        } finally {
            loadingReactions.value = false;
        }
    };
    const goBack = () => {
        window.history.back();
    };

    // Get the current authenticated user
    const UserId = computed(() => {
        const user = usePage().props.auth ?.user;
        if (!user) return null;

        currentUser.value = {
            id: user.id,
            name: user.name,
            photo_profile: user.photo_profile || "/js/Assets/default-photo.jpg",
        };

        return user.id;
    });

    // Format date to relative time (e.g. "2 days ago")
    const formatRelativeDate = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1)
            return interval + " year" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 2592000);
        if (interval >= 1)
            return interval + " month" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 86400);
        if (interval >= 1)
            return interval + " day" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 3600);
        if (interval >= 1)
            return interval + " hour" + (interval === 1 ? "" : "s") + " ago";

        interval = Math.floor(seconds / 60);
        if (interval >= 1)
            return interval + " minute" + (interval === 1 ? "" : "s") + " ago";

        return Math.floor(seconds) + " second" + (seconds === 1 ? "" : "s") + " ago";
    };

    // Handle avatar image error
    const handleAvatarError = (e) => {
        e.target.src = "/js/Assets/default-photo.jpg";
    };

    // Replace the toggleLike function with this implementation
    const toggleLike = async () => {
        if (!UserId.value) {
            router.visit('/login');
            return;
        }

        try {
            if (isLiked.value) {
                await axios.delete(`/api/reaction/photo/${photo.value.id}`, {
                    data: {
                        user_id: UserId.value,
                        reaction_type_id: 1 // ID for "like" reaction
                    },
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    }
                });
                likeCount.value--;
            } else {
                await axios.post(`/api/reaction/photo/${photo.value.id}`, {
                    user_id: UserId.value,
                    reaction_type_id: 1 // ID for "like" reaction
                }, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    }
                });
                likeCount.value++;
            }
            isLiked.value = !isLiked.value;
        } catch (error) {
            console.error('Error toggling like:', error);
            if (error.response ?.status === 401) {
                router.visit('/login');
            } else {
                alert('Failed to update like. Please try again.');
            }
        }
    };

    // Toggle bookmark
    const toggleBookmark = async () => {
        if (!currentUser.value ?.id) {
            console.log('No user logged in');
            router.visit('/login');
            return;
        }

        bookmarkLoading.value = true;
        console.log('Starting bookmark operation with:', {
            isBookmarked: isBookmarked.value,
            photoId: photo.value.id,
            userId: currentUser.value.id
        });

        try {
            if (isBookmarked.value) {
                console.log('Attempting to delete bookmark');
                await axios.delete('/api/user-favorite/photo', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    withCredentials: true,
                    data: {
                        user_id: currentUser.value.id,
                        content_photo_id: photo.value.id
                    }
                });
                console.log('Bookmark deleted successfully');
                isBookmarked.value = false;
            } else {
                console.log('Attempting to create bookmark');
                const response = await axios.post('/api/user-favorite/photo', {
                    user_id: currentUser.value.id,
                    content_photo_id: photo.value.id
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    withCredentials: true
                });
                console.log('Bookmark created:', response.data);
                isBookmarked.value = true;
            }
        } catch (error) {
            console.error('Error details:', {
                response: error.response ?.data,
                status: error.response ?.status,
                message: error.message
            });

            if (error.response ?.status === 401) {
                router.visit('/login');
            } else {
                alert('Error updating bookmark. Please try again.');
            }
        } finally {
            bookmarkLoading.value = false;
        }
    };

    // Add this new function to check bookmark status
    const checkIfBookmarked = async (photoId = null) => {
    const currentPhotoId = photoId || photo.value.id;
    const userId = currentUser.value?.id;

    console.log('Checking bookmark with:', { currentPhotoId, userId });

    if (!userId || !currentPhotoId) {
        console.log('Missing userId or photoId, skipping bookmark check');
        return;
    }

    try {
        const response = await axios.get(`/api/favorite/photo/user/${userId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            withCredentials: true
        });

        console.log('Bookmark response:', response.data);
        console.log('Response type:', typeof response.data);
        console.log('Is array:', Array.isArray(response.data));

        // Handle different response formats
        let bookmarkData = [];

        if (Array.isArray(response.data)) {
            bookmarkData = response.data;
        } else if (response.data && typeof response.data === 'object') {
            // Check if it's wrapped in a data property
            if (Array.isArray(response.data.data)) {
                bookmarkData = response.data.data;
            } else if (Array.isArray(response.data.favorites)) {
                bookmarkData = response.data.favorites;
            } else if (response.data.success && Array.isArray(response.data.bookmarks)) {
                bookmarkData = response.data.bookmarks;
            } else {
                // If it's a single object, convert to array
                bookmarkData = [response.data];
            }
        }

        console.log('Processed bookmark data:', bookmarkData);

        const isBookmarkedPhoto = bookmarkData.some(favorite => {
            const favoritePhotoId = favorite.content_photo_id || favorite.photo_id || favorite.id;
            return favoritePhotoId === parseInt(currentPhotoId);
        });

        console.log('Is bookmarked:', isBookmarkedPhoto);
        isBookmarked.value = isBookmarkedPhoto;

    } catch (error) {
        console.error('Error checking bookmark status:', error);

        // Try alternative endpoint if the first one fails
        if (error.response?.status !== 401) {
            try {
                console.log('Trying alternative bookmark check...');
                const altResponse = await axios.get(`/api/user-favorite/check`, {
                    params: {
                        user_id: userId,
                        content_photo_id: currentPhotoId
                    },
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    withCredentials: true
                });

                isBookmarked.value = altResponse.data.is_bookmarked || false;
                console.log('Alternative bookmark check result:', isBookmarked.value);
                return;
            } catch (altError) {
                console.error('Alternative bookmark check also failed:', altError);
            }
        }

        if (error.response?.status === 401) {
            router.visit('/login');
        } else if (error.response?.status === 404) {
            // User has no bookmarks yet
            console.log('No bookmarks found for user');
            isBookmarked.value = false;
        }
        // Jangan reset isBookmarked jika error lain, biarkan state sebelumnya
    }
};

    // Fetch comments for the photo
    const fetchComments = async (photoId) => {
        try {
            const response = await axios.get(`/api/comment/photo/${photoId}`, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token") || "123"}`,
                },
            });

            // Handle both array and single comment responses
            const commentData = response.data.data || response.data;
            const commentsArray = Array.isArray(commentData) ? commentData : [commentData];

            // Fetch reactions for each comment
            const commentsWithReactions = await Promise.all(commentsArray.map(async (comment) => {
                // Create basic comment structure
                const commentObj = {
                    id: comment.id,
                    user: {
                        id: comment.user_id,
                        name: "Loading...",
                        photo_profile: "/js/Assets/default-photo.jpg",
                    },
                    text: comment.content,
                    date: comment.created_at,
                    canDelete: comment.user_id === (currentUser.value ?.id || null),
                    isLoading: false,
                    reactions: []
                };

                // Fetch user details
                try {
                    const userResponse = await axios.get(`/api/users/${comment.user_id}`, {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("token") || "123"}`,
                        },
                    });

                    commentObj.user = {
                        id: userResponse.data.id,
                        name: userResponse.data.name,
                        photo_profile: userResponse.data.photo_profile ||
                            "/js/Assets/default-photo.jpg",
                    };
                } catch (userError) {
                    console.error("Error fetching user:", userError);
                }

                // Fetch reactions for this comment
                try {
                    const reactionsResponse = await axios.get(
                        `/api/reaction/comment/${comment.id}`, {
                            headers: {
                                Authorization: `Bearer ${localStorage.getItem("token") || "123"}`,
                            },
                        });

                    // Transform the response to match our frontend structure
                    commentObj.reactions = reactionsResponse.data.map(reaction => ({
                        id: reaction.reaction_type_id,
                        react_type: reaction.react_type,
                        icon: reaction.icon,
                        count: reaction.count,
                        userReacted: reaction.userReacted,
                        user_id: reaction.userReacted ? UserId.value : null
                    }));
                } catch (reactionsError) {
                    console.error("Error fetching reactions:", reactionsError);
                }

                return commentObj;
            }));

            comments.value = commentsWithReactions;

        } catch (error) {
            console.error("Error fetching comments:", error);

            // Set dummy comments if API fails
            comments.value = [{
                    id: 1,
                    user: {
                        id: 2,
                        name: "John Doe",
                        photo_profile: "/js/Assets/default-photo.jpg",
                    },
                    text: "This is an amazing photo! The colors are fantastic.",
                    date: "2023-05-15T10:30:00Z",
                    canDelete: false,
                },
                {
                    id: 2,
                    user: {
                        id: 3,
                        name: "Jane Smith",
                        photo_profile: "/js/Assets/default-photo.jpg",
                    },
                    text: "Great composition and lighting!",
                    date: "2023-05-14T16:45:00Z",
                    canDelete: false,
                },
            ];
        }
    };

    // Add comment
    const addComment = async () => {
        if (!newComment.value.trim() || !UserId.value) return;

        try {
            const loadingComment = {
                id: "temp-" + Date.now(),
                user: {
                    ...currentUser.value,
                },
                text: newComment.value,
                status: 'hidden',
                date: new Date().toISOString(),
                canDelete: true,
                isLoading: true,
            };
            comments.value.unshift(loadingComment);

            const response = await axios.post(
                `/api/comment/photo/${photo.value.id}`,
                {
                    content: newComment.value.trim(),
                    content_photo_id: photo.value.id,
                    user_id: UserId.value,
                },
                {
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                }
            );

            // Remove loading comment and add the real one
            comments.value = comments.value.filter(
                (c) => c.id !== loadingComment.id
            );
            
            // Add the new comment with hidden status
            comments.value.unshift({
                id: response.data.id,
                user: {
                    ...currentUser.value,
                },
                text: response.data.content || newComment.value,
                date: response.data.created_at || new Date().toISOString(),
                status: 'hidden',
                canDelete: true,
            });

            newComment.value = "";

            // Show success message with SweetAlert2
            await Swal.fire({
                title: 'Comment Submitted!',
                text: 'Komentar Anda telah dikirim dan sedang dalam proses pemantauan. Komentar tersebut akan terlihat oleh orang lain setelah disetujui.',
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                timer: 5000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });

        } catch (error) {
            console.error("Error adding comment:", error);
            comments.value = comments.value.filter((c) => !c.isLoading);

            let errorMessage = "Failed to add comment. Please try again.";
            if (error.response) {
                if (error.response.status === 401) {
                    errorMessage = "Please log in to comment.";
                    router.push("/login");
                } else if (error.response.status === 422) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join("\n");
                } else if (error.response.data?.message) {
                    errorMessage = error.response.data.message;
                }
            }

            // Show error message with SweetAlert2
            await Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonColor: '#d33',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        }
    };

    // Delete comment
    const deleteComment = async (commentId) => {
        if (!confirm("Yakin ingin menghapus komentar ini?")) return;

        try {
            await axios.delete(
                `/api/user-comments/${commentId}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    }
                }
            );

            // Hapus komentar dari daftar tampilan
            comments.value = comments.value.filter(c => c.id !== commentId);
            alert('Komentar berhasil dihapus');
        } catch (error) {
            console.error('Delete error:', error);
            alert(error.response ?.data ?.message || 'Gagal menghapus komentar');
        }
    };

    // Format file size from bytes to readable format
    const formatFileSize = (bytes) => {
        if (typeof bytes !== 'number' || isNaN(bytes)) return 'Not specified';
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        const sizeValue = parseFloat(bytes / Math.pow(k, i)).toFixed(2);
        const formattedSize = sizeValue % 1 === 0 ? sizeValue.toString().split('.')[0] : sizeValue;

        return `${formattedSize} ${sizes[i]}`;
    };

    const toggleReaction = async (commentId, reactionType) => {
        if (!UserId.value) {
            return;
        }

        try {
            const reaction = reactions.value.find(r => r.react_type === reactionType);
            if (!reaction) {
                console.error('Reaction type not found:', reactionType);
                return;
            }

            const commentIndex = comments.value.findIndex(c => c.id === commentId);
            if (commentIndex === -1) return;

            const comment = {
                ...comments.value[commentIndex]
            };
            if (!comment.reactions) comment.reactions = [];

            const existingReactionIndex = comment.reactions.findIndex(r => r.userReacted);

            // Jika user sudah punya reaction, dan mengklik reaction yang sama
            if (existingReactionIndex >= 0 && comment.reactions[existingReactionIndex].react_type ===
                reactionType) {
                // Hapus reaction
                await axios.delete(`/api/reaction/comment/${commentId}`, {
                    data: {
                        user_id: UserId.value,
                        reaction_type_id: reaction.id
                    }
                });

                // Update state lokal
                comment.reactions = comment.reactions.map(r => ({
                    ...r,
                    count: r.react_type === reactionType ? Math.max(0, r.count - 1) : r.count,
                    userReacted: r.react_type === reactionType ? false : r.userReacted
                })).filter(r => r.count > 0);
            } else {
                // Tambah/update reaction baru
                const response = await axios.post(`/api/reaction/comment/${commentId}`, {
                    user_id: UserId.value,
                    reaction_type_id: reaction.id
                });

                if (existingReactionIndex >= 0) {
                    // Update reaction yang ada
                    const oldReaction = comment.reactions[existingReactionIndex];
                    comment.reactions = comment.reactions.map(r => ({
                        ...r,
                        count: r.react_type === oldReaction.react_type ? r.count - 1 : r.count
                    })).filter(r => r.count > 0);
                }

                // Tambahkan reaction baru atau update yang ada
                const existingReactionTypeIndex = comment.reactions.findIndex(r => r.react_type ===
                    reactionType);
                if (existingReactionTypeIndex >= 0) {
                    comment.reactions[existingReactionTypeIndex] = {
                        ...comment.reactions[existingReactionTypeIndex],
                        count: comment.reactions[existingReactionTypeIndex].count + 1,
                        userReacted: true
                    };
                } else {
                    comment.reactions.push({
                        ...response.data.reaction,
                        userReacted: true
                    });
                }
            }

            // Update state
            comments.value[commentIndex] = comment;

        } catch (error) {
            console.error('Error toggling reaction:', error);
            alert('Failed to update reaction. Please try again.');
        }
    };

    // Fetch photo data
    onMounted(async () => {
    try {
        // Fetch reactions first
        await fetchReactions();

        const slug = window.location.pathname.split("/").pop();
        const response = await axios.get(`/api/content-photo/${slug}`, {
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${localStorage.getItem("token") || "123"}`,
            },
        });

        console.log('Photo data:', response.data);

        const photoData = response.data.photo;

        photo.value = {
            id: photoData.id,
            title: photoData.title,
            description: photoData.description,
            imageUrl: photoData.image_url ?
                `/storage/${photoData.image_url.replace(/^public\//, "")}` :
                "/js/Assets/default-photo.jpg",
            altText: photoData.alt_text || photoData.title,
            tags: photoData.tag ? photoData.tag.split(/,\s*/) : [],
            user: photoData.user,
            created_at: photoData.created_at,
            source: photoData.source,
            note: photoData.note,
            total_views: photoData.total_views || 0,
            // Metadata fields
            collection_date: photoData.metadata_photo?.collection_date,
            file_size: photoData.metadata_photo?.file_size,
            aperture: photoData.metadata_photo?.aperture,
            location: photoData.metadata_photo?.location,
            camera_model: photoData.metadata_photo?.model,
            ISO: photoData.metadata_photo?.ISO,
            dimensions: photoData.metadata_photo?.dimensions,
        };

        // Set the actual like count from total_reactions
        likeCount.value = response.data.total_reactions || 0;

        // Get existing reactions for this photo
        const userReaction = photoData.content_reactions.find(
            reaction => reaction.user_id === UserId.value
        );
        isLiked.value = !!userReaction;

        // Check if bookmark data is already included in photo response
        if (photoData.user_favorites && Array.isArray(photoData.user_favorites)) {
            const userBookmark = photoData.user_favorites.find(
                fav => fav.user_id === UserId.value
            );
            isBookmarked.value = !!userBookmark;
            console.log('Bookmark status from photo data:', isBookmarked.value);
        } else {
            // PENTING: Check bookmark setelah photo data berhasil di-set
            if (photo.value.id && UserId.value) {
                console.log('Calling checkIfBookmarked with photo ID:', photo.value.id);
                await checkIfBookmarked(photo.value.id);
            }
        }

        // Fetch comments if the photo has an ID
        if (photo.value.id) {
            await fetchComments(photo.value.id);
        }

    } catch (error) {
        console.error("Error fetching photo:", error);
        router.push("/not-found");
    } finally {
        loading.value = false;
    }
});

    // Add this function with the other state variables and functions
    const getCommentStatusClass = (status) => {
        return {
            'bg-gray-800/50': status === 'published' || !status,
            'bg-yellow-800/30 border-yellow-800/50': status === 'hidden'
        };
    };
</script>

<style scoped>
    .rounded-image {
        border-radius: 0.5rem;
    }

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
