<template>
    <MainLayout>
        <div class="bg-[#0d0d0d] min-h-screen w-full">
            <div class="max-w-7xl mx-auto px-4 py-12 sm:py-16 mt-10 sm:mt-15">
                <!-- Profile Section -->
                <div class="flex flex-col mb-12">
                    <!-- Foto + Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 items-start gap-8">
                        <!-- Foto Profil -->
                        <div class="flex flex-col items-center sm:items-start">
                            <div class="relative w-40 h-40 sm:w-48 sm:h-48 mb-4">
                                <img :src="profilePhoto" alt="Profile Photo"
                                    class="w-full h-full object-cover rounded-full z-10 relative" />
                                <div
                                    class="absolute top-0 left-0 w-full h-full border-4 border-white rounded-full blur-sm z-0" />
                                <div
                                    class="absolute top-0 left-0 w-full h-full border border-white rounded-full z-10" />
                            </div>

                            <div class="text-white text-center sm:text-left">
                                <h1 class="text-2xl sm:text-3xl font-medium mb-2">{{ user?.name }}</h1>
                                <p class="text-lg sm:text-xl mb-1">{{ user?.role }}</p>
                                <p class="text-lg sm:text-xl">{{ user?.email }}</p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex justify-around sm:justify-center sm:gap-16 mt-4 mb-8 sm:mb-40">
                            <div class="flex flex-col items-center">
                                <span class="text-white text-2xl sm:text-3xl font-medium">{{ totalPost }}</span>
                                <span class="text-white text-base sm:text-xl">POSTINGAN</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-white text-2xl sm:text-3xl font-medium">{{ totalLikes }}</span>
                                <span class="text-white text-base sm:text-xl">LIKE</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-white text-2xl sm:text-3xl font-medium">{{ totalFavorites }}</span>
                                <span class="text-white text-base sm:text-xl">FAVORITE</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="min-h-screen text-white">
                    <!-- Top Navigation Bar -->
                    <div class="border-b border-gray-700 py-4 flex justify-center">
                        <div class="container flex justify-center space-x-8">
                            <button
                                :class="['font-medium flex items-center', activeTab === 'posts' ? 'text-white' : 'text-gray-400']"
                                @click="activeTab = 'posts'">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path fill-rule="evenodd"
                                            d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </span>
                                PHOTO
                            </button>

                            <button
                                :class="['font-medium flex items-center', activeTab === 'saved' ? 'text-white' : 'text-gray-400']"
                                @click="activeTab = 'saved'">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path fill-rule="evenodd"
                                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </span>
                                VIDEO
                            </button>

                            <button
                                :class="['font-medium flex items-center', activeTab === 'tagged' ? 'text-white' : 'text-gray-400']"
                                @click="activeTab = 'tagged'">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="size-6">
                                        <path fill-rule="evenodd"
                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                FAVORITE
                            </button>

                        </div>
                    </div>

                    <!-- Media Grid -->
                    <div class="mt-12 container mx-auto px-1">
                        <!-- POSTS -->
                        <div v-if="activeTab === 'posts'"
                            class="grid grid-cols-3 sm:grid-cols-2 lg:grid-cols-3 gap-1 sm:gap-4">
                            <div v-for="photo in userPhotos" :key="'photo-' + photo.id"
                                class="aspect-square overflow-hidden rounded-lg shadow-md cursor-pointer">
                                <img :src="getMediaUrl(photo.image_url)" :alt="photo.title"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                            </div>

                            <div v-for="video in userVideos" :key="'video-' + video.id"
                                class="aspect-square overflow-hidden relative rounded-lg shadow-md cursor-pointer">
                                <img :src="getMediaUrl(video.thumbnail)" :alt="video.title"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <!-- play icon -->
                                </div>
                            </div>
                        </div>

                        <!-- SAVED -->
                        <div v-if="activeTab === 'saved'" class="text-center text-gray-400 py-20">
                            Konten yang disimpan akan tampil di sini.
                        </div>

                        <!-- TAGGED -->
                        <div v-if="activeTab === 'tagged'" class="text-center text-gray-400 py-20">
                            Konten yang ditandai akan tampil di sini.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </MainLayout>
</template>


<script setup>
    import {
        ref,
        onMounted
    } from 'vue'
    import axios from 'axios'
    import MainLayout from '@/Layouts/MainLayout.vue'

    const activeTab = ref('posts')
    const userId = ref(1)
    const user = ref(null)
    const profilePhoto = ref('/default-profile.jpg')

    const totalPost = ref(0)
    const totalLikes = ref(0)
    const totalFavorites = ref(0)

    const userPhotos = ref([])
    const userVideos = ref([])

    const getMediaUrl = (url) => {
        if (!url) return '/default-post.jpg'
        return url.startsWith('http') ? url : `/storage/${url.replace(/^public\//, '')}`
    }

    onMounted(async () => {
        try {
            const {
                data: userData
            } = await axios.get(`http://127.0.0.1:8000/api/users/${userId.value}`)
            user.value = userData

            if (userData.photo_profile) {
                profilePhoto.value = userData.photo_profile.startsWith('http') ?
                    userData.photo_profile :
                    `/storage/${userData.photo_profile.replace(/^public\//, '')}`
            }

            const {
                data: photos
            } = await axios.get(`http://127.0.0.1:8000/api/content-photo/user/${userId.value}`)
            userPhotos.value = Array.isArray(photos) ? photos : []
            const photoCount = userPhotos.value.length
            const photoLikes = userPhotos.value.reduce((sum, item) => sum + (item.user_reactions ?.length || 0), 0)

            const {
                data: videos
            } = await axios.get(`http://127.0.0.1:8000/api/content-video`)
            userVideos.value = Array.isArray(videos) ? videos.filter(video => video.user_id === userId
                .value) : []
            const videoCount = userVideos.value.length
            const videoLikes = userVideos.value.reduce((sum, item) => sum + (item.user_reactions ?.length || 0), 0)

            const {
                data: favoriteCount
            } = await axios.get(`http://127.0.0.1:8000/api/favorite/user/${userId.value}`)

            totalPost.value = photoCount + videoCount
            totalLikes.value = photoLikes + videoLikes
            totalFavorites.value = favoriteCount || 0

        } catch (error) {
            console.error('Gagal mengambil data:', error)
        }
    })

</script>
