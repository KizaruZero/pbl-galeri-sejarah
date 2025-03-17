<template>
  <main>
    <MainLayout>
      <HeroSection />
      <ImageGallery />
      <ImageGallery />
      <UpcomingEvent />
      <!-- <TrendingEvents /> -->
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '../components/layouts/MainLayout.vue';
  import HeroSection from '../components/GalleryPage/HeroGallery.vue'
  import ImageGallery from '../components/GalleryPage/ImageGallery.vue'
  // import TrendingEvents from '../components/BudayaPage/TrendingEvents.vue'
  import UpcomingEvent from '../components/LandingPage/UpcomingEvent.vue'
</script>