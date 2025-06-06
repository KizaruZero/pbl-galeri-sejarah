<template>
    <MainLayout>
        <title>Event Yang Sedang Diselenggarakan | Galeri Sejarah</title>
        <meta
            name="description"
            content="Event yang sedang berjalan dan tersedia di website kami"
        />
        <HeroSection />
        <Event />
        <!-- <TrendingEvents /> -->
        <JoinUs />
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import HeroSection from "@/Components-landing/EventPage/HeroSection.vue";
import Event from "@/Components-landing/EventPage/EventGrid.vue";
// import TrendingEvents from '@/Components-landing/EventPage/TrendingEvents.vue';
import JoinUs from "@/Components-landing/JoinUsSection.vue";
</script>
