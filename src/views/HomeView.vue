<template>
  <main>
    <MainLayout>
      <HeroSection />
      <PakubuwonoSection />
      <UpcomingEvent />
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '../Layout/MainLayout.vue';
  import HeroSection from '../components/LandingPage/HeroSection.vue'
  import PakubuwonoSection from '../components/LandingPage/PakubuwonoSection.vue'
  import UpcomingEvent from '../components/LandingPage/UpcomingEvent.vue'
</script>