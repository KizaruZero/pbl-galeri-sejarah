<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Article
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center text-white">
                Loading articles...
            </div>

            <!-- Error State -->
            <div v-if="error" class="text-center text-red-500">
                {{ error }}
            </div>

            <!-- Grid Card -->
            <div
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
            >
                <ArticleCard
                    v-for="article in articles"
                    :key="article.id"
                    :id="article.id"
                    :title="article.title"
                    :slug="article.slug"
                    :content="article.content"
                    :user_id="article.user_id"
                    :image_url="
                        article.image_url
                            ? article.image_url.startsWith('http')
                                ? article.image_url
                                : `/storage/${article.image_url.replace(
                                      /^public\//,
                                      ''
                                  )}`
                            : '/default-article.jpg'
                    "
                    :status="article.status"
                    :total_views="article.total_views"
                    @click="getDetailPage(article.slug)"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ArticleCard from "./ArticleCard.vue";
import axios from "axios";

const articles = ref([]);
const loading = ref(true);
const error = ref(null);
const isLiked = ref(false);
const isSaved = ref(false);
const likeCount = ref(Math.floor(Math.random() * 100) + 5);

const getDetailPage = (slug) => {
    window.location.href = `/article/${slug}`;
};

onMounted(async () => {
    try {
        const options = {
            method: "GET",
            url: "/api/articles/",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer 123",
            },
        };

        const response = await axios.request(options);

        // Handle both array and paginated responses
        articles.value = Array.isArray(response.data)
            ? response.data
            : response.data.data || [];

        // Ensure all articles have required fields
        articles.value = articles.value.map((article) => ({
            ...article,
            title: article.title || "Untitled",
            content: article.content || "No content available",
            image_url: article.image_url || "",
            thumbnail_url: article.thumbnail_url || "",
            total_views: article.total_views || 0,
        }));
    } catch (err) {
        error.value = "Failed to load articles";
        console.error("Error fetching articles:", err);
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
};

// Toggle save status
const toggleSave = () => {
    isSaved.value = !isSaved.value;
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
