<template>
    <main>
        <MainLayout>
            <title>
                Galeri Sejarah | Berisi Foto dan Video Sejarah | Galeri Sejarah
            </title>
            <meta
                name="description"
                content="Galeri Sejarah | Berisi Foto dan Video Sejarah | Galeri Sejarah"
            />
            <HeroSection />
            <SearchBar />
            <RecentUploads />
            <CategoryGrid />

            <!-- <TrendingContent /> -->
            <UpcomingEvent />
            <!-- <TrendingEvents /> -->
        </MainLayout>
    </main>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import HeroSection from "@/Components-landing/GalleryPage/HeroSection.vue";
import RecentUploads from "@/Components-landing/GalleryPage/RecentUploads.vue";
import SearchBar from "@/Components-landing/GalleryPage/SearchBar.vue";
import UpcomingEvent from "@/Components-landing/UpcomingEvent.vue";
import CategoryGrid from "@/Components-landing/GalleryPage/CategoryGrid.vue";
</script>
