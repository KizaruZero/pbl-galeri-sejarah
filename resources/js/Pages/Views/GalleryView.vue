<template>
  <main>
    <MainLayout>
      <HeroSection />
      <SearchBar />
      <ImageGallery />
      <!-- <ImageGallery /> -->
      <UpcomingEvent />
      <!-- <TrendingEvents /> -->
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '../../Layouts/MainLayout.vue';
  import HeroSection from '../../Components-landing/GalleryPage/HeroSection.vue'
  import SearchBar from '../../Components-landing/GalleryPage/SearchBar.vue'
  import ImageGallery from '../../Components-landing/GalleryPage/ImageGrid.vue'
  // import TrendingEvents from '../components/BudayaPage/TrendingEvents.vue'
  import UpcomingEvent from '../../Components-landing/UpcomingEvent.vue'
</script>
