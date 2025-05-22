<template>
    <MainLayout>
        <div class="min-h-screen bg-black md:mt-12">
            <!-- Back Button - Adjusted for mobile -->
            <button @click="router.go(-1)"
                class="fixed md:absolute top-16 left-2 md:top-4 md:left-4 z-10 p-2 bg-black/50 rounded-full hover:bg-black/75 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>

            <!-- Main Video Container -->
            <div class="rounded-lg shadow-xl overflow-hidden">
                <!-- Video Player - Responsive adjustments -->
                <div class="relative pt-[56.25%] bg-black"> <!-- 16:9 aspect ratio -->
                    <video v-if="isLocalVideo" controls :poster="video.thumbnailUrl"
                        class="absolute top-0 left-0 w-full h-full object-contain bg-gray-950">
                        <source :src="video.video_url" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>

                    <iframe v-else class="absolute top-0 left-0 w-full h-full" :src="video.video_url" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <!-- Video Info Section - Adjusted padding for mobile -->
                <div class="p-4 md:p-6 md:ml-14 md:mr-14">
                    <!-- Title and Actions - Stacked on mobile -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4 gap-3">
                        <div class="flex-1">
                            <h1 class="text-xl md:text-2xl font-bold text-white">
                                {{ video.title }}
                            </h1>
                            <div class="flex items-center mt-2 space-x-4 text-xs md:text-sm text-gray-400">
                                <span>{{ video.views }} views</span>
                                <span>{{ formatDate(video.created_at) }}</span>
                            </div>
                        </div>

                        <div class="flex space-x-3 md:space-x-4">
                            <!-- Like Button - Smaller on mobile -->
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
                                    " class="text-sm md:text-base">
                                    {{ likeCount }}
                                </span>
                            </button>

                            <!-- Bookmark Button - Smaller on mobile -->
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

                    <!-- Creator Info - Adjusted spacing for mobile -->
                    <div class="flex items-center gap-3 mb-4 p-2 md:p-3 rounded-lg">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full overflow-hidden border-2 border-white/20">
                            <img :src="getMediaUrl(video.user?.photo_profile)" :alt="video.user?.name"
                                class="w-full h-full object-cover" />
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm md:text-base">
                                {{ video.user?.name || "Unknown Creator" }}
                            </p>
                            <p class="text-gray-300 text-xs" v-if="video.created_at">
                                Uploaded {{ formatRelativeDate(video.created_at) }}
                            </p>
                            <p class="text-gray-300 text-xs">Content Creator</p>
                        </div>
                    </div>

                    <!-- Description - Smaller text on mobile -->
                    <div class="mb-4 md:mb-6">
                        <p class="text-gray-300 whitespace-pre-line text-sm md:text-base">
                            {{ video.description }}
                        </p>
                    </div>

                    <div class="border-t border-gray-800 pt-4 md:pt-6" />

                    <!-- Video Details - Single column on mobile -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">Collection Date</h3>
                            <p class="text-white text-sm md:text-base">{{ formatCollectionDate(video.collection_date) }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">Location</h3>
                            <p class="text-white text-sm md:text-base">{{ video.location || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">Frame Rate</h3>
                            <p class="text-white text-sm md:text-base">{{ video.frame_rate || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">Resolution</h3>
                            <p class="text-white text-sm md:text-base">{{ video.resolution || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">File Size</h3>
                            <p class="text-white text-sm md:text-base">
                                {{ video.file_size ? formatFileSize(parseInt(video.file_size)) : 'Not specified' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">Duration</h3>
                            <p class="text-white text-sm md:text-base">{{ video.duration || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">File Format</h3>
                            <p class="text-white text-sm md:text-base">{{ video.format_file || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-xs md:text-sm">Video Audio Codec</h3>
                            <p class="text-white text-sm md:text-base">{{ video.codec_video_audio || 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Tags - Smaller on mobile -->
                    <div class="flex flex-wrap gap-2 mb-4 md:mb-6" v-if="video.tags && video.tags.length">
                        <router-link v-for="tag in video.tags" :key="tag" :to="`/tags/${tag.toLowerCase()}`"
                            class="px-2 py-0.5 md:px-3 md:py-1 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-full text-xs md:text-sm transition-colors">
                            #{{ tag }}
                        </router-link>
                    </div>

                    <!-- Comments Section -->
                    <div class="border-t border-gray-800 pt-4 md:pt-6">
                        <h2 class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-6">
                            Comments ({{ comments.length }})
                        </h2>

                        <!-- New Comment Form - Adjusted for mobile -->
                        <div class="mb-4 md:mb-8" v-if="UserId">
                            <div class="flex items-start gap-2 md:gap-4">
                                <div
                                    class="w-7 h-7 md:w-10 md:h-10 rounded-full overflow-hidden bg-gray-700 flex-shrink-0">
                                    <img :src="currentUser.photo_profile" :alt="currentUser.name"
                                        class="w-full h-full object-cover" @error="handleAvatarError" />
                                </div>
                                <div class="flex-1">
                                    <div class="relative">
                                        <textarea v-model="newComment" placeholder="Add a comment..."
                                            class="w-full px-3 py-2 md:px-4 md:py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition placeholder-gray-400 text-sm md:text-base"
                                            rows="2"></textarea>
                                        <div class="absolute bottom-1 right-1 md:bottom-3 md:right-3 flex items-center">
                                            <span
                                                class="text-xs text-gray-400 mr-1 md:mr-3 mb-2">{{ newComment.length }}/500</span>
                                            <button @click="addComment" :disabled="!newComment.trim()"
                                                class="px-2 py-0.5 mb-2 md:px-4 md:py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-600 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors text-xs md:text-sm font-medium">
                                                Post
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-1 md:mt-2 flex items-center gap-1 md:gap-2">
                                        <span class="text-xs text-gray-400">Add reaction:</span>
                                        <div class="flex gap-1">
                                            <button v-for="reaction in availableReactions" :key="reaction.id"
                                                class="text-xs hover:scale-110 md:hover:scale-125 transform transition-transform"
                                                @click="newComment += reaction.emoji">
                                                {{ reaction.emoji }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else
                            class="mb-4 md:mb-8 p-2 md:p-4 bg-gray-800/50 rounded-lg text-center border border-gray-700">
                            <p class="text-gray-300 text-xs md:text-base">
                                Please
                                <router-link to="/login" class="text-blue-400 hover:underline font-medium">login
                                </router-link>
                                to post a comment.
                            </p>
                        </div>

                        <!-- Comments List - Adjusted spacing for mobile -->
                        <div class="space-y-3 md:space-y-5">
                            <div v-for="comment in comments" :key="comment.id" class="group">
                                <div v-if="comment.isLoading"
                                    class="p-2 md:p-4 bg-gray-800/50 rounded-lg flex items-center gap-2 md:gap-3">
                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-gray-700 animate-pulse"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-3 bg-gray-700 rounded w-1/4 animate-pulse"></div>
                                        <div class="h-2 bg-gray-700 rounded w-3/4 animate-pulse"></div>
                                    </div>
                                </div>
                                <div v-else
                                    class="bg-gray-800/50 p-2 md:p-4 rounded-lg hover:bg-gray-800/70 transition-colors border border-gray-700">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-2 md:gap-3">
                                            <router-link :to="`/users/${comment.user.id}`" class="flex-shrink-0">
                                                <div
                                                    class="w-6 h-6 md:w-10 md:h-10 rounded-full overflow-hidden bg-gray-700">
                                                    <img :src="getMediaUrl(comment.user?.photo_profile)"
                                                        :alt="comment.user?.name" class="w-full h-full object-cover"
                                                        @error="handleAvatarError" />
                                                </div>
                                            </router-link>
                                            <div>
                                                <router-link :to="`/users/${comment.user.id}`"
                                                    class="font-medium text-white hover:underline text-sm md:text-base">
                                                    {{ comment.user.name }}
                                                </router-link>
                                                <p class="text-gray-400 text-xs mt-0.5">
                                                    {{ formatRelativeDate(comment.date) }}
                                                </p>
                                            </div>
                                        </div>
                                        <button v-if="comment.canDelete" @click="deleteComment(comment.id)"
                                            class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 transition-all p-0.5 md:p-1 rounded-full hover:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p
                                        class="mt-1 md:mt-3 text-gray-200 pl-8 md:pl-13 whitespace-pre-line text-sm md:text-base">
                                        {{ comment.text }}
                                    </p>

                                    <!-- Comment Reactions - Smaller on mobile -->
                                    <div class="mt-1 md:mt-3 pl-8 md:pl-13 flex items-center gap-1 md:gap-3">
                                        <!-- Reaction buttons -->
                                        <div class="flex items-center gap-1 md:gap-2">
                                            <button v-for="reaction in comment.reactions" :key="reaction.type"
                                                @click.stop="toggleReaction(comment.id, reaction.type)"
                                                class="flex items-center text-xxs md:text-xs bg-gray-700/50 hover:bg-gray-600 rounded-full px-1 py-0.5 md:px-2.5 md:py-1 transition-colors"
                                                :class="{ 'bg-blue-900/50': reaction.userReacted }">
                                                <span
                                                    class="mr-0.5 md:mr-1">{{ availableReactions.find(r => r.name === reaction.type)?.emoji || '👍' }}</span>
                                                <span>{{ reaction.count }}</span>
                                            </button>
                                        </div>

                                        <!-- Add reaction button -->
                                        <div class="relative">
                                            <button
                                                class="text-gray-400 hover:text-gray-200 text-xxs md:text-xs flex items-center gap-1 p-0.5 rounded-full hover:bg-gray-700 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                <span class="hidden md:inline">Add reaction</span>
                                            </button>

                                            <!-- Reaction picker -->
                                            <div
                                                class="absolute bottom-full left-0 mb-1 hidden group-hover:flex bg-gray-800 rounded-full shadow-lg p-1 space-x-1 border border-gray-700 z-10">
                                                <button v-for="reaction in availableReactions" :key="reaction.id"
                                                    @click.stop="toggleReaction(comment.id, reaction.name)"
                                                    class="hover:scale-110 md:hover:scale-125 transform transition-transform text-xs"
                                                    :title="reaction.name">
                                                    {{ reaction.emoji }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Empty state -->
                            <div v-if="comments.length === 0" class="text-center py-4 md:py-8">
                                <div class="text-gray-400 mb-1 md:mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-12 md:w-12 mx-auto"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <h3 class="text-gray-300 font-medium text-sm md:text-base">No comments yet</h3>
                                <p class="text-gray-500 text-xs md:text-sm">Be the first to share what you think!</p>
                            </div>
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

    // CSRF Token and Headers
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content'),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };

    // Helper function for media URLs
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

    // Reactive state
    const video = ref({});
    const loading = ref(true);
    const isLiked = ref(false);
    const likeCount = ref(0);
    const isBookmarked = ref(false);
    const bookmarkId = ref(null);
    const bookmarkLoading = ref(false);
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

    // Fetch comments for the video
    const fetchComments = async (videoId) => {
        try {
            const response = await axios.get(`/api/comment/video/${videoId}`, {
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
                `/api/comment/video/${video.value.id}`, {
                    content: newComment.value.trim(),
                    content_video_id: video.value.id,
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

    // Computed properties
    const isLocalVideo = computed(() => {
        return video.value.video_url && !video.value.video_url.includes('youtube.com');
    });

    // Utility functions
    const formatDate = (dateString) => {
        if (!dateString) return "";
        const options = {
            year: "numeric",
            month: "long",
            day: "numeric"
        };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const formatRelativeDate = (dateString) => {
        if (!dateString) return "";
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        const intervals = [{
                label: 'year',
                seconds: 31536000
            },
            {
                label: 'month',
                seconds: 2592000
            },
            {
                label: 'day',
                seconds: 86400
            },
            {
                label: 'hour',
                seconds: 3600
            },
            {
                label: 'minute',
                seconds: 60
            }
        ];

        for (const interval of intervals) {
            const count = Math.floor(seconds / interval.seconds);
            if (count >= 1) {
                return `${count} ${interval.label}${count === 1 ? '' : 's'} ago`;
            }
        }

        return `${Math.floor(seconds)} second${seconds === 1 ? '' : 's'} ago`;
    };

    const formatCollectionDate = (dateString) => {
    if (!dateString) return 'Not specified';
    
    try {
        const date = new Date(dateString);
        
        // Periksa jika tanggal valid
        if (isNaN(date.getTime())) {
            return 'Invalid date';
        }
        
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            timeZone: 'UTC' // Tambahkan ini jika tanggal harus dianggap UTC
        };
        
        return date.toLocaleDateString('en-US', options);
    } catch (error) {
        console.error('Error formatting date:', error);
        return dateString; // Kembalikan aslinya jika gagal memformat
    }
};

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

    const convertToEmbedUrl = (url) => {
        if (!url) return null;
        if (url.includes("youtube.com/watch")) {
            const videoId = url.split("v=")[1] ?.split("&")[0];
            return videoId ? `https://www.youtube.com/embed/${videoId}` : null;
        }
        return url;
    };

    // Action functions
    const toggleLike = () => {
        isLiked.value = !isLiked.value;
        likeCount.value += isLiked.value ? 1 : -1;
    };

    const toggleBookmark = async () => {
        if (!currentUser.value.id) {
            console.log('No user logged in');
            router.visit('/login');
            return;
        }

        bookmarkLoading.value = true;
        console.log('Starting bookmark operation with:', {
            isBookmarked: isBookmarked.value,
            videoId: video.value.id,
            userId: currentUser.value.id
        });

        try {
            if (isBookmarked.value) {
                console.log('Attempting to delete bookmark');
                // Use the delete video favorite route
                await axios.delete('/api/user-favorite/video', {
                    headers,
                    withCredentials: true,
                    data: {
                        user_id: currentUser.value.id,
                        content_video_id: video.value.id
                    }
                });
                console.log('Bookmark deleted successfully');
                isBookmarked.value = false;
            } else {
                console.log('Attempting to create bookmark');
                // Use the create video favorite route
                const response = await axios.post('/api/user-favorite/video', {
                    user_id: currentUser.value.id,
                    content_video_id: video.value.id
                }, {
                    headers,
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

    const checkIfBookmarked = async () => {
        if (!currentUser.value.id || !video.value.id) return;

        try {
            const response = await axios.get(`/api/favorite/video/user/${currentUser.value.id}`, {
                headers,
                withCredentials: true
            });

            const isBookmarkedVideo = response.data.some(favorite =>
                favorite.content_video_id === video.value.id
            );

            isBookmarked.value = isBookmarkedVideo;
        } catch (error) {
            console.error('Error checking bookmark status:', error);
            if (error.response ?.status === 401) {
                router.visit('/login');
            }
        }
    };

    // Initialize component
    onMounted(async () => {
        try {
            const slug = window.location.pathname.split("/").pop();
            const response = await axios.get(`/api/content-video/${slug}`, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token") || "123"}`,
                },
            });

            const videoData = response.data;

            video.value = {
                ...videoData,
                video_url: videoData.video_url ?
                    `/storage/${videoData.video_url.replace(/^public\//, "")}` : convertToEmbedUrl(
                        videoData.link_youtube) || "",
                thumbnailUrl: videoData.thumbnail ?
                    `/storage/${videoData.thumbnail.replace(/^public\//, "")}` :
                    "/default-thumbnail.jpg",
                tags: videoData.tag ? videoData.tag.split(/,\s*/) : [],
                user: videoData.user ? {
                    ...videoData.user,
                    avatar: getMediaUrl(videoData.user.photo_profile),
                    photo_profile: videoData.user.photo_profile
                } : null,
                created_at: videoData.created_at,
                collection_date: videoData.metadata_video ?.collection_date,
                file_size: videoData.metadata_video ?.file_size,
                duration: videoData.metadata_video ?.duration,
                location: videoData.metadata_video ?.location,
                frame_rate: videoData.metadata_video ?.frame_rate,
                resolution: videoData.metadata_video ?.resolution,
                codec_video_audio: videoData.metadata_video ?.codec_video_audio,
                format_file: videoData.metadata_video ?.format_file,
            };

            // Check bookmark status after video data is loaded
            await checkIfBookmarked();

            // Set dummy data for likes and comments
            likeCount.value = Math.floor(Math.random() * 100);
            isLiked.value = Math.random() > 0.5;

            // Fetch comments if the photo has an ID
            if (video.value.id) {
                await fetchComments(video.value.id);
            }
        } catch (error) {
            console.error("Error fetching video:", error);
        } finally {
            loading.value = false;
        }
    });

</script>

<style scoped>
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
