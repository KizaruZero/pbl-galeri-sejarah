<template>
    <MainLayout>
        <div class="min-h-screen bg-gray-50">
            <!-- Loading State -->
            <div v-if="loading" class="max-w-7xl mx-auto py-12 text-center">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"
                ></div>
                <p class="mt-4 text-gray-600">Loading article...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="max-w-7xl mx-auto py-12 text-center">
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-red-500"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main v-else class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 sm:px-0">
                    <!-- Article Container -->
                    <article class="bg-white shadow overflow-hidden rounded-lg">
                        <!-- Article Header -->
                        <div class="relative">
                            <img
                                :src="article.image_url || '/placeholder.jpg'"
                                :alt="article.title"
                                class="w-full h-96 object-cover"
                                @error="article.image_url = '/placeholder.jpg'"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"
                            ></div>
                            <div class="absolute bottom-0 left-0 p-6">
                                <h1 class="text-4xl font-bold text-white mb-2">
                                    {{ article.title }}
                                </h1>
                                <div class="flex items-center text-gray-200">
                                    <span class="mr-4"
                                        >Published:
                                        {{
                                            formatDate(article.published_at)
                                        }}</span
                                    >
                                    <span
                                        >Views: {{ article.total_views }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="px-6 py-8">
                            <div
                                class="prose max-w-none"
                                v-html="formatContent(article.content)"
                            ></div>
                        </div>

                        <!-- Article Footer -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <Link
                                href="/article"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                &larr; Back to Articles
                            </Link>
                        </div>
                    </article>
                </div>
            </main>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link } from "@inertiajs/vue3";

const slug = computed(() => usePage().props.slug);

const article = ref({});
const loading = ref(true);
const error = ref(null);

const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

const formatContent = (content) => {
    if (!content) return "";

    // Convert line breaks to paragraphs
    let html = content.replace(/\n\n/g, "</p><p>");

    // Convert image markdown to HTML
    html = html.replace(
        /!\[.*?\]\((.*?)\)/g,
        '<img src="$1" alt="" class="my-4 rounded-lg w-full">'
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

const fetchArticle = async () => {
    try {
        loading.value = true;
        error.value = null;

        const response = await axios.get(
            `http://127.0.0.1:8000/api/articles/${slug.value}`
        );

        if (response.data && response.data.data) {
            // Transform image URLs to full paths
            article.value = {
                ...response.data.data,
                image_url: formatImageUrl(response.data.data.image_url),
                thumbnail_url: formatImageUrl(response.data.data.thumbnail_url),
            };
        } else {
            throw new Error("Article data format is invalid");
        }
    } catch (err) {
        error.value =
            err.response?.data?.message ||
            err.message ||
            "Failed to fetch article";
    } finally {
        loading.value = false;
    }
};

const formatImageUrl = (url) => {
    if (!url) return ""; // or a placeholder image URL

    // If it's already a full URL, return as-is
    if (url.startsWith("http")) return url;

    // If it starts with /storage, assume it's correct
    if (url.startsWith("/storage")) return url;

    // Otherwise, prepend your storage URL
    return `http://127.0.0.1:8000/storage${
        url.startsWith("/") ? "" : "/"
    }${url}`;
};

onMounted(() => {
    fetchArticle();
    console.log(article.image_url);
});
</script>

<style>
/* Add custom prose styles */
.prose h2 {
    @apply text-2xl font-bold mt-6 mb-4;
}
.prose p {
    @apply my-4 text-gray-700 leading-relaxed;
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
</style>
