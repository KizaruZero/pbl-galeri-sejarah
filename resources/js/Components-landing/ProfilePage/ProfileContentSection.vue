<template>
    <div class="min-h-screen text-black dark:text-white">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-700 py-3 sm:py-4">
            <div class="container flex justify-center space-x-3 sm:space-x-6 md:space-x-8">
                <button v-for="tab in tabs" :key="tab.id" :class="[
                        'font-medium flex items-center text-sm sm:text-base',
                        activeTab === tab.id ? 'text-black dark:text-white' : 'text-gray-500 dark:text-gray-400',
                    ]" @click="$emit('change-tab', tab.id)">
                    <span class="mr-1 sm:mr-2">
                        <component :is="tab.icon" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" />
                    </span>
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <!-- Media Grid -->
        <div class="mt-6 sm:mt-8 md:mt-12 container mx-auto px-2 sm:px-4">
            <!-- Photo Grid -->
            <div v-if="activeTab === 'photo'"
                class="grid grid-cols-3 gap-2 sm:gap-3 md:gap-4">
                <div v-for="photo in props.photos" :key="'photo-' + photo.id"
                    class="rounded-md sm:rounded-lg shadow-md cursor-pointer relative group">
                    <!-- Edit button and status in top right corner - Improved responsive layout -->
                    <div class="absolute top-1 right-1 sm:top-2 sm:right-2 flex items-center gap-1 sm:gap-2 z-10">
                        <!-- Status badge - Responsive sizing -->
                        <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-full text-[10px] xs:text-xs sm:text-sm font-semibold" :class="{
                            'bg-green-500 text-white': photo.status === 'approved',
                            'bg-red-500 text-white': photo.status === 'rejected',
                            'bg-blue-500 text-white': photo.status === 'pending',
                        }">
                            {{ photo.status }}
                        </span>
                        <!-- Action buttons container -->
                        <div class="flex items-center gap-1">
                            <!-- Edit button - Responsive sizing -->
                            <button class="bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-1 sm:p-1.5 shadow-md transition-all"
                                @click.stop="editPhoto(photo)"
                                title="Edit photo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 xs:h-3 xs:w-3 sm:h-3.5 sm:w-3.5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <!-- Delete button - Responsive sizing -->
                            <button class="bg-red-500 bg-opacity-75 hover:bg-opacity-100 rounded-full p-1 sm:p-1.5 shadow-md transition-all"
                                @click.stop="deletePhoto(photo)"
                                title="Delete photo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 xs:h-3 xs:w-3 sm:h-3.5 sm:w-3.5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Photo container -->
                    <div class="aspect-square overflow-hidden relative"
                        @click="photo.status !== 'pending' && navigateToPhotoDetail(photo)"
                        :class="{'cursor-not-allowed': photo.status === 'pending', 'cursor-pointer': photo.status !== 'pending'}">
                        <img :src="photo.image_url" :alt="photo.title"
                            :onerror="(e) => (e.target.src = '/js/Assets/default-photo.jpg')"
                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" />

                        <!-- Title overlay at bottom of photo - Improved responsive sizing -->
                        <div
                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-1.5 sm:p-2 md:p-3">
                            <div class="text-white font-medium text-xs xs:text-sm sm:text-base line-clamp-1">
                                {{ photo.title }}
                            </div>
                        </div>
                    </div>

                    <!-- Rejection note - Improved responsive sizing -->
                    <div v-if="photo.status === 'rejected'"
                        class="mt-0.5 text-center text-[10px] xs:text-xs sm:text-sm font-medium text-red-200 px-1">
                        Rejected: {{ photo.note }}
                    </div>
                </div>
            </div>

            <!-- Video Grid -->
            <div v-if="activeTab === 'video'"
                class="grid grid-cols-3 gap-2 sm:gap-3 md:gap-4">
                <div v-for="video in props.videos" :key="'video-' + video.id"
                    class="rounded-md sm:rounded-lg shadow-md cursor-pointer relative group">
                    <!-- Edit button and status in top right corner - Improved responsive layout -->
                    <div class="absolute top-1 right-1 sm:top-2 sm:right-2 flex items-center gap-1 sm:gap-2 z-10">
                        <!-- Status badge - Responsive sizing -->
                        <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-full text-[10px] xs:text-xs sm:text-sm font-semibold" :class="{
                            'bg-green-500 text-white': video.status === 'approved',
                            'bg-red-500 text-white': video.status === 'rejected',
                            'bg-blue-500 text-white': video.status === 'pending',
                        }">
                            {{ video.status }}
                        </span>
                        <!-- Action buttons container -->
                        <div class="flex items-center gap-1">
                            <!-- Edit button - Responsive sizing -->
                            <button class="bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-1 sm:p-1.5 shadow-md transition-all"
                                @click.stop="editVideo(video)"
                                title="Edit video">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 xs:h-3 xs:w-3 sm:h-3.5 sm:w-3.5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <!-- Delete button - Responsive sizing -->
                            <button class="bg-red-500 bg-opacity-75 hover:bg-opacity-100 rounded-full p-1 sm:p-1.5 shadow-md transition-all"
                                @click.stop="deleteVideo(video)"
                                title="Delete video">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 xs:h-3 xs:w-3 sm:h-3.5 sm:w-3.5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Video container -->
                    <div class="aspect-square overflow-hidden relative"
                        @click="video.status !== 'pending' && navigateToVideoDetail(video)"
                        :class="{'cursor-not-allowed': video.status === 'pending', 'cursor-pointer': video.status !== 'pending'}">
                        <img :src="video.thumbnail" :alt="video.title"
                            :onerror="(e) => (e.target.src = '/js/Assets/default-photo.jpg')"
                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" />

                        <!-- Play button overlay - Responsive sizing -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 text-white opacity-80">
                                <path fill-rule="evenodd"
                                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286l-11.54 6.347c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Title overlay at bottom of video - Improved responsive sizing -->
                        <div
                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-1.5 sm:p-2 md:p-3">
                            <div class="text-white font-medium text-xs xs:text-sm sm:text-base line-clamp-1">
                                {{ video.title }}
                            </div>
                        </div>
                    </div>

                    <!-- Rejection note - Improved responsive sizing -->
                    <div v-if="video.status === 'rejected'"
                        class="mt-0.5 text-center text-[10px] xs:text-xs sm:text-sm font-medium text-red-200 px-1">
                        Rejected: {{ video.note }}
                    </div>
                </div>
            </div>

            <!-- Favorites State -->
            <div v-if="activeTab === 'favorit'" class="container mx-auto px-2 sm:px-4">
                <!-- Empty State -->
                <div v-if="(!photoFavorites || photoFavorites.length === 0) && (!videoFavorites || videoFavorites.length === 0)"
                    class="text-center text-gray-400 py-10 sm:py-16 md:py-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-4"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-base sm:text-lg md:text-xl">No favorites yet</p>
                    <p class="text-xs sm:text-sm md:text-base mt-2">
                        Start adding photos and videos to your favorites!
                    </p>
                </div>

                <!-- Combined Favorites Grid -->
                <div v-if="(photoFavorites && photoFavorites.length > 0) || (videoFavorites && videoFavorites.length > 0)"
                    class="grid grid-cols-3 gap-2 sm:gap-3 md:gap-4 mb-4">
                    <!-- Photo Favorites -->
                    <div v-for="favorite in photoFavorites" :key="'photo-' + favorite.id"
                        class="aspect-square overflow-hidden rounded-md sm:rounded-lg shadow-md cursor-pointer"
                        @click="navigateToPhotoDetail(favorite.content_photo || favorite)">
                        <img :src="'/storage/' + (favorite.content_photo?.image_url || favorite.image_url)"
                            :alt="favorite.content_photo?.title || 'Favorite photo'"
                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                    </div>

                    <!-- Video Favorites -->
                    <div v-for="favorite in videoFavorites" :key="'video-' + favorite.id"
                        class="aspect-square overflow-hidden relative rounded-md sm:rounded-lg shadow-md cursor-pointer"
                        @click="navigateToVideoDetail(favorite.content_video || favorite)">
                        <img :src="'/storage/' + (favorite.content_video?.thumbnail || favorite.thumbnail)"
                            :alt="favorite.content_video?.title || favorite.title"
                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out hover:scale-105" />
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-white opacity-80">
                                <path fill-rule="evenodd"
                                    d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286l-11.54 6.347c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ref,
    onMounted
} from "vue";
import axios from "axios";
import Swal from "sweetalert2";

// Tab Icons
const PhotoIcon = {
    template: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path fill-rule="evenodd"
        d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
        clip-rule="evenodd" />
    </svg>
  `,
};

const VideoIcon = {
    template: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path fill-rule="evenodd"
        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z"
        clip-rule="evenodd" />
    </svg>
  `,
};

const FavoriteIcon = {
    template: `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path fill-rule="evenodd"
        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
        clip-rule="evenodd" />
    </svg>
  `,
};

const tabs = [{
        id: "photo",
        label: "PHOTO",
        icon: PhotoIcon
    },
    {
        id: "video",
        label: "VIDEO",
        icon: VideoIcon
    },
    {
        id: "favorit",
        label: "FAVORITE",
        icon: FavoriteIcon
    },
];
const slug = window.location.pathname.split("/").pop(); // ambil slug dari URL


const navigateToVideoDetail = (video) => {
    if (!video || !video.slug) return;
    const categorySlug = video.category_contents?.[0]?.category?.slug || 'uncategorized';
    window.location.href = `/gallery-video/${categorySlug}/${video.slug}`;
};

const navigateToPhotoDetail = (photo) => {
    if (!photo || !photo.slug) return;
    window.location.href = `/gallery-photo/${photo.slug}`;
};

const editPhoto = (photo) => {
    window.location.href = `/update-photo/${photo.id}`;
};

const editVideo = (video) => {
    window.location.href = `/update-video/${video.id}`;
};

const props = defineProps({
    activeTab: String,
    photos: Array,
    videos: Array,
    photoFavorites: Array,
    videoFavorites: Array,
});

onMounted(() => {
    console.log("Photo Favorites:", props.photoFavorites);
    console.log("Video Favorites:", props.videoFavorites);
});

const deletePhoto = async (photo) => {
    try {
        const result = await Swal.fire({
            title: 'Delete Photo?',
            text: `Are you sure you want to delete "${photo.title}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            const response = await axios.delete(`/api/content-photo/${photo.id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                }
            });

            if (response.status === 200) {
                await Swal.fire({
                    title: 'Deleted!',
                    text: 'Your photo has been deleted successfully.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Emit event to parent to refresh the photos list
                emits('photo-deleted', photo.id);
                
                // Reload the page to refresh the content
                window.location.reload();
            }
        }
    } catch (error) {
        console.error('Error deleting photo:', error);
        
        let errorMessage = 'Failed to delete photo. Please try again.';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        }

        await Swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    }
};

const deleteVideo = async (video) => {
    try {
        const result = await Swal.fire({
            title: 'Delete Video?',
            text: `Are you sure you want to delete "${video.title}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            const response = await axios.delete(`/api/content-video/${video.id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                }
            });

            if (response.status === 200) {
                await Swal.fire({
                    title: 'Deleted!',
                    text: 'Your video has been deleted successfully.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Emit event to parent to refresh the videos list
                emits('video-deleted', video.id);
                
                // Reload the page to refresh the content
                window.location.reload();
            }
        }
    } catch (error) {
        console.error('Error deleting video:', error);
        
        let errorMessage = 'Failed to delete video. Please try again.';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        }

        await Swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    }
};

const emits = defineEmits(["change-tab", "photo-deleted", "video-deleted"]);
</script>
