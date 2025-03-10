<template>
  <main>
    <MainLayout>
      <HeroSection />
      <ImageGallery />
      <ImageGallery />
      <TrendingEvents />
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '../components/layouts/MainLayout.vue';
  import HeroSection from '../components/GalleryPage/HeroSection.vue'
  import ImageGallery from '../components/GalleryPage/ImageGallery.vue'
  import TrendingEvents from '../components/BudayaPage/TrendingEvents.vue'
</script>