<script setup>
import Navbar from "@/Components-landing/Navbar.vue";
import Footer from "@/Components-landing/Footer.vue";
import BaseLoading from "@/Components/BaseLoading.vue";
import SeoHead from "@/Components/SeoHead.vue";

import "lite-youtube-embed/src/lite-yt-embed.css";
</script>

<template>
    <lite-youtube-embed videoid="YOUR_VIDEO_ID" playlabel="Play Video" />

    <BaseLoading />
    <Navbar />
    <main>
        <SeoHead
            title="Karesidenan Surakarta"
            description="Kumpulan Foto, Video, Event dan Sejarah Surakarta"
        />
        <slot></slot>
    </main>
    <Footer />
</template>
