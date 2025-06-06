<script setup>
import MainLayout from "@/Layouts//MainLayout.vue";
import HeroSection from "@/Components-landing/ArticlePage/HeroSejarah.vue";
import ArticleSection from "@/Components-landing/ArticlePage/ArticleSejarah.vue";
</script>

<template>
    <MainLayout>
        <title>Artikel Sejarah Indonesia | Galeri Sejarah</title>
        <meta
            name="description"
            content="Artikel dan catatan sejarah Indonesia dari berbagai periode waktu."
        />
        <HeroSection />
        <ArticleSection />
    </MainLayout>
</template>
