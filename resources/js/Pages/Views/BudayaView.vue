<template>
    <MainLayout>
        <HeroSection />
        <ImageGallery />
        <TrendingEvents />
        <JoinUs />
    </MainLayout>
</template>

<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue'
    import HeroSection from '@/Components-landing/BudayaPage/HeroSection.vue';
    import ImageGallery from '@/Components-landing/BudayaPage/ImageGallery.vue';
    import TrendingEvents from '@/Components-landing/BudayaPage/TrendingEvents.vue';
    import JoinUs from '@/Components-landing/JoinUsSection.vue';
</script>
