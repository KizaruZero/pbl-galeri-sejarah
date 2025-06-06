<template>
    <main>
        <MainLayout>
            <SeoHead
                title="Galeri Sejarah"
                description="Kumpulan Foto, Video, Event dan Sejarah"
            />

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
import SeoHead from "@/Components/SeoHead.vue";
</script>
