<template>
    <div
        class="grid grid-cols-2 sm:flex sm:flex-wrap justify-center gap-4 sm:gap-6 bg-black p-4 sm:p-6"
    >
        <article
            v-for="(item, index) in articles"
            :key="index"
            @click="goToDetail(item)"
            class="overflow-hidden px-4 pt-4 pb-6 text-white cursor-pointer transition hover:scale-105 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] max-w-[470px] text-center"
        >
            <img
                :src="item.image_url"
                class="object-cover w-full aspect-[1.38] rounded-t-lg"
                :alt="item.title"
            />
            <h2
                class="mt-3 text-base sm:text-lg lg:text-2xl font-semibold leading-tight"
            >
                {{ item.title }}
            </h2>
            <p
                class="mt-2 text-xs sm:text-sm text-gray-300 leading-relaxed line-clamp-2"
            >
                {{ truncateContent(item.content) }}
            </p>
        </article>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

const articles = ref([]);

const goToDetail = (item) => {
    router.visit(route("article.show", { slug: item.slug }));
};

const truncateContent = (content) => {
    if (!content) return "";
    // Remove markdown images and truncate text
    return (
        content
            .replace(/!\[.*?\]\(.*?\)/g, "")
            .replace(/\n/g, " ")
            .substring(0, 100) + "..."
    );
};

const formatImageUrl = (url) => {
    if (!url) return "/placeholder-image.jpg";
    return url.startsWith("http")
        ? url
        : `/storage/${url.replace(/^public\//, "")}`;
};

onMounted(async () => {
    try {
        const response = await axios.get("http://127.0.0.1:8000/api/articles");

        // Handle different response structures
        const responseData = response.data.data || response.data;

        if (Array.isArray(responseData)) {
            articles.value = responseData.map((item) => ({
                ...item,
                image_url: formatImageUrl(item.image_url || item.thumbnail_url),
            }));
        } else {
            console.error("API response is not an array:", responseData);
        }
    } catch (error) {
        console.error("Failed to fetch articles:", error);
        // You might want to set an error state here for UI feedback
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
