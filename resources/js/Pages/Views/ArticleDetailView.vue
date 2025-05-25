<template>
    <MainLayout>
        <div class="min-h-screen bg-black mt-8">
                <div class="max-w-full mx-auto">
                        <div class="relative">
                        <!-- Back Button -->
                        <button @click="goBack"
                            class="absolute top-4 left-4 z-10 p-2 bg-black/50 rounded-full hover:bg-black/75 transition-colors mt-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>

                        <!-- Image container - modified for full width -->
                        <div class="w-full overflow-hidden">
                            <img :src="article.imageUrl" :alt="article.altText"
                                class="w-full h-auto max-h-[70vh] object-contain bg-gray-950" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-70 mix-blend-multiply"></div>
                        </div>
                    </div>
                </div>

                <!-- Main article Card -->
                <div class="bg-black rounded-lg shadow-xl overflow-hidden">

                    <!-- article Details -->
                    <div class="p-6 ml-24 mr-24">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex space-x-4">
                                <!-- Like Button -->
                                <button @click="toggleLike" class="flex items-center space-x-1 group">
                                    <div class="p-1 rounded-full group-hover:bg-red-500/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :class="
                                                isLiked
                                                    ? 'text-red-500 fill-current'
                                                    : 'text-gray-400 group-hover:text-red-500'
                                            " viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                    <span :class="
                                            isLiked
                                                ? 'text-red-500'
                                                : 'text-gray-400 group-hover:text-red-500'
                                        ">
                                        {{ likeCount }}
                                    </span>
                                </button>

                                <!-- Bookmark Button -->
                                <button @click="toggleBookmark" class="flex items-center group">
                                    <div class="p-1 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :class="
                                                isBookmarked
                                                    ? 'text-blue-500 fill-current'
                                                    : 'text-gray-400 group-hover:text-blue-500'
                                            " viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold text-white">
                                {{ article.title }}
                            </h1>
                        </div>

                        <!-- content -->
                        <div class="prose prose-invert max-w-none mb-6" v-html="formattedContent"></div>

                        <!-- article Stats -->
                        <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ article.total_views || 0 }} views</span>
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
        onMounted,
        computed
    } from "vue";
    import {
        usePage
    } from "@inertiajs/vue3";
    import axios from "axios";
    import MainLayout from "@/Layouts/MainLayout.vue";
    import {
        Link
    } from "@inertiajs/vue3";

    const slug = window.location.pathname.split("/").pop();

    const article = ref({});
    const loading = ref(true);
    const error = ref(null);

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
        
    const goBack = () => {
        window.history.back();
    };

    // Fetch article data
    onMounted(async () => {
        try {
            console.log("Slug:", slug);
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
                imageUrl: articleData.image_url ?
                    articleData.image_url.startsWith("http") ?
                    articleData.image_url :
                    `/storage/${articleData.image_url.replace(
                          /^public\//,
                          ""
                      )}` :
                    "/default-article.jpg",
                userId: articleData.user_id || null,
                thumbnailUrl: articleData.thumbnail_url || null,
                status: articleData.status || "draft",
                publishedAt: articleData.published_at || null,
                totalViews: articleData.total_views || 0,
                createdAt: articleData.created_at || null,
                updatedAt: articleData.updated_at || null,
            };
        } catch (error) {
            console.error("Error fetching article:", error);
        } finally {
            loading.value = false;
        }
    });

    const formattedContent = computed(() => formatContent(article.value.content));

</script>

<style>
    /* Add custom prose styles */
    .prose h2 {
        @apply text-2xl font-bold mt-6 mb-4;
    }

    .prose p {
        @apply my-4 text-white leading-relaxed;
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
