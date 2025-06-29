<script setup>
import Navbar from "@/Components-landing/Navbar.vue";
import Footer from "@/Components-landing/Footer.vue";
import BaseLoading from "@/Components/BaseLoading.vue";
import SeoHead from "@/Components/SeoHead.vue";
import { watchEffect } from 'vue'


import "lite-youtube-embed/src/lite-yt-embed.css";

import { ref, onMounted } from 'vue'
import axios from 'axios'
// Ambil CMS Name
const companyProfile = ref(null);

const fetchCompanyProfile = async () => {
    try {
        const response = await axios.get('/api/company-profile');
        companyProfile.value = response.data.data;
    } catch (error) {
        console.error("Gagal mengambil data CMS:", error);
    }
};

onMounted(() => {
    fetchCompanyProfile();
});

watchEffect(() => {
  if (companyProfile.value?.logo_url) {
    const favicon = document.querySelector("link[rel~='icon']");
    if (favicon) {
      favicon.href = companyProfile.value.logo_url;
    } else {
      const link = document.createElement("link");
      link.rel = "icon";
      link.href = companyProfile.value.logo_url;
      document.head.appendChild(link);
    }
  }
});

</script>

<template>
    <lite-youtube-embed videoid="YOUR_VIDEO_ID" playlabel="Play Video" />

    <BaseLoading />
    <Navbar />
    <main>
        <SeoHead
            v-if="companyProfile"
            :title="companyProfile.cms_name"
            description="Kumpulan Foto, Video, Event dan Sejarah"
        />
        <slot></slot>
    </main>
    <Footer />
</template>
