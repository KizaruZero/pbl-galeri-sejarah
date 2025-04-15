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
  import MainLayout from '@/Layouts/MainLayout.vue';
  import HeroSection from '@/Components-landing/GalleryPage/HeroSection.vue'
  import ImageGallery from '@/Components-landing/GalleryPage/ImageGallery.vue'
  // import TrendingEvents from '@/Components-landing/BudayaPage/TrendingEvents.vue'
  import UpcomingEvent from '@/Components-landing/LandingPage/UpcomingEvent.vue'
</script>
