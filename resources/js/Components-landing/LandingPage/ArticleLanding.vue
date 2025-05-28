<template>
  <section class="w-full bg-black py-16 px-6 md:px-10">
    <div class="max-w-[1192px] mx-auto">
      <!-- Title Section -->
      <div class="flex flex-col items-center text-white mb-12">
        <span class="w-full h-0.5 bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">Article</h1>
        <span class="w-full h-0.5 bg-white mt-6"></span>
      </div>

      <!-- Loading State -->
      <div
        v-if="loading"
        class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 animate-pulse"
      >
        <!-- Skeleton loading cards -->
        <div
          v-for="i in 3"
          :key="i"
          class="bg-zinc-900 rounded-lg overflow-hidden shadow-lg h-[400px]"
        >
          <!-- Image placeholder -->
          <div class="w-full h-48 bg-gray-800"></div>

          <!-- Content placeholder -->
          <div class="p-4">
            <div class="h-6 w-3/4 bg-gray-800 rounded mb-3"></div>
            <div class="h-4 w-full bg-gray-800 rounded mb-2"></div>
            <div class="h-4 w-5/6 bg-gray-800 rounded mb-2"></div>
            <div class="h-4 w-2/3 bg-gray-800 rounded"></div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-500 text-lg mb-4">{{ error }}</p>
        <button
          @click="fetchArticles"
          class="px-6 py-2 bg-white text-black rounded-md hover:bg-gray-200 transition"
        >
          Retry
        </button>
      </div>

      <!-- Content Section -->
      <div v-else>
        <!-- Grid Cards -->
        <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
          <ArticleCard
            v-for="article in displayedArticles"
            :key="article.id"
            :id="article.id"
            :title="article.title"
            :slug="article.slug"
            :content="article.content"
            :user_id="article.user_id"
            :image_url="getImageUrl(article.image_url)"
            :status="article.status"
            :total_views="article.total_views"
            @click="getDetailPage(article.slug)"
          />
        </div>

        <!-- Empty State -->
        <div v-if="!loading && articles.length === 0" class="text-center py-16">
          <p class="text-white text-xl">No articles available</p>
        </div>

        <!-- View All Button -->
        <div v-if="articles.length > 3" class="flex justify-end mt-12">
          <Link
            href="/article"
            class="inline-block px-8 py-3 bg-white text-black font-medium hover:bg-gray-200 transition duration-300"
          >
            View All Articles
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import ArticleCard from "@/Components-landing/ArticlePage/ArticleCard.vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";

const articles = ref([]);
const loading = ref(true);
const error = ref(null);

// Only show first 3 articles
const displayedArticles = computed(() => {
  return articles.value.slice(0, 3);
});

// Helper function for image URL
const getImageUrl = (imageUrl) => {
  if (!imageUrl) return "/js/Assets/default-photo.jpg";
  return imageUrl.startsWith("http")
    ? imageUrl
    : `/storage/${imageUrl.replace(/^public\//, "")}`;
};

const getDetailPage = (slug) => {
  window.location.href = `/article/${slug}`;
};

const fetchArticles = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.get("/api/articles/", {
      headers: {
        Accept: "application/json",
        Authorization: "Bearer 123",
      },
    });

    // Handle different response formats
    articles.value = Array.isArray(response.data)
      ? response.data
      : response.data?.data || [];

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
    console.error("Error fetching articles:", err);
    error.value = "Failed to load articles. Please try again later.";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchArticles();
});
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
