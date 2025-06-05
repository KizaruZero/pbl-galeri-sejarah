<template>
  <section class="w-full bg-white dark:bg-black py-16 px-6 md:px-10">
    <div class="max-w-[1192px] mx-auto">
      <!-- Judul -->
      <div class="flex flex-col items-center text-black dark:text-white mb-12">
        <span class="w-full h-0.5 bg-black dark:bg-white mb-6"></span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif text-center">Tema</h1>
        <span class="w-full h-0.5 bg-black dark:bg-white mt-6"></span>
      </div>

      <!-- Loading State -->
      <div
        v-if="loading"
        class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 animate-pulse"
      >
        <!-- Skeleton cards (9 items to match pagination) -->
        <div
          v-for="i in 9"
          :key="i"
          class="bg-zinc-900 rounded-lg overflow-hidden shadow-lg h-[350px]"
        >
          <!-- Image placeholder -->
          <div class="w-full h-[200px] bg-gray-800"></div>

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
      <div v-if="error" class="text-center text-red-500 py-12">
        <p class="text-xl">{{ error }}</p>
        <button
          @click="fetchCategories"
          class="mt-4 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
        >
          Coba Lagi
        </button>
      </div>

      <!-- Content -->
      <div v-if="!loading && !error">
        <!-- Grid Card -->
        <div
          v-if="categorys.length > 0"
          class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
        >
          <CategoryCard
            v-for="category in paginatedCategories"
            :key="category.slug"
            :categoryImage="category.categoryImage"
            :categoryName="category.categoryName"
            :categoryDescription="
              category.categoryDescription || 'No description available'
            "
            :titleSize="'lg'"
            @click="navigateToCategory(category.slug)"
          />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center text-white py-12">
          <p class="text-xl">Tidak ada tema yang tersedia</p>
        </div>

        <!-- Pagination -->
        <div v-if="categorys.length > 0" class="flex justify-center mt-8 gap-2">
          <button
            @click="currentPage--"
            :disabled="currentPage === 1"
            class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
          >
            Previous
          </button>
          <div class="flex items-center px-4 text-black dark:text-white">
            Page {{ currentPage }} of {{ totalPages }}
          </div>
          <button
            @click="currentPage++"
            :disabled="currentPage >= totalPages"
            class="px-4 py-2 bg-gray-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-700 transition-colors duration-300"
          >
            Next
          </button>
        </div>
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

const fetchCategories = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.get("/api/categories", {
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: "Bearer 123",
      },
    });

    // Handle different response formats
    let categoryArray = Array.isArray(response.data)
      ? response.data
      : response.data?.data || [];

    categorys.value = categoryArray.map((category) => ({
      categoryImage: category.category_image
        ? category.category_image.startsWith("http")
          ? category.category_image
          : `/storage/${category.category_image.replace(/^public\//, "")}`
        : "/js/Assets/default-photo.jpg",
      categoryName: category.category_name || "Untitled",
      titleSize: "base",
      categoryDescription: category.category_description || "No description available",
      slug: category.slug,
    }));
  } catch (err) {
    console.error("Gagal mengambil data category:", err);
    error.value = "Gagal memuat data tema. Silakan coba lagi nanti.";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCategories();
});
</script>
