<template>
    <MainLayout>
        <SeoHead
            :title="article.title"
            :description="metaDescription"
            :image="article.thumbnailUrl || article.imageUrl"
        />

        <div class="min-h-screen bg-white dark:bg-black">
            <!-- Loading state -->
            <div v-if="loading" class="animate-pulse">
                <!-- Image placeholder -->
                <div class="w-full h-[70vh] bg-zinc-900"></div>

                <!-- Content placeholder -->
                <div class="p-6 mx-4 md:mx-24">
                    <div class="flex justify-between items-start mb-6">
                        <div class="h-8 w-3/4 bg-zinc-800 rounded"></div>
                        <div class="flex space-x-4">
                            <div class="h-8 w-8 bg-zinc-800 rounded-full"></div>
                            <div class="h-8 w-8 bg-zinc-800 rounded-full"></div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-4 bg-zinc-800 rounded w-full"></div>
                        <div class="h-4 bg-zinc-800 rounded w-5/6"></div>
                        <div class="h-4 bg-zinc-800 rounded w-4/6"></div>
                    </div>
                </div>
            </div>

            <!-- Error state -->
            <div
                v-else-if="error"
                class="flex flex-col items-center justify-center min-h-screen text-center px-4"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-16 w-16 text-red-500 mb-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
                <h2 class="text-xl font-bold text-white mb-2">{{ error }}</h2>
                <button
                    @click="fetchArticle"
                    class="px-6 py-2 bg-white text-black rounded-md hover:bg-gray-200 transition"
                >
                    Try Again
                </button>
            </div>

            <!-- Main content -->
            <div v-else>
                <div class="max-w-full mx-auto">
                    <div class="relative">
                        <!-- Back Button -->
                        <button
                            @click="goBack"
                            class="absolute top-4 left-4 z-10 p-2 bg-black/50 rounded-full hover:bg-black/75 transition-colors"
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
                        <!-- Empty state for image -->
                        <div
                            v-if="!article.imageUrl || imageError"
                            class="w-full h-[30vh] md:h-[70vh] bg-zinc-900 flex items-center justify-center"
                        >
                            <div class="text-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-16 w-16 md:h-24 md:w-24 mx-auto text-gray-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                <p
                                    class="mt-4 text-gray-400 text-sm md:text-base"
                                >
                                    No image available
                                </p>
                            </div>
                        </div>

                        <!-- Image container - modified for full width -->
                        <div class="w-full overflow-hidden">
                            <img
                                v-if="!imageError"
                                :src="article.imageUrl"
                                :alt="article.altText"
                                class="w-full h-auto max-h-[70vh] object-contain bg-gray-200 dark:bg-gray-950"
                                @error="handleImageError"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-white/80 dark:from-black/80 via-transparent to-transparent opacity-70 mix-blend-multiply"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Main article Card -->
                <div
                    class="bg-white dark:bg-black rounded-lg shadow-xl overflow-hidden"
                >
                    <!-- article Details -->
                    <div class="p-6 md:px-24">
                        <div
                            class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400"
                        >
                            <div class="flex items-center gap-1">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                                <span>{{ article.totalViews || 0 }} views</span>
                            </div>
                        </div>
                        <!-- Title -->
                        <h1
                            class="text-xl md:text-3xl font-bold text-black dark:text-white mb-4"
                        >
                            {{ article.title }}
                        </h1>

                        <!-- Content -->
                        <div
                            class="prose prose-invert max-w-none mb-6"
                            v-html="formattedContent"
                        ></div>

                        <!-- Article Stats -->
                    </div>
                </div>

                <!-- Recent Articles Section -->
                <div class="bg-white dark:bg-black py-16">
                    <div class="max-w-6xl mx-auto px-6">
                        <div
                            class="flex flex-col items-center text-black dark:text-white mb-12"
                        >
                            <span
                                class="w-full h-0.5 bg-black dark:bg-white mb-6"
                            ></span>
                            <h1
                                class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                            >
                                Artikel Terbaru
                            </h1>
                            <span
                                class="w-full h-0.5 bg-black dark:bg-white mt-6"
                            ></span>
                        </div>

                        <!-- Loading State -->
                        <div
                            v-if="loadingRecent"
                            class="grid grid-cols-1 md:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="i in 3"
                                :key="i"
                                class="bg-black rounded-lg overflow-hidden animate-pulse"
                            >
                                <div class="h-48 bg-zinc-800"></div>
                                <div class="p-4">
                                    <div
                                        class="h-4 bg-zinc-800 rounded w-3/4 mb-2"
                                    ></div>
                                    <div
                                        class="h-3 bg-zinc-800 rounded w-1/2"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div
                            v-else
                            class="grid grid-cols-1 md:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="article in recentArticles.slice(0, 6)"
                                :key="article.id"
                                class="bg-zinc-200 dark:bg-zinc-900 rounded-lg overflow-hidden hover:scale-105 transition-transform cursor-pointer"
                                @click="goToArticle(article.slug)"
                            >
                                <img
                                    :src="article.imageUrl"
                                    :alt="article.title"
                                    class="w-full h-48 object-cover"
                                    @error="
                                        $event.target.src =
                                            '/js/Assets/default-photo.jpg'
                                    "
                                />
                                <div class="p-4">
                                    <h3
                                        class="text-black dark:text-white font-semibold mb-2 line-clamp-2"
                                    >
                                        {{ article.title }}
                                    </h3>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 text-sm"
                                    >
                                        {{ formatDate(article.publishedAt) }}
                                    </p>
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
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link } from "@inertiajs/vue3";
import SeoHead from "@/Components/SeoHead.vue";

const slug = window.location.pathname.split("/").pop();

const article = ref({});
const loading = ref(true);
const error = ref(null);
const imageError = ref(false);
const recentArticles = ref([]);
const loadingRecent = ref(true);

const formatDate = (dateString) => {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

const formatContent = (content) => {
    if (!content) return "";

    // Convert line breaks to paragraphs
    let html = content.replace(/\n\n/g, "</p><p>");

    // Convert image markdown to HTML with better styling
    html = html.replace(
        /!\[.*?\]\((.*?)\)/g,
        '<div class="image-container my-6"><img src="$1" alt="" class="article-image w-full max-w-xl mx-auto rounded-lg shadow-lg object-cover" loading="lazy" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';" /><div class="image-error hidden w-full h-64 bg-zinc-800 rounded-lg flex items-center justify-center"><span class="text-gray-400">Image not available</span></div></div>'
    );

    // Convert headings (assuming lines that end with :) to h2
    html = html.replace(
        /(.+:)\n/g,
        '<h2 class="text-2xl font-bold mt-6 mb-4">$1</h2>'
    );

    // Convert bullet points to list items
    html = html.replace(/\n- (.+)/g, '<li class="ml-4 list-disc">$1</li>');
    html = html.replace(/<li>/g, '<ul class="my-4 pl-6"><li>');
    html = html.replace(/<\/li>\n/g, "</li></ul>");

    // Wrap in paragraph tags
    html = `<p>${html}</p>`;

    return html;
};

const goBack = () => {
    window.history.back();
};

const handleImageError = (e) => {
    imageError.value = true;
    e.target.style.display = "none";
};

// Fetch article data
const fetchArticle = async () => {
    loading.value = true;
    error.value = null;
    imageError.value = false;

    try {
        const response = await axios.get(`/api/articles/${slug}`, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer 123", // hapus jika tidak pakai token
            },
        });

        const articleData = response.data;

        article.value = {
            title: articleData.title || "Untitled article",
            content: articleData.content || "No content available",
            imageUrl: articleData.image_url
                ? articleData.image_url.startsWith("http")
                    ? articleData.image_url
                    : `/storage/${articleData.image_url.replace(
                          /^public\//,
                          ""
                      )}`
                : "/js/Assets/default-photo.jpg",
            userId: articleData.user_id || null,
            thumbnailUrl: articleData.thumbnail_url || null,
            status: articleData.status || "draft",
            publishedAt: articleData.published_at || null,
            totalViews: articleData.total_views || 0,
            createdAt: articleData.created_at || null,
            updatedAt: articleData.updated_at || null,
        };
        console.log("Fetched article:", article.value.content);
    } catch (err) {
        console.error("Error fetching article:", err);
        error.value = "Failed to load article. Please try again later.";
    } finally {
        loading.value = false;
    }
};

// Add new function to fetch recent articles
const fetchRecentArticles = async () => {
    loadingRecent.value = true;
    try {
        const response = await axios.get("/api/articles/", {
            params: { limit: 3, exclude: slug },
            headers: {
                Accept: "application/json",
                Authorization: "Bearer 123",
            },
        });

        recentArticles.value = response.data.map((article) => ({
            id: article.id,
            slug: article.slug,
            title: article.title,
            imageUrl: article.image_url
                ? article.image_url.startsWith("http")
                    ? article.image_url
                    : `/storage/${article.image_url.replace(/^public\//, "")}`
                : "/js/Assets/default-photo.jpg",
            publishedAt: article.published_at,
        }));
    } catch (err) {
        console.error("Error fetching recent articles:", err);
    } finally {
        loadingRecent.value = false;
    }
};

const goToArticle = (articleSlug) => {
    window.location.href = `/article/${articleSlug}`;
};

onMounted(() => {
    fetchArticle();
    fetchRecentArticles();
});

const metaDescription = computed(() => {
    if (!article.value.content) return "";
    return article.value.content.replace(/<[^>]+>/g, "").slice(0, 160);
});

const formattedContent = computed(() => formatContent(article.value.content));
</script>

<style>
/* Add custom prose styles */
.prose h2 {
    @apply text-2xl font-bold mt-6 mb-4;
}

.prose p {
    @apply my-4 text-black dark:text-white leading-relaxed;
}

.prose img {
    @apply my-4 rounded-lg w-full;
}

.prose ul {
    @apply my-4 pl-6;
}

.prose li {
    @apply mb-2;
}

/* New styles for article images */
.image-container {
    @apply my-6;
}

.article-image {
    @apply w-full max-w-md mx-auto rounded-lg shadow-lg object-cover;
}

.image-error {
    @apply hidden w-full h-64 bg-zinc-800 rounded-lg flex items-center justify-center;
}
</style>
