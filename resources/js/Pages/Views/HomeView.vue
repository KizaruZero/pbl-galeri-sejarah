<template>
  <main>
    <MainLayout>
      <HeroSection />
      <!-- <PakubuwonoSection /> -->
      <UpcomingEvent />
      <JoinUs />
      <Article />
    </MainLayout>
  </main>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import HeroSection from "@/Components-landing/LandingPage/HeroSection.vue";
// import PakubuwonoSection from '@/Components-landing/LandingPage/PakubuwonoSection.vue'
import UpcomingEvent from "@/Components-landing/UpcomingEvent.vue";
import Article from "@/Components-landing/LandingPage/ArticleLanding.vue";
import JoinUs from "@/Components-landing/JoinUsSection.vue";
</script>
