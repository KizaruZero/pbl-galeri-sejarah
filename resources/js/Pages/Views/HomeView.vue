<template>
  <main>
    <MainLayout>
      <HeroSection />
      <PakubuwonoSection />
      <UpcomingEvent />
      <JoinUs />
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '@/Layouts/MainLayout.vue'
  import HeroSection from '@/Components-landing/LandingPage/HeroSection.vue'
  import PakubuwonoSection from '@/Components-landing/LandingPage/PakubuwonoSection.vue'
  import UpcomingEvent from '@/Components-landing/LandingPage/UpcomingEvent.vue'
  import JoinUs from '@/Components-landing/JoinUsSection.vue';
</script>