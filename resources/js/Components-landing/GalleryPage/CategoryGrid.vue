<template>
    <section class="w-full bg-black py-16 px-6 md:px-10">
        <div class="max-w-[1192px] mx-auto">
            <!-- Judul -->
            <div class="flex flex-col items-center text-white mb-12">
                <span class="w-full h-0.5 bg-white mb-6"></span>
                <h1
                    class="text-3xl md:text-4xl lg:text-5xl font-serif text-center"
                >
                    Tema
                </h1>
                <span class="w-full h-0.5 bg-white mt-6"></span>
            </div>

            <!-- Grid Card -->
            <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <CategoryCard
                    v-for="category in paginatedCategories"
                    :key="category.slug"
                    :categoryImage="category.categoryImage"
                    :categoryName="category.categoryName"
                    :categoryDescription="
                        category.categoryDescription ||
                        'No description available'
                    "
                    :titleSize="'lg'"
                    @click="navigateToCategory(category.slug)"
                />
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8 gap-2">
                <button
                    @click="currentPage--"
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700"
                >
                    Previous
                </button>
                <div class="flex items-center px-4 text-white">
                    Page {{ currentPage }} of {{ totalPages }}
                </div>
                <button
                    @click="currentPage++"
                    :disabled="currentPage >= totalPages"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700"
                >
                    Next
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import CategoryCard from "./CategoryCard.vue";
import axios from "axios";

const categorys = ref([]);
const loading = ref(true);
const error = ref(null);
const currentPage = ref(1);
const itemsPerPage = 9;

// Computed properties for pagination
const totalPages = computed(() => {
    return Math.ceil(categorys.value.length / itemsPerPage);
});

const paginatedCategories = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return categorys.value.slice(start, end);
});

// Direct navigation function
const navigateToCategory = (slug) => {
    window.location.href = `/gallery/${slug}`;
};

onMounted(async () => {
    const options = {
        method: "POST",
        url: "/api/categories",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: "Bearer 123",
        },
        data: {
            category_name: "string",
            category_description: "string",
            category_image: "string",
        },
    };

    try {
        const response = await axios.get("/api/categories", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: "Bearer 123",
            },
        });

        // Pastikan response.data adalah array atau akses property yang benar
        let categoryArray = response.data;

        // Jika response memiliki struktur { data: [...] }
        if (response.data && Array.isArray(response.data.data)) {
            categoryArray = response.data.data;
        }

        // Jika masih bukan array, buat array kosong
        if (!Array.isArray(categoryArray)) {
            console.warn(
                "Data kategori tidak dalam format array:",
                categoryArray
            );
            categoryArray = [];
        }

        categorys.value = categoryArray.map((category) => ({
            categoryImage: category.category_image
                ? category.category_image.startsWith("http")
                    ? category.category_image
                    : `/storage/${category.category_image.replace(
                          /^public\//,
                          ""
                      )}`
                : "/js/Assets/default-photo.jpg",
            categoryName: category.category_name || "Untitled",
            titleSize: "base",
            categoryDescription:
                category.category_description || "No description available",
            slug: category.slug,
        }));
    } catch (err) {
        console.error("Gagal mengambil data category:", err);
        error.value = "Gagal mengambil data";
    } finally {
        loading.value = false;
    }
});
</script>
