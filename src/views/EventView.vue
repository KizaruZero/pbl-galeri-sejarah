<template>
    <MainLayout>
        <HeroSection />
        <!-- <ImageGallery /> -->
        <Event/>
        <TrendingEvents />
        <JoinUs />
    </MainLayout>
</template>

<script setup>
    import MainLayout from '@/components/layouts/MainLayout.vue'
    import HeroSection from '@/components/EventPage/HeroSection.vue';
    // import ImageGallery from '@/components/EventPage/ImageGallery.vue';
    import Event from '@/components/EventPage/EventGrid.vue';
    import TrendingEvents from '@/components/EventPage/TrendingEvents.vue';
    import JoinUs from '@/components/JoinUsSection.vue';
</script>
