<template>
  <main>
    <MainLayout>
      <HeroSection />
      <SearchBar />
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
  import ImageGrid from '@/Components-landing/GalleryPage/ImageGrid.vue'
  // import TrendingEvents from '../components/BudayaPage/TrendingEvents.vue'
  import UpcomingEvent from '@/Components-landing/UpcomingEvent.vue'
  import CategoryGrid from '@/Components-landing/GalleryPage/CategoryGrid.vue';
</script>
