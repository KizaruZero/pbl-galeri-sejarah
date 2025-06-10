<template>
    <div class="mx-auto px-4 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <template v-if="!isAuthenticated">
                <div
                    class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-black p-12 mb-8 border border-gray-200 dark:border-gray-800"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent dark:via-gray-800/5 transform -skew-y-1"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-black dark:bg-white rounded-2xl mb-6 shadow-lg"
                        >
                            <svg
                                class="w-10 h-10 text-white dark:text-black"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                                />
                            </svg>
                        </div>
                        <h1
                            class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-black to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent"
                        >
                            JADILAH MEMBER
                        </h1>
                        <p
                            class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed"
                        >
                            Bergabunglah dengan komunitas kreatif dan
                            ekspresikan dirimu melalui konten visual yang
                            menakjubkan
                        </p>
                    </div>
                </div>
            </template>
            <template v-else>
                <div
                    class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-50 to-white dark:from-black dark:to-gray-900 p-12 mb-8 border border-gray-200 dark:border-gray-800"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-gray-100/20 to-transparent dark:via-gray-700/20 transform skew-y-1"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl mb-6 shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h1
                            class="text-4xl md:text-5xl font-bold mb-4 text-black dark:text-white"
                        >
                            Selamat datang, {{ username }}!
                        </h1>
                        <p
                            class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto"
                        >
                            Nikmati pengalaman penuh sebagai member premium
                            dengan akses ke semua fitur eksklusif
                        </p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Features Grid -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2
                    class="text-3xl md:text-4xl font-bold mb-4 text-black dark:text-white"
                >
                    Fitur Unggulan
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto"
                >
                    Ekspresikan kreativitasmu dengan berbagai fitur canggih yang
                    tersedia
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Upload Photo -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold mb-3 text-black dark:text-white"
                        >
                            Upload Foto
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4"
                        >
                            Bagikan momen berharga dengan kualitas foto terbaik
                        </p>
                        <div
                            class="flex items-center text-sm text-gray-500 dark:text-gray-500"
                        >
                            <div
                                class="w-2 h-2 bg-green-500 rounded-full mr-2"
                            ></div>
                            Resolusi tinggi, Sharing Moment
                        </div>
                    </div>
                </div>

                <!-- Upload Video -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold mb-3 text-black dark:text-white"
                        >
                            Upload Video
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4"
                        >
                            Ceritakan kisahmu melalui video berkualitas HD
                        </p>
                        <div
                            class="flex items-center text-sm text-gray-500 dark:text-gray-500"
                        >
                            <div
                                class="w-2 h-2 bg-blue-500 rounded-full mr-2"
                            ></div>
                            HD quality
                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold mb-3 text-black dark:text-white"
                        >
                            Komentar Interaktif
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4"
                        >
                            Berinteraksi dengan komunitas melalui komentar dan
                            diskusi yang meaningful
                        </p>
                        <div
                            class="flex items-center text-sm text-gray-500 dark:text-gray-500"
                        >
                            <div
                                class="w-2 h-2 bg-purple-500 rounded-full mr-2"
                            ></div>
                            Real-time interaction
                        </div>
                    </div>
                </div>

                <!-- Likes -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold mb-3 text-black dark:text-white"
                        >
                            Like & Reaksi
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4"
                        >
                            Ekspresikan apresiasi dengan berbagai reaksi dan
                            like pada konten favorit
                        </p>
                        <div
                            class="flex items-center text-sm text-gray-500 dark:text-gray-500"
                        >
                            <div
                                class="w-2 h-2 bg-red-500 rounded-full mr-2"
                            ></div>
                            Multiple reactions available
                        </div>
                    </div>
                </div>

                <!-- Favorites -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold mb-3 text-black dark:text-white"
                        >
                            Koleksi Favorit
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4"
                        >
                            Simpan konten terbaik ke koleksi pribadi untuk
                            dinikmati kapan saja
                        </p>
                        <div
                            class="flex items-center text-sm text-gray-500 dark:text-gray-500"
                        >
                            <div
                                class="w-2 h-2 bg-yellow-500 rounded-full mr-2"
                            ></div>
                            Organized collections
                        </div>
                    </div>
                </div>

                <!-- Social Sharing -->
                <div
                    class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent dark:from-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white dark:text-black"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-bold mb-3 text-black dark:text-white"
                        >
                            Share & Connect
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed mb-4"
                        >
                            Bagikan konten ke berbagai platform dan perluas
                            jangkauan audiensmu
                        </p>
                        <div
                            class="flex items-center text-sm text-gray-500 dark:text-gray-500"
                        >
                            <div
                                class="w-2 h-2 bg-indigo-500 rounded-full mr-2"
                            ></div>
                            Cross-platform sharing
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-black to-gray-800 dark:from-white dark:to-gray-100 p-12 shadow-2xl"
            >
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-gray-700/10 to-transparent dark:via-gray-300/10 transform skew-y-1"
                ></div>
                <div class="relative z-10">
                    <h3
                        class="text-3xl md:text-4xl font-bold mb-4 text-white dark:text-black"
                    >
                        Siap Memulai Perjalanan Kreatifmu?
                    </h3>
                    <p
                        class="text-gray-300 dark:text-gray-600 text-lg mb-8 max-w-2xl mx-auto"
                    >
                        Bergabunglah dengan ribuan kreator lainnya dan mulai
                        berbagi karya terbaikmu hari ini
                    </p>
                    <template v-if="!isAuthenticated">
                        <button
                            class="inline-flex items-center px-8 py-4 bg-white dark:bg-black text-black dark:text-white font-bold rounded-2xl hover:shadow-2xl transition-all duration-300 hover:scale-105 group"
                        >
                            <span>Daftar Sekarang</span>
                            <svg
                                class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />
                            </svg>
                        </button>
                    </template>
                    <template v-else>
                        <button
                            class="inline-flex items-center px-8 py-4 bg-white dark:bg-black text-black dark:text-white font-bold rounded-2xl hover:shadow-2xl transition-all duration-300 hover:scale-105 group"
                        >
                            <span>Mulai Berkreasi</span>
                            <svg
                                class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// Define props
const props = defineProps({
    isAuthenticated: {
        type: Boolean,
        required: true,
    },
});

// Get user data from Inertia page props
const username = computed(() => {
    const user = usePage().props.auth.user;
    return user ? user.name : "Member";
});
</script>
