<template>
    <MainLayout>
        <div class="min-h-screen bg-black mt-12">
            <!-- Back Button -->
            <button
                @click="router.go(-1)"
                class="absolute top-4 left-4 z-10 p-2 bg-black/50 rounded-full hover:bg-black/75 transition-colors mt-20"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />
                </svg>
            </button>

            <!-- Main Video Container -->
            <div class="rounded-lg shadow-xl overflow-hidden">
                <!-- Video Player -->
                <div class="relative pt-[3%] bg-black">
                    <video
                        v-if="isLocalVideo"
                        controls
                        :poster="video.thumbnailUrl"
                        class="w-full h-auto max-h-[70vh] object-contain bg-gray-950"
                    >
                        <source :src="video.video_url" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>

                    <iframe
                        v-else
                        class="absolute inset-0 w-full h-full"
                        :src="video.video_url"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    >
                    </iframe>
                </div>

                <!-- Video Info Section -->
                <div class="p-6 ml-14 mr-14">
                    <!-- Title and Actions -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                {{ video.title }}
                            </h1>
                            <div class="flex items-center mt-2 space-x-4 text-sm text-gray-400">
                                <span>{{ video.views }} views</span>
                                <span>{{ formatDate(video.created_at) }}</span>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <!-- Like Button -->
                            <button
                                @click="toggleLike"
                                class="flex items-center space-x-1 group"
                            >
                                <div class="p-1 rounded-full group-hover:bg-red-500/10 transition-colors">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        :class="
                                            isLiked
                                                ? 'text-red-500 fill-current'
                                                : 'text-gray-400 group-hover:text-red-500'
                                        "
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                        />
                                    </svg>
                                </div>
                                <span
                                    :class="
                                        isLiked
                                            ? 'text-red-500'
                                            : 'text-gray-400 group-hover:text-red-500'
                                    "
                                >
                                    {{ likeCount }}
                                </span>
                            </button>

                            <!-- Bookmark Button -->
                            <button
                                @click="toggleBookmark"
                                :disabled="bookmarkLoading"
                                class="flex items-center group disabled:opacity-50"
                            >
                                <div class="p-1 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        :class="
                                            isBookmarked
                                                ? 'text-blue-500 fill-current'
                                                : 'text-gray-400 group-hover:text-blue-500'
                                        "
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                                        />
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Creator Info -->
                    <div class="flex items-center gap-3 mb-6 p-3 rounded-lg">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white/20">
                            <img
                                :src="getMediaUrl(video.user?.photo_profile)"
                                :alt="video.user?.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div>
                            <p class="text-white font-medium">
                                {{ video.user?.name || "Unknown Creator" }}
                            </p>
                            <p class="text-gray-300 text-xs" v-if="video.created_at">
                                Uploaded {{ formatRelativeDate(video.created_at) }}
                            </p>
                            <p class="text-gray-300 text-xs">Content Creator</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <p class="text-gray-300 whitespace-pre-line">
                            {{ video.description }}
                        </p>
                    </div>

                    <div class="border-t border-gray-800 pt-6" />

                    <!-- Video Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-gray-400 text-sm">Collection Date</h3>
                            <p class="text-white">{{ video.collection_date || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">Location</h3>
                            <p class="text-white">{{ video.location || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">Frame Rate</h3>
                            <p class="text-white">{{ video.frame_rate || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">Resolution</h3>
                            <p class="text-white">{{ video.resolution || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">File Size</h3>
                            <p class="text-white">
                                {{ video.file_size ? formatFileSize(parseInt(video.file_size)) : 'Not specified' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">Duration</h3>
                            <p class="text-white">{{ video.duration || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">File Format</h3>
                            <p class="text-white">{{ video.format_file || 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm">Video Audio Codec</h3>
                            <p class="text-white">{{ video.codec_video_audio || 'Not specified' }}</p>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-6" v-if="video.tags && video.tags.length">
                        <router-link
                            v-for="tag in video.tags"
                            :key="tag"
                            :to="`/tags/${tag.toLowerCase()}`"
                            class="px-3 py-1 bg-gray-800 text-gray-300 hover:bg-gray-700 rounded-full text-sm transition-colors"
                        >
                            #{{ tag }}
                        </router-link>
                    </div>

                    <!-- Comments Section -->
                    <div class="border-t border-gray-800 pt-6">
                        <h2 class="text-xl font-semibold text-white mb-4">
                            Comments ({{ comments.length }})
                        </h2>

                        <!-- New Comment Form -->
                        <div class="mb-6">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-700 mt-1">
                                    <img
                                        :src="currentUser.avatar"
                                        :alt="currentUser.name"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div class="flex-1">
                                    <textarea
                                        v-model="newComment"
                                        placeholder="Add a comment..."
                                        class="w-full px-4 py-2 bg-white text-black border border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                                        rows="3"
                                    ></textarea>
                                    <button
                                        @click="addComment"
                                        :disabled="!newComment.trim()"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed transition-colors"
                                    >
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Comments List -->
                        <div class="space-y-4">
                            <div
                                v-for="comment in comments"
                                :key="comment.id"
                                class="bg-white p-4 rounded-lg"
                            >
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-700">
                                            <img
                                                :src="comment.user.avatar"
                                                :alt="comment.user.name"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div>
                                            <p class="font-medium text-black">
                                                {{ comment.user.name }}
                                            </p>
                                            <p class="text-gray-400 text-xs">
                                                {{ formatRelativeDate(comment.date) }}
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        v-if="comment.canDelete"
                                        @click="deleteComment(comment.id)"
                                        class="text-gray-400 hover:text-red-500 transition-colors"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-3 text-black pl-11">
                                    {{ comment.text }}
                                </p>
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
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

const router = useRouter();

// CSRF Token and Headers
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
};

// Helper function for media URLs
const getMediaUrl = (url) => {
    if (!url) return "/default-avatar.jpg";
    if (url.startsWith("http")) return url;

    const cleanPath = url
        .replace(/^storage\//, "")
        .replace(/^public\//, "")
        .replace(/^\//, "");

    return cleanPath ? `/storage/${cleanPath}` : "/default-avatar.jpg";
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

// Current user data
const currentUser = ref({
    id: usePage().props.auth?.user?.id,
    name: usePage().props.auth?.user?.name || "You",
    avatar: getMediaUrl(usePage().props.auth?.user?.profile_photo_url) || "/default-avatar.jpg",
});

// Computed properties
const isLocalVideo = computed(() => {
    return video.value.video_url && !video.value.video_url.includes('youtube.com');
});

// Utility functions
const formatDate = (dateString) => {
    if (!dateString) return "";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const formatRelativeDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    const intervals = [
        { label: 'year', seconds: 31536000 },
        { label: 'month', seconds: 2592000 },
        { label: 'day', seconds: 86400 },
        { label: 'hour', seconds: 3600 },
        { label: 'minute', seconds: 60 }
    ];

    for (const interval of intervals) {
        const count = Math.floor(seconds / interval.seconds);
        if (count >= 1) {
            return `${count} ${interval.label}${count === 1 ? '' : 's'} ago`;
        }
    }

    return `${Math.floor(seconds)} second${seconds === 1 ? '' : 's'} ago`;
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
        const videoId = url.split("v=")[1]?.split("&")[0];
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
            response: error.response?.data,
            status: error.response?.status,
            message: error.message
        });

        if (error.response?.status === 401) {
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
        if (error.response?.status === 401) {
            router.visit('/login');
        }
    }
};

const addComment = () => {
    if (newComment.value.trim()) {
        comments.value.unshift({
            id: Date.now(),
            user: { ...currentUser.value },
            text: newComment.value,
            date: new Date().toISOString(),
            canDelete: true,
        });
        newComment.value = "";
    }
};

const deleteComment = (id) => {
    comments.value = comments.value.filter((comment) => comment.id !== id);
};

// Initialize component
onMounted(async () => {
    try {
        const slug = window.location.pathname.split("/").pop();
        const response = await axios.get(`/api/content-video/${slug}`, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer 123",
            },
        });

        const videoData = response.data;

        video.value = {
            ...videoData,
            video_url: videoData.video_url ?
                `/storage/${videoData.video_url.replace(/^public\//, "")}` :
                convertToEmbedUrl(videoData.link_youtube) || "",
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
            collection_date: videoData.metadata_video?.collection_date,
            file_size: videoData.metadata_video?.file_size,
            duration: videoData.metadata_video?.duration,
            location: videoData.metadata_video?.location,
            frame_rate: videoData.metadata_video?.frame_rate,
            resolution: videoData.metadata_video?.resolution,
            codec_video_audio: videoData.metadata_video?.codec_video_audio,
            format_file: videoData.metadata_video?.format_file,
        };

        // Check bookmark status after video data is loaded
        await checkIfBookmarked();

        // Set dummy data for likes and comments
        likeCount.value = Math.floor(Math.random() * 100);
        isLiked.value = Math.random() > 0.5;

        // Set dummy comments
        comments.value = [
            {
                id: 1,
                user: {
                    id: 2,
                    name: "John Doe",
                    avatar: "/default-avatar.jpg",
                },
                text: "This is an amazing video! The editing is fantastic.",
                date: "2023-05-15T10:30:00Z",
                canDelete: false,
            },
            {
                id: 2,
                user: {
                    id: 3,
                    name: "Jane Smith",
                    avatar: "/default-avatar.jpg",
                },
                text: "Great content and production quality!",
                date: "2023-05-14T16:45:00Z",
                canDelete: false,
            },
        ];
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
