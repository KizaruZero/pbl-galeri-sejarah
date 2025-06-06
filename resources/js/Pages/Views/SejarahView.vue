<script setup>
import MainLayout from "@/Layouts//MainLayout.vue";
import HeroSection from "@/Components-landing/SejarahPage/HeroSejarah.vue";
import ArticleSection from "@/Components-landing/SejarahPage/ArticleSejarah.vue";
import SeoHead from "@/Components/SeoHead.vue";
</script>

<template>
    <MainLayout>
        <SeoHead title="Sejarah" description="Informasi sejarah Nusantara" />
        <HeroSection />
        <ArticleSection />
    </MainLayout>
</template>
