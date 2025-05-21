<template>
  <!-- Spinner Loading -->
  <section
    v-if="loading"
    class="flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] bg-black"
  >
    <div
      class="w-10 h-10 border-4 border-white border-t-transparent rounded-full animate-spin"
    ></div>
  </section>

  <!-- Hero Section -->
  <section
    v-else-if="currentPhoto"
    class="relative flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] overflow-hidden"
  >
    <div class="relative w-full h-full">
      <img
        :src="currentPhoto.imageUrl"
        :alt="currentPhoto.altText"
        class="absolute inset-0 w-full h-full object-cover object-center"
      />
    </div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Teks Tengah -->
    <div
      class="absolute inset-0 flex flex-col items-center justify-center z-10 text-white text-center px-4"
    >
      <h1
        class="uppercase font-bellefair text-6xl max-md:text-4xl max-sm:text-2xl leading-tight"
      >
        {{ currentPhoto.title }}
      </h1>
    </div>
  </section>

  <!-- Error State -->
  <section
    v-else
    class="flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px] bg-black text-white"
  >
    <p>Tidak dapat memuat data</p>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";

const photos = ref([]);
const loading = ref(true);
const error = ref(null);

const currentPhoto = computed(() => {
  // Ambil slug dari URL pathname
  const slug = window.location.pathname.split("/").pop();

  if (!slug && photos.value.length > 0) {
    // Jika tidak ada slug, ambil yang pertama
    return photos.value[0];
  }
  return photos.value.find((photo) => photo.slug === slug);
});

onMounted(async () => {
  try {
    const response = await axios.get("/api/categories", {
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: "Bearer 123",
      },
    });

    let categoryArray = response.data;

    if (response.data && Array.isArray(response.data.data)) {
      categoryArray = response.data.data;
    }

    if (!Array.isArray(categoryArray)) {
      console.warn("Data kategori tidak dalam format array:", categoryArray);
      categoryArray = [];
    }

    photos.value = categoryArray.map((category) => ({
      imageUrl: category.category_image
        ? category.category_image.startsWith("http")
          ? category.category_image
          : `/storage/${category.category_image.replace(/^public\//, "")}`
        : "/default-category.jpg",
      title: category.category_name || "Untitled",
      description: category.category_description || "No description available",
      slug: category.slug,
      altText: category.category_name || "Category image",
    }));
  } catch (err) {
    console.error("Gagal mengambil data kategori:", err);
    error.value = "Gagal mengambil data";
  } finally {
    loading.value = false;
  }
});
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
  font-family: "Bellefair", serif;
}

/* Spinner */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
