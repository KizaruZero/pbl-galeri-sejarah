<template>
    <MainLayout>
        <div class="min-h-screen bg-black py-12 px-4 sm:px-6 lg:px-8 mt-12">
            <div class="max-w-4xl mx-auto mt-6">
                <!-- Tombol kembali -->
                <button @click="$router.go(-1)" class="mb-6 flex items-center text-gray-600 hover:text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back
                </button>

                <!-- Foto utama -->
                <div class="card">
                    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <img :src="photo.imageUrl" :alt="photo.altText"
                            class="w-full h-auto max-h-[70vh] object-contain">

                        <!-- Info foto -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h1 class="text-2xl font-bold text-black">{{ photo.title }}</h1>
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
                                        <span>{{ likeCount }}</span>
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

                            <p class="text-gray-200 mb-6">{{ photo.description }}</p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span v-for="tag in photo.tags" :key="tag"
                                    class="px-3 py-1 bg-gray-200 text-black rounded-full text-sm">
                                    {{ tag }}
                                </span>
                            </div>

                            <!-- Komentar -->
                            <div class="border-t border-gray-200 pt-6">
                                <h2 class="text-xl font-semibold mb-4">Comments ({{ comments.length }})</h2>

                                <!-- Form komentar baru -->
                                <div class="mb-6">
                                    <textarea v-model="newComment" placeholder="Add a comment..."
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        rows="3"></textarea>
                                    <button @click="addComment" :disabled="!newComment.trim()"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-blue-300">
                                        Post Comment
                                    </button>
                                </div>

                                <!-- Daftar komentar -->
                                <div class="space-y-4">
                                    <div v-for="comment in comments" :key="comment.id"
                                        class="bg-gray-700 p-4 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ comment.user }}</p>
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
    import axios from 'axios';

    const photo = ref({});
    const loading = ref(true);
    const isLiked = ref(false);
    const likeCount = ref(0);
    const isBookmarked = ref(false);
    const comments = ref([]);
    const newComment = ref('');

    const slug = window.location.pathname.split('/').pop(); // ambil slug dari URL

    // Format tanggal
    const formatDate = (dateString) => {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
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

    // Tambah komentar
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

    // Hapus komentar
    const deleteComment = (id) => {
        comments.value = comments.value.filter(comment => comment.id !== id);
    };

    // Ambil data foto
    onMounted(async () => {
        try {
            const response = await axios.get(
                `http://127.0.0.1:8000/api/content-photo/${slug}`, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: 'Bearer 123'
                    }
                }
            );


            photo.value = {
                ...response.data,
                imageUrl: response.data.image_url ?
                    (response.data.image_url.startsWith('http') ?
                        response.data.image_url :
                        `/storage/${response.data.image_url.replace(/^public\//, '')}`) :
                    '/default-photo.jpg',
                altText: response.data.alt_text || '',
                tags: response.data.tags || []
            };

            // Set dummy data untuk demo
            likeCount.value = Math.floor(Math.random() * 100);
            isLiked.value = Math.random() > 0.5;
            isBookmarked.value = Math.random() > 0.5;

            // Set dummy komentar
            comments.value = [{
                    id: 1,
                    user: 'John Doe',
                    text: 'This is an amazing photo! The colors are fantastic.',
                    date: '2023-05-15T10:30:00Z',
                    canDelete: false
                },
                {
                    id: 2,
                    user: 'Jane Smith',
                    text: 'Great composition and lighting!',
                    date: '2023-05-14T16:45:00Z',
                    canDelete: false
                }
            ];
        } catch (error) {
            console.error('Error fetching photo:', error);
        } finally {
            loading.value = false;
        }
    });

</script>

<style scoped>
    /* Animasi untuk tombol like */
    button:active svg {
        transform: scale(1.2);
        transition: transform 0.2s ease;
    }

</style>
