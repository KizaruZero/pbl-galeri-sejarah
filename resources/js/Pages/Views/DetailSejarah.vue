<template>
    <MainLayout>
        <div
            class="min-h-screen flex flex-col items-center justify-center bg-black text-white p-6"
        >
            <!-- Back button -->
            <button
                @click="$router.back()"
                class="absolute top-4 left-4 flex items-center text-white text-lg hover:text-gray-400"
            >
                <span class="text-2xl">‹</span>
                <span class="ml-2">Kembali</span>
            </button>

            <!-- Loading state -->
            <div v-if="loading" class="text-center">
                <p>Loading...</p>
            </div>

            <!-- Error state -->
            <div v-if="error" class="text-center text-red-500">
                <p>Failed to load article: {{ error }}</p>
            </div>

            <!-- Success state -->
            <template v-if="article">
                <h1 class="text-4xl font-bold">{{ article.title }}</h1>
                <img
                    :src="article.image"
                    class="mt-5 w-full max-w-lg"
                    :alt="article.title"
                />
                <p class="mt-4 text-lg">{{ article.content }}</p>
            </template>
        </div>
    </MainLayout>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";

const article = ref(null);
const loading = ref(true);
const error = ref(null);
const route = useRoute();

onMounted(() => {
    const fetchArticle = async () => {
        try {
            loading.value = true;
            const response = await axios.get(
                `/api/articles/${route.params.slug}`
            );
            article.value = response.data.data;
        } catch (err) {
            console.error("Error fetching Article:", err);
            error.value = err.message || "Failed to load article";
        } finally {
            loading.value = false;
        }
    };

    fetchArticle();
});
</script>
