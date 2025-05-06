<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">
                    Gallery
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Grid Card -->
            <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <ImageCard v-for="photo in photos" :key="photo.slug" :imageUrl="photo.imageUrl" :title="photo.title"
                    :description="photo.description || 'No description available'" :userId="photo.user_id"
                    :userName="photo.user?.name || 'Unknown Photographer'"
                    :userAvatar="photo.user?.avatar" :titleSize="'lg'"
                    @click="getDetailPage(photo.slug)" />
            </div>
        </div>
    </section>
</template>

<script setup>
    import {
        ref,
        onMounted
    } from 'vue';
    import ImageCard from './ImageCard.vue';
    import axios from 'axios';

    const photos = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const selectedPhoto = ref(null);
    const isLiked = ref(false);
    const isSaved = ref(false);
    const likeCount = ref(Math.floor(Math.random() * 100) + 5); // Random initial like count
    const slug = window.location.pathname.split('/').pop(); // ambil slug dari URL
    const slug1 = window.location.pathname.split('/').pop(); // ambil slug dari URL

    const getDetailPage = (slug) => {
        window.location.href = `/gallery-photo/${slug1}/${slug}`;
    };

    onMounted(async () => {
        const options = {
            method: 'GET',
            url: `http://127.0.0.1:8000/api/category-photo/${slug}`,
            headers: {
                Accept: 'application/json',
                Authorization: 'Bearer 123'
            }
        };

        try {
            const response = await axios.request(options);

            const photoArray = Array.isArray(response.data.photos) ?
                response.data.photos :
                response.data.photos.data || [];



            photos.value = photoArray.map(item => {
                const photo = item.content_photo;
                return {
                    ...photo,
                    imageUrl: photo.image_url ?
                        (photo.image_url.startsWith('http') ?
                            photo.image_url :
                            `/storage/${photo.image_url.replace(/^public\//, '')}`) :
                        '/default-photo.jpg',
                    title: photo.title || 'Untitled',
                    titleSize: 'base',
                    description: photo.description || 'No description available',
                    slug: photo.slug,
                    altText: photo.alt_text || '',
                    tags: photo.tag ? photo.tag.split(', ') : [],
                    user_id: photo.user_id,
                    user: item.user || null,
                    category: item.category // Include category info if needed
                };
            });


            // Set dummy data untuk demo - applied to each photo if needed
            photos.value = photos.value.map(photo => ({
                ...photo,
                likeCount: Math.floor(Math.random() * 100),
                isLiked: Math.random() > 0.5,
                isBookmarked: Math.random() > 0.5,

                // Set dummy komentar for each photo if needed
                comments: [{
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
                ]
            }));

        } catch (err) {

            error.value = 'Gagal mengambil data';
        } finally {
            loading.value = false;
        }
    });

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

</script>

<style scoped>
    /* Tambahan animasi untuk modal */
    .modal-enter-active,
    .modal-leave-active {
        transition: opacity 0.3s ease;
    }

    .modal-enter-from,
    .modal-leave-to {
        opacity: 0;
    }

</style>
