<template>
    <MainLayout>
        <div class="min-h-screen bg-black mt-8">
            <div class="text-white">
                <!-- Back Button -->
                <div class="p-6">
                    <button @click="$router.go(-1)" class="flex items-center text-gray-400 hover:text-white">
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
                            class="w-full h-auto max-h-[70vh] object-contain bg-gray-950" />

                        <!-- User Info Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white/20">
                                    <img :src="getMediaUrl(photo.user?.photo_profile)" :alt="photo.user?.name"
                                        class="w-full h-full object-cover" @error="handleAvatarError" />
                                </div>
                                <div>
                                    <p class="text-white font-medium">
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

            <!-- Photo Details -->
            <div class="p-6 ml-24 mr-24">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">
                            {{ photo.title }}
                        </h1>
                        <p v-if="photo.source" class="text-gray-400 text-sm mt-1">
                            Source: {{ photo.source }}
                        </p>
                    </div>
                    <div class="flex space-x-4">
                        <!-- Like Button -->
                        <button @click="toggleLike" class="flex items-center space-x-1 group">
                            <div class="p-1 rounded-full group-hover:bg-red-500/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :class="
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
                                ">
                                {{ likeCount }}
                            </span>
                        </button>

                        <!-- Bookmark Button -->
                        <button @click="toggleBookmark" class="flex items-center group">
                            <div class="p-1 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :class="
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
                <p class="text-gray-300 mb-6 whitespace-pre-line">
                    {{ photo.description }}
                </p>
                <div class="border-t border-gray-800 pt-6" />

                <!-- Photo Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-gray-400 text-sm">Collection Date</h3>
                        <p class="text-white">{{ photo.collection_date || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-sm">Location</h3>
                        <p class="text-white">{{ photo.location || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-sm">Model Camera</h3>
                        <p class="text-white">{{ photo.camera_model || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-sm">Dimensions</h3>
                        <p class="text-white">{{ photo.dimensions || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-sm">File Size</h3>
                        <p class="text-white">
                            {{ photo.file_size ? formatFileSize(parseInt(photo.file_size)) : 'Not specified' }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-sm">Aperture</h3>
                        <p class="text-white">{{ photo.aperture || 'Not specified' }}</p>
                    </div>
                    <div>
                        <h3 class="text-gray-400 text-sm">ISO</h3>
                        <p class="text-white">{{ photo.ISO || 'Not specified' }}</p>
                    </div>
                </div>

                <!-- Tags -->
                <div class="flex flex-wrap gap-2 mb-6" v-if="photo.tags && photo.tags.length">
                    <router-link v-for="tag in photo.tags" :key="tag" :to="`/tags/${tag.toLowerCase()}`"
                        class="px-3 py-1 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-full text-sm transition-colors">
                        #{{ tag }}
                    </router-link>
                </div>

                <!-- Photo Stats -->
                <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ photo.total_views || 0 }} views</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>{{ comments.length }} comments</span>
                    </div>
                </div>

                <!-- Comments Section -->
<div class="border-t border-gray-800 pt-6">
    <h2 class="text-xl font-semibold text-white mb-6">
        Comments ({{ comments.length }})
    </h2>

    <!-- New Comment Form -->
    <div class="mb-8" v-if="UserId">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex-shrink-0">
                <img :src="currentUser.photo_profile" :alt="currentUser.name"
                    class="w-full h-full object-cover" @error="handleAvatarError" />
            </div>
            <div class="flex-1">
                <div class="relative">
                    <textarea v-model="newComment" placeholder="Add a comment..."
                        class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder-gray-400"
                        rows="3"></textarea>
                    <div class="absolute bottom-3 right-3 flex items-center">
                        <span class="text-xs text-gray-400 mr-3">{{ newComment.length }}/500</span>
                        <button @click="addComment" :disabled="!newComment.trim()"
                            class="px-4 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-600 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors text-sm font-medium">
                            Post
                        </button>
                    </div>
                </div>
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-xs text-gray-400">Add reaction:</span>
                    <div class="flex gap-1">
                        <button v-for="reaction in availableReactions" :key="reaction.id"
                            class="text-sm hover:scale-125 transform transition-transform"
                            @click="newComment += reaction.emoji">
                            {{ reaction.emoji }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="mb-8 p-4 bg-gray-800/50 rounded-lg text-center border border-gray-700">
        <p class="text-gray-300">
            Please
            <router-link to="/login" class="text-blue-400 hover:underline font-medium">login</router-link>
            to post a comment.
        </p>
    </div>

    <!-- Comments List -->
    <div class="space-y-5">
        <div v-for="comment in comments" :key="comment.id" class="group">
            <div v-if="comment.isLoading" class="p-4 bg-gray-800/50 rounded-lg flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-700 animate-pulse"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-700 rounded w-1/4 animate-pulse"></div>
                    <div class="h-3 bg-gray-700 rounded w-3/4 animate-pulse"></div>
                </div>
            </div>
            <div v-else class="bg-gray-800/50 p-4 rounded-lg hover:bg-gray-800/70 transition-colors border border-gray-700">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <router-link :to="`/users/${comment.user.id}`" class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700">
                                <img :src="getMediaUrl(comment.user?.photo_profile)" :alt="comment.user?.name"
                                    class="w-full h-full object-cover" @error="handleAvatarError" />
                            </div>
                        </router-link>
                        <div>
                            <router-link :to="`/users/${comment.user.id}`" class="font-medium text-white hover:underline">
                                {{ comment.user.name }}
                            </router-link>
                            <p class="text-gray-400 text-xs mt-0.5">
                                {{ formatRelativeDate(comment.date) }}
                            </p>
                        </div>
                    </div>
                    <button v-if="comment.canDelete" @click="deleteComment(comment.id)"
                        class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all p-1 rounded-full hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="mt-3 text-gray-200 pl-13 whitespace-pre-line">
                    {{ comment.text }}
                </p>
                
                <!-- Comment Reactions -->
                <div class="mt-3 pl-13 flex items-center gap-3">
                    <!-- Reaction buttons -->
                    <div class="flex items-center gap-2">
                        <button v-for="reaction in comment.reactions" :key="reaction.type"
                            @click.stop="toggleReaction(comment.id, reaction.type)"
                            class="flex items-center text-xs bg-gray-700/50 hover:bg-gray-600 rounded-full px-2.5 py-1 transition-colors"
                            :class="{ 'bg-blue-900/50': reaction.userReacted }">
                            <span class="mr-1">{{ availableReactions.find(r => r.name === reaction.type)?.emoji || '👍' }}</span>
                            <span>{{ reaction.count }}</span>
                        </button>
                    </div>
                    
                    <!-- Add reaction button -->
                    <div class="relative">
                        <button class="text-gray-400 hover:text-gray-200 text-xs flex items-center gap-1 p-1 rounded-full hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Add reaction</span>
                        </button>
                        
                        <!-- Reaction picker -->
                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:flex bg-gray-800 rounded-full shadow-lg p-1 space-x-1 border border-gray-700 z-10">
                            <button v-for="reaction in availableReactions" :key="reaction.id"
                                @click.stop="toggleReaction(comment.id, reaction.name)"
                                class="hover:scale-125 transform transition-transform text-sm"
                                :title="reaction.name">
                                {{ reaction.emoji }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Empty state -->
        <div v-if="comments.length === 0" class="text-center py-8">
            <div class="text-gray-400 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-gray-300 font-medium mb-1">No comments yet</h3>
            <p class="text-gray-500 text-sm">Be the first to share what you think!</p>
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

    const availableReactions = [{
            id: 1,
            emoji: '👍',
            name: 'like'
        },
        {
            id: 2,
            emoji: '❤️',
            name: 'love'
        },
        {
            id: 3,
            emoji: '😂',
            name: 'laugh'
        },
        {
            id: 4,
            emoji: '😮',
            name: 'surprise'
        },
        {
            id: 5,
            emoji: '😢',
            name: 'sad'
        },
        {
            id: 6,
            emoji: '😡',
            name: 'angry'
        }
    ]

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

    // Toggle like
    const toggleLike = () => {
        isLiked.value = !isLiked.value;
        likeCount.value += isLiked.value ? 1 : -1;
    };

    // Toggle bookmark
    const toggleBookmark = () => {
        isBookmarked.value = !isBookmarked.value;
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

            comments.value = await Promise.all(commentsArray.map(async (comment) => {
                // Create basic comment structure
                const commentObj = {
                    id: comment.id,
                    user: {
                        id: comment.user_id,
                        name: "Loading...", // Temporary placeholder
                        photo_profile: "/js/Assets/default-photo.jpg",
                    },
                    text: comment.content,
                    date: comment.created_at,
                    canDelete: comment.user_id === (currentUser.value ?.id || null),
                    isLoading: false,
                };

                // Try to fetch user details if not included in comment response
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
                    // Keep the placeholder values if user fetch fails
                }

                return commentObj;
            }));

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
                date: new Date().toISOString(),
                canDelete: true,
                isLoading: true,
            };
            comments.value.unshift(loadingComment);

            const response = await axios.post(
                `/api/comment/photo/${photo.value.id}`, {
                    content: newComment.value.trim(),
                    content_photo_id: photo.value.id,
                    user_id: UserId.value,
                }, {
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
            comments.value.unshift({
                id: response.data.id,
                user: {
                    ...currentUser.value,
                },
                text: response.data.content || newComment.value,
                date: response.data.created_at || new Date().toISOString(),
                canDelete: true,
            });

            newComment.value = "";
        } catch (error) {
            console.error("Error adding comment:", error);
            // Remove loading comment if error occurs
            comments.value = comments.value.filter((c) => !c.isLoading);

            let errorMessage = "Failed to add comment. Please try again.";
            if (error.response) {
                if (error.response.status === 401) {
                    errorMessage = "Please log in to comment.";
                    router.push("/login");
                } else if (error.response.status === 422) {
                    // Handle validation errors
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join("\n");
                } else if (error.response.data ?.message) {
                    errorMessage = error.response.data.message;
                }
            }
            alert(errorMessage);
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

    const toggleReaction = (commentId, reactionType) => {
        const comment = comments.value.find(c => c.id === commentId);
        if (!comment) return;

        // Jika belum ada array reactions, buat baru
        if (!comment.reactions) {
            comment.reactions = [];
        }

        // Cari reaksi yang sudah ada
        const existingReaction = comment.reactions.find(r => r.type === reactionType);

        if (existingReaction) {
            // Toggle reaksi user
            existingReaction.userReacted = !existingReaction.userReacted;
            existingReaction.count += existingReaction.userReacted ? 1 : -1;

            // Hapus jika count <= 0
            if (existingReaction.count <= 0) {
                comment.reactions = comment.reactions.filter(r => r.type !== reactionType);
            }
        } else {
            // Tambahkan reaksi baru
            comment.reactions.push({
                type: reactionType,
                count: 1,
                userReacted: true
            });
        }
    };

    // Fetch photo data
    onMounted(async () => {
        try {
            const slug = window.location.pathname.split("/").pop();
            const response = await axios.get(`/api/content-photo/${slug}`, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token") || "123"}`,
                },
            });

            photo.value = {
                ...response.data,
                id: response.data.id,
                imageUrl: response.data.image_url ?
                    response.data.image_url.startsWith("http") ?
                    response.data.image_url :
                    `/storage/${response.data.image_url.replace(/^public\//, "")}` :
                    "/js/Assets/default-photo.jpg",
                altText: response.data.alt_text || response.data.title || "",
                tags: response.data.tag ? response.data.tag.split(", ") : [],
                user: response.data.user ? {
                    ...response.data.user,
                    photo_profile: response.data.user.photo_profile
                } : null,
                created_at: response.data.created_at,
                collection_date: response.data.metadata_photo ?.collection_date,
                file_size: response.data.metadata_photo ?.file_size,
                aperture: response.data.metadata_photo ?.aperture,
                location: response.data.metadata_photo ?.location,
                camera_model: response.data.metadata_photo ?.model,
                ISO: response.data.metadata_photo ?.ISO,
                dimensions: response.data.metadata_photo ?.dimensions,
            };

            // Set dummy data for likes/bookmarks for demo
            likeCount.value = Math.floor(Math.random() * 100);
            isLiked.value = Math.random() > 0.5;
            isBookmarked.value = Math.random() > 0.5;

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
