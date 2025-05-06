<template>
    <MainLayout>
        <div class="bg-[#0d0d0d] min-h-screen w-full">
            <div class="max-w-7xl mx-auto px-4 py-8 mt-14 sm:py-12 lg:py-16">
                <!-- Profile Section -->
                <div class="flex flex-col mb-8 sm:mb-12">
                    <!-- Profile + Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                        <!-- Profile Info -->
                        <div class="flex flex-col col-span-1 items-center md:items-start ">
                            <div class="relative w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 mb-4">
                                <img :src="profilePhoto" alt="Profile Photo"
                                    class="w-full h-full object-cover rounded-full z-10 relative" />
                                <div
                                    class="absolute top-0 left-0 w-full h-full border-4 border-white rounded-full blur-sm z-0" />
                                <div
                                    class="absolute top-0 left-0 w-full h-full border border-white rounded-full z-10" />
                            </div>

                            <div class="text-white text-center md:text-left">
                                <h1 class="text-xl sm:text-2xl md:text-3xl font-medium mb-2">{{ userData?.name }}</h1>
                                <p class="text-base sm:text-lg md:text-xl mb-1">{{ userData?.role }}</p>
                                <p class="text-base sm:text-lg md:text-xl">{{ userData?.email }}</p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex col-span-2 justify-center md:align-left items-center">
                            <div class="flex flex-wrap justify-around gap-4 sm:gap-8 md:gap-12 lg:mb-20">
                                <div class="flex flex-col items-center">
                                    <span class="text-white text-xl sm:text-2xl md:text-3xl font-semibold">{{ totalPost }}</span>
                                    <span class="text-white text-xs sm:text-sm md:text-base">POSTINGAN</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-white text-xl sm:text-2xl md:text-3xl font-semibold">{{ totalLikes }}</span>
                                    <span class="text-white text-xs sm:text-sm md:text-base">LIKE</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-white text-xl sm:text-2xl md:text-3xl font-semibold">{{ totalFavorites }}</span>
                                    <span class="text-white text-xs sm:text-sm md:text-base">FAVORITE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="min-h-screen text-white">
                    <!-- Navigation Tabs -->
                    <div class="border-b border-gray-700 py-3 sm:py-4">
                        <div class="container flex justify-center space-x-3 sm:space-x-6 md:space-x-8">
                            <button
                                :class="['font-medium flex items-center text-sm sm:text-base', activeTab === 'photo' ? 'text-white' : 'text-gray-400']"
                                @click="activeTab = 'photo'">
                                <span class="mr-1 sm:mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6">
                                        <path fill-rule="evenodd"
                                            d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                PHOTO
                            </button>

                            <button
                                :class="['font-medium flex items-center text-sm sm:text-base', activeTab === 'video' ? 'text-white' : 'text-gray-400']"
                                @click="activeTab = 'video'">
                                <span class="mr-1 sm:mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6">
                                        <path fill-rule="evenodd"
                                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                VIDEO
                            </button>

                            <button
                                :class="['font-medium flex items-center text-sm sm:text-base', activeTab === 'favorit' ? 'text-white' : 'text-gray-400']"
                                @click="activeTab = 'favorit'">
                                <span class="mr-1 sm:mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6">
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
                    <div class="mt-6 sm:mt-8 md:mt-12 container mx-auto">
                        <!-- photo -->
                        <div v-if="activeTab === 'photo'"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-1 sm:gap-2 md:gap-4">
                            <div v-for="photo in userPhotos" :key="'photo-' + photo.id"
                                class="aspect-square overflow-hidden rounded-md sm:rounded-lg shadow-md cursor-pointer">
                                <img :src="getMediaUrl(photo.image_url)" :alt="photo.title"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                            </div>
                        </div>

                        <!-- video -->
                        <div v-if="activeTab === 'video'" 
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-1 sm:gap-2 md:gap-4">
                            <div v-for="video in userVideos" :key="'video-' + video.id"
                                class="aspect-square overflow-hidden relative rounded-md sm:rounded-lg shadow-md cursor-pointer">
                                <img :src="getMediaUrl(video.thumbnail)" :alt="video.title"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-white opacity-80">
                                        <path fill-rule="evenodd"
                                            d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286l-11.54 6.347c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- favorit -->
                        <div v-if="activeTab === 'favorit'" class="text-center text-gray-400 py-10 sm:py-16 md:py-20">
                            <div class="text-sm sm:text-base md:text-lg">Konten yang ditandai akan tampil di sini.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import MainLayout from '@/Layouts/MainLayout.vue'

// Get authenticated user from Inertia
const page = usePage()
const authUser = ref(page.props.auth.user)

const activeTab = ref('photo')
const userData = ref(null)
const profilePhoto = ref('/default-profile.jpg') // Default fallback image

const totalPost = ref(0)
const totalLikes = ref(0)
const totalFavorites = ref(0)

const userPhotos = ref([])
const userVideos = ref([])

// Improved media URL handler
const getMediaUrl = (url) => {
    if (!url) return '/default-post.jpg';
    
    // Jika sudah URL lengkap, langsung return
    if (url.startsWith('http')) return url;
    
    // Normalisasi path secara menyeluruh
    const cleanPath = url
        .replace(/^storage\//, '')     // Hapus awalan storage/
        .replace(/^public\//, '')      // Hapus awalan public/
        .replace(/^\//, '')            // Hapus slash di awal
        .replace(/^storage\//, '');    // Hapus storage/ lagi jika masih ada
    
    // Pastikan tidak ada path kosong
    if (!cleanPath) return '/default-post.jpg';
    
    return `/storage/${cleanPath}`;
}

onMounted(async () => {
    try {
        if (!authUser.value?.id) {
            console.error('No authenticated user found')
            return
        }
        
        const userId = authUser.value.id
        console.log('Loading profile data for user:', userId)

        // 1. Get user details with profile photo
        const { data: userDataResponse } = await axios.get(`/api/users/${userId}`)
        userData.value = userDataResponse
        
        // Set profile photo with proper URL handling
        profilePhoto.value = userDataResponse.profile_photo_url 
            ? getMediaUrl(userDataResponse.profile_photo_url)
            : '/default-profile.jpg'

        // 2. Get user photos
        const { data: photosResponse } = await axios.get(`/api/content-photo/user/${userId}`)
        userPhotos.value = Array.isArray(photosResponse?.photos) 
            ? photosResponse.photos.map(photo => ({
                ...photo,
                image_url: getMediaUrl(photo.image_url)
            }))
            : []
        
        // 3. Calculate photo likes
        const photoLikes = userPhotos.value.reduce((sum, photo) => {
            return sum + (photo.user_comments?.reduce((cSum, comment) => 
                cSum + (comment.user_reactions?.length || 0), 0) || 0)
        }, 0)

        // 4. Get favorites count
        const { data: favoritesResponse } = await axios.get(`/api/favorite/user/${userId}`)

// Mengolah response sesuai struktur yang diberikan
totalFavorites.value = favoritesResponse?.total || 0
// favoriteItems.value = favoritesResponse?.data || [] // Jika ingin menyimpan data favorit

console.log('Total favorites:', totalFavorites.value)
// console.log('Favorite items:', favoriteItems.value)
        // 5. Get user videos
        const { data: videosResponse } = await axios.get(`/api/content-video/user/${userId}`)
        userVideos.value = Array.isArray(videosResponse?.videos)
            ? videosResponse.videos.map(video => ({
                ...video,
                thumbnail: getMediaUrl(video.thumbnail),
                video_url: getMediaUrl(video.video_url)
            }))
            : []
        
        // 6. Calculate video likes (if available)
        const videoLikes = userVideos.value.reduce((sum, video) => {
            return sum + (video.user_comments?.reduce((cSum, comment) => 
                cSum + (comment.user_reactions?.length || 0), 0) || 0)
        }, 0)

        // 7. Calculate totals
        totalPost.value = userPhotos.value.length + userVideos.value.length
        totalLikes.value = photoLikes + videoLikes

        console.log('Profile data loaded successfully:', {
            user: userData.value,
            photos: userPhotos.value.length,
            videos: userVideos.value.length,
            likes: totalLikes.value,
            favorites: totalFavorites.value
        })

    } catch (error) {
        console.error('Error loading profile data:', {
            message: error.message,
            response: error.response?.data,
            stack: error.stack
        })
    }
})
</script>