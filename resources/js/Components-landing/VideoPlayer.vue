<template>
    <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center">
        <!-- Add backdrop overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-80"></div>

        <div class="relative w-full max-w-4xl mx-auto z-[10000]">
            <!-- Close button -->
            <button @click="close"
                class="absolute -top-10 right-0 text-white text-2xl p-2 hover:text-gray-300">&times;</button>

            <div class="bg-gray-900 rounded-lg overflow-hidden">
                <!-- Video player -->
                <video ref="videoRef" class="w-full" style="max-height: 500px;" controls autoplay :src="videoUrl"></video>

                <!-- Reactions section (Instagram-like) -->
                <div class="p-4 text-white">
                    <!-- Reactions buttons -->
                    <div class="flex items-center space-x-4 mb-3">
                        <button @click="toggleLike"
                            class="focus:outline-none transition-transform duration-200 active:scale-125"
                            :class="{ 'text-red-500': isLiked }">
                            <svg v-if="isLiked" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>

                        <button @click="toggleSave"
                            class="focus:outline-none transition-transform duration-200 active:scale-125 ml-auto"
                            :class="{ 'text-yellow-500': isSaved }">
                            <svg v-if="isSaved" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Like count -->
                    <p class="font-bold text-sm mb-2">{{ likeCount }} likes</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {
        ref,
        watch,
        defineProps,
        defineEmits,
        onUnmounted
    } from 'vue';

    const props = defineProps({
        isOpen: Boolean,
        videoUrl: String,
        videoId: [String, Number]
    });

    const emit = defineEmits(['close']);
    const videoRef = ref(null);

    // Reaction states
    const isLiked = ref(false);
    const isSaved = ref(false);
    const likeCount = ref(Math.floor(Math.random() * 100) + 5); // Random initial like count
    const comments = ref([{
            username: 'user123',
            text: 'Amazing video!'
        },
        {
            username: 'traveler',
            text: 'Love the cinematography!'
        }
    ]);
    const showCommentInput = ref(false);
    const newComment = ref('');

    // Close the video player
    const close = () => {
        if (videoRef.value) {
            videoRef.value.pause();
        }
        emit('close');
    };

    // Handle Escape key to close the player
    const handleKeyDown = (e) => {
        if (e.key === 'Escape' && props.isOpen) {
            close();
        }
    };

    // Toggle like status
    const toggleLike = () => {
        if (isLiked.value) {
            likeCount.value--;
        } else {
            likeCount.value++;
        }
        isLiked.value = !isLiked.value;

        // Here you would typically send an API request to update the like status
        // saveReaction('like', isLiked.value);
    };

    // Toggle save status
    const toggleSave = () => {
        isSaved.value = !isSaved.value;

        // Here you would typically send an API request to update the save status
        // saveReaction('save', isSaved.value);
    };

    // Add a new comment
    const addComment = () => {
        if (newComment.value.trim()) {
            comments.value.push({
                username: 'currentUser', // In a real app, this would be the actual username
                text: newComment.value.trim()
            });

            // Here you would typically send an API request to save the comment
            // saveComment(newComment.value);

            newComment.value = '';
            showCommentInput.value = false;
        }
    };

    // Function to save reactions to backend (mock)
    const saveReaction = (type, value) => {
        console.log(`Saving ${type} reaction: ${value} for video ${props.videoId}`);
        // Implement API call to save reaction
    };

    // Function to save comment to backend (mock)
    const saveComment = (comment) => {
        console.log(`Saving comment: "${comment}" for video ${props.videoId}`);
        // Implement API call to save comment
    };

    // Add event listener for Escape key
    watch(() => props.isOpen, (newVal) => {
        if (newVal) {
            document.addEventListener('keydown', handleKeyDown);
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        } else {
            document.removeEventListener('keydown', handleKeyDown);
            document.body.style.overflow = ''; // Re-enable scrolling
        }
    });

    // Cleanup on component unmount
    onUnmounted(() => {
        document.removeEventListener('keydown', handleKeyDown);
        document.body.style.overflow = ''; // Ensure scrolling is re-enabled
    });

</script>

<style scoped>
/* Add these styles to ensure proper stacking and prevent pointer events on background */
.fixed {
    isolation: isolate;
}
</style>
