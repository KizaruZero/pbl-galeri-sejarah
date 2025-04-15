<template>
    <MainLayout>
        <HeroSection />
        <ImageGallery />
        <TrendingEvents />
        <JoinUs />
    </MainLayout>
</template>

<script setup>
    import MainLayout from '@/components/layouts/MainLayout.vue'
    import HeroSection from '@/components/BudayaPage/HeroSection.vue';
    import ImageGallery from '@/components/BudayaPage/ImageGallery.vue';
    import TrendingEvents from '@/components/BudayaPage/TrendingEvents.vue';
    import JoinUs from '@/components/JoinUsSection.vue';
</script>
