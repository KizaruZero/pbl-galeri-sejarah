<template>
  <main>
    <MainLayout>
      <HeroSection />
      <SearchBar />
      <ImageGrid />
      <CategoryGrid />

      <!-- <ImageGrid /> -->
      <UpcomingEvent />
      <!-- <TrendingEvents /> -->
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '@/Layouts/MainLayout.vue';
  import HeroSection from '@/Components-landing/GalleryPage/HeroSection.vue'
  import SearchBar from '@/Components-landing/GalleryPage/SearchBar.vue'
  import ImageGrid from '@/Components-landing/GalleryPage/TrendingContent.vue'
  import UpcomingEvent from '@/Components-landing/UpcomingEvent.vue'
  import CategoryGrid from '@/Components-landing/GalleryPage/CategoryGrid.vue';
</script>
