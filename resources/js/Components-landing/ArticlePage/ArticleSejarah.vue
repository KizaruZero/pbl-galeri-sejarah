<template>
  <div class="grid grid-cols-2 sm:flex sm:flex-wrap justify-center gap-4 sm:gap-6 bg-black p-4 sm:p-6">
    <article
      v-for="(item, index) in articles"
      :key="index"
      @click="goToDetail(item)"
      class="overflow-hidden px-4 pt-4 pb-6 text-white cursor-pointer transition hover:scale-105 
             w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] max-w-[470px] text-center"
    >
      <img
        :src="item.image_url"
        class="object-cover w-full aspect-[1.38] rounded-t-lg"
        :alt="item.title"
      />
      <h2 class="mt-3 text-base sm:text-lg lg:text-2xl font-semibold leading-tight">
        {{ item.title }}
      </h2>
      <p class="mt-2 text-xs sm:text-sm text-gray-300 leading-relaxed">
        {{ item.content }}
      </p>
    </article>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const articles = ref([]);

const goToDetail = (item) => {
  router.visit(route("Detail", { slug: item.slug }));
};

onMounted(async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/article");
    // pastikan image URL-nya sesuai dengan struktur response dari controller
    articles.value = response.data.map(item => ({
      ...item,
      image_url: item.image_url.startsWith('http')
        ? item.image_url
        : `/storage/${item.image_url.replace(/^public\//, '')}`
    }));
  } catch (error) {
    console.error("Gagal mengambil data artikel:", error);
  }
});
</script>
