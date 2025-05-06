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
            <div
                class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
            >
                <CategoryCard
                    v-for="category in categorys"
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
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import CategoryCard from "./CategoryCard.vue";
import axios from "axios";

const categorys = ref([]);
const loading = ref(true);
const error = ref(null);

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
                : "/default-category.jpg",
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
