<template>
    <main>
        <MainLayout>
            <SeoHead
                title="Pesona Surakarta"
                description="Kumpulan Foto, Video, Event dan Sejarah"
            />

            <HeroSection />
            <!-- <PakubuwonoSection /> -->
            <TrendingContent />
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
import TrendingContent from "@/Components-landing/LandingPage/TrendingContent.vue";
import JoinUs from "@/Components-landing/JoinUsSection.vue";
import Article from "@/Components-landing/LandingPage/ArticleLanding.vue";
import SeoHead from "@/Components/SeoHead.vue";
</script>