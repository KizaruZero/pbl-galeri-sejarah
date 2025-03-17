<template>
  <main>
    <MainLayout>
      <HeroSection />
      <Search/>
      <ImageGallery />
      <ImageGallery />
      <UpcomingEvent />
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '../components/layouts/MainLayout.vue';
  import HeroSection from '../components/GalleryPage/HeroGallery.vue'
  import Search from '../components/GalleryPage/SearchBar.vue'
  import HeroSection from '../components/GalleryPage/HeroSection.vue'
  import ImageGallery from '../components/GalleryPage/ImageGallery.vue'
  import UpcomingEvent from '../components/LandingPage/UpcomingEvent.vue'
</script>