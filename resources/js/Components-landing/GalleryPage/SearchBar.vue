<template>
    <div class="relative flex flex-col items-center w-full bg-white dark:bg-black py-10">
        <div class="relative flex flex-col items-center w-full max-w-[1300px] rounded-lg shadow-lg px-5 py-10">
            <!-- Search Form -->
            <form @submit.prevent="handleSearch" class="w-full mt-8">
                <div class="flex w-full">
                    <!-- Mobile Sidebar Toggle Button -->
                    <button @click.prevent="toggleSidebar" type="button"
                        class="md:hidden px-4 py-3 bg-white dark:bg-black text-black dark:text-white border border-black dark:border-white rounded-l-lg hover:bg-gray-300 dark:hover:bg-gray-800 transition-colors">
                        ☰
                    </button>

                    <!-- Category Dropdown (Desktop) -->
                    <div class="hidden md:block relative">
                        <button @click.prevent="toggleDropdown" type="button"
                            class="z-10 inline-flex items-center px-5 py-3 text-base font-medium text-black dark:text-white bg-white dark:bg-black border border-black dark:border-white rounded-l-lg hover:bg-gray-300 dark:hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-500 transition-colors">
                            {{ selectedCategory }}
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div v-if="showDropdown"
                            class="absolute top-full left-0 w-48 bg-white dark:bg-black text-black dark:text-white rounded-lg shadow-lg mt-2 border border-gray-700 z-50">
                            <ul class="py-2 text-sm">
                                <li v-for="category in categories" :key="category">
                                    <button @click.prevent="
                                            selectCategory(category)
                                        " type="button"
                                        class="block w-full px-4 py-3 text-left hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                                        {{ category }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="flex-grow relative">
                        <input type="search" v-model="searchQuery" @input="handleSearchInput"
                            class="w-full p-3 text-base text-black dark:text-white bg-white dark:bg-black border border-black dark:border-white md:border-l-0 md:rounded-none rounded-r-lg focus:ring-gray-500 focus:border-gray-500"
                            placeholder="Search Mockups, Logos, Design Templates, Users, Locations..." required />
                        <button type="submit"
                            class="absolute top-0 right-0 h-full px-4 text-black dark:text-white bg-white dark:bg-black border border-black dark:border-white rounded-r-lg hover:bg-gray-200 dark:hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </div>
                </div>

                <!-- Search Options -->
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center text-sm text-black dark:text-white">
                            <input type="checkbox" v-model="useFuzzySearch" @change="handleSearchModeChange"
                                class="mr-2" />
                            Use Smart Search (finds similar results)
                        </label>
                        <span v-if="lastSearchTime" class="text-xs text-gray-500">
                            Search completed in {{ lastSearchTime }}ms
                        </span>
                    </div>

                    <!-- Content Type Filter -->
                    <div class="flex items-center space-x-2">
                        <div class="px-3 py-1 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 text-black dark:text-white">
                            All ({{ totalCount }})
                        </div>
                        <div class="px-3 py-1 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 text-black dark:text-white">
                            📸 Photos ({{ photoCount }})
                        </div>
                        <div class="px-3 py-1 rounded-lg text-sm bg-gray-200 dark:bg-gray-700 text-black dark:text-white">
                            🎥 Videos ({{ videoCount }})
                        </div>
                    </div>
                </div>
            </form>

            <!-- Search Results -->
            <div v-if="searchPerformed" class="w-full mt-8">
                <div v-if="loading" class="text-center py-10">
                    <div
                        class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-black dark:border-white">
                    </div>
                    <p class="mt-2 text-black dark:text-white">Searching...</p>
                </div>

                <div v-else>
                    <!-- Search Stats -->
                    <div v-if="totalCount > 0" class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                        <p>
                            Found {{ totalCount }} results
                            {{ searchQuery ? `for "${searchQuery}"` : "" }}
                            {{
                                selectedCategory !== "All categories"
                                    ? `in "${selectedCategory}"`
                                    : ""
                            }}
                            {{
                                useFuzzySearch
                                    ? "(Smart Search)"
                                    : "(Exact Search)"
                            }}
                        </p>
                        <div class="flex space-x-4 mt-2">
                            <span>📸 {{ photoCount }} Photos</span>
                            <span>🎥 {{ videoCount }} Videos</span>
                        </div>
                    </div>

                    <!-- Photo Section -->
                    <div v-if="photoResults.length > 0" class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">📸</span>
                                <h2 class="text-2xl font-bold text-black dark:text-white">
                                    Photos
                                </h2>
                                <span
                                    class="ml-3 px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-sm font-medium">
                                    {{ photoResults.length }} results
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="photo in paginatedPhotoResults" :key="`photo-${photo.id}`"
                                class="bg-zinc-200 dark:bg-zinc-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <a @click.prevent="navigateToDetail(photo)" class="block cursor-pointer">
                                    <div class="relative">
                                        <img :src="photo.image_url" :alt="photo.title"
                                            class="w-full h-48 object-cover" @error="handleImageError" />
                                        <div
                                            class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                            📸 PHOTO
                                        </div>
                                        <div v-if="photo.score"
                                            class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs">
                                            {{
                                                Math.round(
                                                    (1 - photo.score) * 100
                                                )
                                            }}% match
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-black dark:text-white font-semibold text-lg mb-2 line-clamp-2">
                                            {{ photo.title }}
                                        </h3>
                                        <p class="text-gray-800 dark:text-gray-200 text-sm mb-3 line-clamp-2">
                                            {{
                                                truncateDescription(
                                                    photo.description
                                                )
                                            }}
                                        </p>

                                        <!-- Photo Metadata -->
                                        <div v-if="photo.metadata"
                                            class="text-xs text-gray-500 dark:text-gray-400 mb-2 space-y-1">
                                            <div v-if="photo.metadata.location" class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 md:h-5 md:w-5 text-gray-400 mt-1 md:mt-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{
                                                    photo.metadata.location
                                                }}</span>
                                            </div>
                                            <div v-if="photo.metadata.model" class="flex items-center">
                                                <span class="mr-1">📷</span>
                                                <span>{{
                                                    photo.metadata.model
                                                }}</span>
                                            </div>
                                            <div v-if="
                                                    photo.metadata.aperture ||
                                                    photo.metadata.iso
                                                " class="flex items-center space-x-2">
                                                <span v-if="
                                                        photo.metadata.aperture
                                                    ">f/{{
                                                        photo.metadata.aperture
                                                    }}</span>
                                                <span v-if="photo.metadata.iso">ISO
                                                    {{
                                                        photo.metadata.iso
                                                    }}</span>
                                            </div>
                                        </div>

                                        <div
                                            class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                            <span
                                                class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ photo.category }}</span>
                                            <span class="flex items-center gap-1"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ photo.total_views }}
                                                views</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                            <span>By: {{ photo.user_name }}</span>
                                            <span>{{
                                                photo.created_at_human ||
                                                photo.created_at
                                            }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Photo Pagination -->
                        <div v-if="totalPhotoPages > 1" class="flex justify-center mt-8 gap-2">
                            <button
                                @click="goToPhotoPage(currentPhotoPage - 1)"
                                :disabled="currentPhotoPage === 1"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                            >
                                Previous
                            </button>
                            <div class="flex items-center px-4 text-black dark:text-white">
                                Page {{ currentPhotoPage }} of {{ totalPhotoPages }}
                            </div>
                            <button
                                @click="goToPhotoPage(currentPhotoPage + 1)"
                                :disabled="currentPhotoPage >= totalPhotoPages"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                            >
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Video Section -->
                    <div v-if="videoResults.length > 0" class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">🎥</span>
                                <h2 class="text-2xl font-bold text-black dark:text-white">
                                    Videos
                                </h2>
                                <span
                                    class="ml-3 px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full text-sm font-medium">
                                    {{ videoResults.length }} results
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="video in paginatedVideoResults" :key="`video-${video.id}`"
                                class="bg-zinc-200 dark:bg-zinc-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <a @click.prevent="navigateToDetail(video)" class="block cursor-pointer">
                                    <div class="relative">
                                        <img :src="video.thumbnail_url" :alt="video.title"
                                            class="w-full h-48 object-cover" @error="handleImageError" />
                                        <div
                                            class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                            🎥 VIDEO
                                        </div>
                                        <div v-if="video.duration_formatted"
                                            class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                                            {{ video.duration_formatted }}
                                        </div>
                                        <div v-if="video.score"
                                            class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs">
                                            {{
                                                Math.round(
                                                    (1 - video.score) * 100
                                                )
                                            }}% match
                                        </div>
                                        <!-- Play button overlay -->
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all">
                                                <svg class="w-8 h-8 text-white ml-1" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path d="M8 5v10l7-5-7-5z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-black dark:text-white font-semibold text-lg mb-2 line-clamp-2">
                                            {{ video.title }}
                                        </h3>
                                        <p class="text-gray-800 dark:text-gray-200 text-sm mb-3 line-clamp-2">
                                            {{
                                                truncateDescription(
                                                    video.description
                                                )
                                            }}
                                        </p>

                                        <!-- Video Metadata -->
                                        <div v-if="video.metadata"
                                            class="text-xs text-gray-500 dark:text-gray-400 mb-2 space-y-1">
                                            <div v-if="video.metadata.location" class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 md:h-5 md:w-5 text-gray-400 mt-1 md:mt-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{
                                                    video.metadata.location
                                                }}</span>
                                            </div>
                                            <div v-if="video.metadata.resolution" class="flex items-center">
                                                <span class="mr-1">🎬</span>
                                                <span>{{
                                                    video.metadata.resolution
                                                }}</span>
                                            </div>
                                            <div v-if="video.metadata.frame_rate" class="flex items-center">
                                                <span class="mr-1">🎯</span>
                                                <span>{{
                                                        video.metadata
                                                            .frame_rate
                                                    }}fps</span>
                                            </div>
                                            <div v-if="video.metadata.codec" class="flex items-center">
                                                <span class="mr-1">🔧</span>
                                                <span>{{
                                                    video.metadata.codec
                                                }}</span>
                                            </div>
                                        </div>

                                        <div
                                            class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                            <span
                                                class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ video.category }}</span>
                                            <span class="flex items-center gap-1"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ video.total_views }}
                                                views</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                            <span>By: {{ video.user_name }}</span>
                                            <span>{{
                                                video.created_at_human ||
                                                video.created_at
                                            }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Video Pagination -->
                        <div v-if="totalVideoPages > 1" class="flex justify-center mt-8 gap-2">
                            <button
                                @click="goToVideoPage(currentVideoPage - 1)"
                                :disabled="currentVideoPage === 1"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                            >
                                Previous
                            </button>
                            <div class="flex items-center px-4 text-black dark:text-white">
                                Page {{ currentVideoPage }} of {{ totalVideoPages }}
                            </div>
                            <button
                                @click="goToVideoPage(currentVideoPage + 1)"
                                :disabled="currentVideoPage >= totalVideoPages"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
                            >
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div v-if="totalCount === 0" class="text-center py-20">
                        <div class="text-6xl mb-4">🔍</div>
                        <p class="text-2xl text-black dark:text-white mb-2">
                            No results found
                        </p>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            {{
                                searchQuery
                                    ? `No results for "${searchQuery}"`
                                    : "No content available"
                            }}
                            {{
                                selectedCategory !== "All categories"
                                    ? ` in category "${selectedCategory}"`
                                    : ""
                            }}
                        </p>
                        <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <p>💡 Try using different keywords</p>
                            <p>🧠 Enable Smart Search for better matching</p>
                            <p>🏷️ Try a different category</p>
                        </div>
                    </div>

                    <!-- Empty Category Message -->
                    <div v-if="
                            activeTab === 'photos' &&
                            photoResults.length === 0 &&
                            videoResults.length > 0
                        " class="text-center py-10">
                        <div class="text-4xl mb-2">📸</div>
                        <p class="text-lg text-black dark:text-white">
                            No photos found
                        </p>
                        <p class="text-gray-500 dark:text-gray-400">
                            But found {{ videoResults.length }} videos
                        </p>
                    </div>

                    <div v-if="
                            activeTab === 'videos' &&
                            videoResults.length === 0 &&
                            photoResults.length > 0
                        " class="text-center py-10">
                        <div class="text-4xl mb-2">🎥</div>
                        <p class="text-lg text-black dark:text-white">
                            No videos found
                        </p>
                        <p class="text-gray-500 dark:text-gray-400">
                            But found {{ photoResults.length }} photos
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div v-if="showSidebar"
            class="fixed inset-0 bg-white dark:bg-black bg-opacity-80 z-50 flex flex-col justify-center items-center text-white transition-opacity duration-300">
            <div
                class="relative w-full max-w-md bg-white dark:bg-black p-6 rounded-lg shadow-lg border border-gray-700">
                <div class="flex justify-between items-center border-b border-gray-700 pb-4">
                    <span class="text-lg font-semibold">Select Category</span>
                    <button @click="toggleSidebar"
                        class="text-gray-800 dark:text-gray-200 hover:text-white text-2xl transition-colors">
                        &times;
                    </button>
                </div>
                <ul class="mt-5 space-y-3 text-lg">
                    <li v-for="category in categories" :key="category">
                        <button @click="selectCategory(category)" type="button"
                            class="block w-full px-6 py-4 text-left text-black dark:text-white bg-zinc-200 dark:bg-zinc-900 hover:bg-gray-300 rounded-lg transition-colors">
                            {{ category }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {
        ref,
        onMounted,
        nextTick,
        computed
    } from "vue";
    import axios from "axios";
    import Fuse from "fuse.js";

    // Add image error handler
    const handleImageError = (e) => {
        e.target.src = "/js/Assets/default-photo.jpg";
    };

    // UI State
    const showDropdown = ref(false);
    const showSidebar = ref(false);
    const selectedCategory = ref("All categories");
    const searchQuery = ref("");
    const searchPerformed = ref(false);
    const loading = ref(false);
    const useFuzzySearch = ref(true);
    const lastSearchTime = ref(null);
    const activeTab = ref("all"); // 'all', 'photos', 'videos'

    // Data
    const categories = ref(["All categories"]);
    const photoResults = ref([]);
    const videoResults = ref([]);
    const searchData = ref([]);
    const fuseInstance = ref(null);

    // Computed properties
    const totalCount = computed(
        () => photoResults.value.length + videoResults.value.length
    );
    const photoCount = computed(() => photoResults.value.length);
    const videoCount = computed(() => videoResults.value.length);

    // Add pagination state
    const currentPhotoPage = ref(1);
    const currentVideoPage = ref(1);
    const itemsPerPage = 3;

    // Add computed properties for paginated results
    const paginatedPhotoResults = computed(() => {
        const start = (currentPhotoPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return photoResults.value.slice(start, end);
    });

    const paginatedVideoResults = computed(() => {
        const start = (currentVideoPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return videoResults.value.slice(start, end);
    });

    const totalPhotoPages = computed(() => {
        return Math.ceil(photoResults.value.length / itemsPerPage);
    });

    const totalVideoPages = computed(() => {
        return Math.ceil(videoResults.value.length / itemsPerPage);
    });

    // Debounce timer
    let searchTimeout = null;

    // Fuse.js configuration
    const fuseOptions = {
        keys: [{
                name: "title",
                weight: 0.3
            },
            {
                name: "description",
                weight: 0.2
            },
            {
                name: "user_name",
                weight: 0.15
            },
            {
                name: "tag",
                weight: 0.15
            },
            {
                name: "all_tags",
                weight: 0.1
            },
            {
                name: "alt_text",
                weight: 0.1
            },
            {
                name: "note",
                weight: 0.1
            },
            {
                name: "location",
                weight: 0.15
            },
            {
                name: "model",
                weight: 0.1
            },
            {
                name: "combined_metadata",
                weight: 0.1
            },
            {
                name: "category",
                weight: 0.05
            },
            {
                name: "source",
                weight: 0.03
            },
            {
                name: "codec_video_audio",
                weight: 0.05
            },
        ],
        threshold: 0.4,
        distance: 100,
        includeScore: true,
        includeMatches: true,
        minMatchCharLength: 2,
        ignoreLocation: true,
    };

    // Initialize Fuse.js
    const initializeFuse = (data) => {
        if (data.length > 0) {
            fuseInstance.value = new Fuse(data, fuseOptions);
        }
    };

    // Fetch categories and search data on mount
    onMounted(async () => {
        try {
            const response = await axios.get("/api/search");
            categories.value = ["All categories", ...response.data.categories];

            if (response.data.search_data) {
                searchData.value = response.data.search_data;
                await nextTick();
                initializeFuse(response.data.search_data);
            }
        } catch (error) {
            console.error("Failed to load initial data:", error);
        }
    });

    const navigateToDetail = (item) => {
        if (item.type === "photo") {
            window.location.href = `/gallery-photo/${item.slug}`;
        } else if (item.type === "video") {
            window.location.href = `/gallery-video/${item.slug}`;
        }
    };

    // Truncate description for display
    const truncateDescription = (desc) => {
        if (!desc) return "";
        return desc.length > 100 ? desc.substring(0, 100) + "..." : desc;
    };

    // Handle search input with debouncing
    const handleSearchInput = () => {
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        searchTimeout = setTimeout(() => {
            if (searchQuery.value.trim()) {
                performSearch();
            }
        }, 300);
    };

    // Handle search mode change
    const handleSearchModeChange = () => {
        if (
            searchQuery.value.trim() ||
            selectedCategory.value !== "All categories"
        ) {
            performSearch();
        }
    };

    // Perform fuzzy search using Fuse.js
    const performFuzzySearch = (query) => {
        if (!fuseInstance.value) {
            console.warn("Fuse.js not initialized");
            return {
                photos: [],
                videos: []
            };
        }

        const startTime = performance.now();
        const results = fuseInstance.value.search(query);
        const endTime = performance.now();

        lastSearchTime.value = Math.round(endTime - startTime);

        // Separate photos and videos
        const photos = [];
        const videos = [];

        results.forEach((result) => {
            const item = {
                ...result.item,
                score: result.score
            };
            if (item.type === "photo") {
                photos.push(item);
            } else if (item.type === "video") {
                videos.push(item);
            }
        });

        return {
            photos,
            videos
        };
    };

    // Perform the actual search
    const performSearch = async () => {
        currentPhotoPage.value = 1;
        currentVideoPage.value = 1;
        const startTime = performance.now();

        // Handle empty search
        if (
            !searchQuery.value.trim() &&
            selectedCategory.value === "All categories"
        ) {
            photoResults.value = [];
            videoResults.value = [];
            searchPerformed.value = false;
            return;
        }

        searchPerformed.value = true;
        loading.value = true;

        try {
            // If using fuzzy search and we have data
            if (
                useFuzzySearch.value &&
                fuseInstance.value &&
                searchQuery.value.trim()
            ) {
                // Filter by category first if needed
                let dataToSearch = searchData.value;

                if (selectedCategory.value !== "All categories") {
                    dataToSearch = searchData.value.filter(
                        (item) => item.category === selectedCategory.value
                    );

                    // Reinitialize Fuse with filtered data
                    const tempFuse = new Fuse(dataToSearch, fuseOptions);
                    const fuzzyResults = tempFuse.search(searchQuery.value.trim());

                    const photos = [];
                    const videos = [];

                    fuzzyResults.forEach((result) => {
                        const item = {
                            ...result.item,
                            score: result.score
                        };
                        if (item.type === "photo") {
                            photos.push(item);
                        } else if (item.type === "video") {
                            videos.push(item);
                        }
                    });

                    photoResults.value = photos;
                    videoResults.value = videos;
                } else {
                    const fuzzyResults = performFuzzySearch(
                        searchQuery.value.trim()
                    );
                    photoResults.value = fuzzyResults.photos;
                    videoResults.value = fuzzyResults.videos;
                }

                const endTime = performance.now();
                lastSearchTime.value = Math.round(endTime - startTime);
            } else {
                // Fallback to server-side search
                const params = {};

                if (selectedCategory.value !== "All categories") {
                    params.category = selectedCategory.value;
                }

                if (searchQuery.value.trim()) {
                    params.query = searchQuery.value.trim();
                }

                params.use_fuzzy = false;

                const response = await axios.get("/api/search", {
                    params
                });

                photoResults.value = response.data.photos || [];
                videoResults.value = response.data.videos || [];

                // Update search data if returned
                if (response.data.search_data) {
                    searchData.value = response.data.search_data;
                    initializeFuse(response.data.search_data);
                }

                const endTime = performance.now();
                lastSearchTime.value = Math.round(endTime - startTime);
            }
        } catch (error) {
            console.error("Search failed:", error);
            photoResults.value = [];
            videoResults.value = [];
            lastSearchTime.value = null;
        } finally {
            loading.value = false;
        }
    };

    // Handle search submission
    const handleSearch = () => {
        performSearch();
    };

    const toggleDropdown = () => {
        showDropdown.value = !showDropdown.value;
    };

    const toggleSidebar = () => {
        showSidebar.value = !showSidebar.value;
    };

    const selectCategory = async (category) => {
        selectedCategory.value = category;
        showDropdown.value = false;
        showSidebar.value = false;

        // Refresh search data for the new category
        try {
            const response = await axios.get("/api/search/data", {
                params: {
                    category: category !== "All categories" ? category : null,
                },
            });

            if (response.data.search_data) {
                searchData.value = response.data.search_data;
                initializeFuse(response.data.search_data);
            }
        } catch (error) {
            console.error("Failed to refresh search data:", error);
        }

        performSearch();
    };

    // Add pagination methods
    const goToPhotoPage = (page) => {
        if (page >= 1 && page <= totalPhotoPages.value) {
            currentPhotoPage.value = page;
        }
    };

    const goToVideoPage = (page) => {
        if (page >= 1 && page <= totalVideoPages.value) {
            currentVideoPage.value = page;
        }
    };

</script>

<style scoped>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

</style>
