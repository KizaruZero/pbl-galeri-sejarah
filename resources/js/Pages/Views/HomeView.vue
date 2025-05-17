<template>
  <main>
    <MainLayout>
      <HeroSection />
      <ImageLanding />
      <JoinUs />
      <!-- <PakubuwonoSection /> -->
      <UpcomingEvent />
      <Article />
    </MainLayout>
  </main>
</template>

<script setup>
  import MainLayout from '@/Layouts/MainLayout.vue'
  import HeroSection from '@/Components-landing/LandingPage/HeroSection.vue'
  import ImageLanding from '@/Components-landing/LandingPage/LandingGrid.vue'
  // import PakubuwonoSection from '@/Components-landing/LandingPage/PakubuwonoSection.vue'
  import UpcomingEvent from '@/Components-landing/UpcomingEvent.vue'
  import Article from '@/Components-landing/LandingPage/ArticleLanding.vue'
  import JoinUs from '@/Components-landing/JoinUsSection.vue'
  
</script>
