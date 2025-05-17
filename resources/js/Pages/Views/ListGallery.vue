<template>
  <MainLayout>
    <HeroSection />
    <ImageGrid />
    <VideoGrid />
    <!-- <TrendingEvents /> -->
    <JoinUs />
  </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import HeroSection from "@/Components-landing/ListGallery/HeroSection.vue";
import ImageGrid from "@/Components-landing/ListGallery/ImageGrid.vue";
import VideoGrid from "@/Components-landing/ListGallery/VideoGrid.vue";
// import TrendingEvents from '@/Components-landing/EventPage/TrendingEvents.vue';
import JoinUs from "@/Components-landing/JoinUsSection.vue";
</script>
