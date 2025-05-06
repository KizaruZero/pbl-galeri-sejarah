<template>
    <section class="relative flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px]">
        <img 
            :src="companyProfile?.bg_events_url"
            class="absolute inset-0 w-full h-full object-cover object-center" 
            alt="Keraton Solo background image"
        />
        <!-- Overlay Hitam Transparan -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <h1
            class="relative z-10 text-center text-white uppercase font-bellefair 
            text-6xl max-md:text-4xl max-sm:text-2xl px-5 leading-tight" v-html="companyProfile?.events_text">
        </h1>
    </section>
</template>

<script setup>
    import {
        ref,
        computed,
        onMounted,
    } from 'vue';
    import {
        usePage,
    } from '@inertiajs/vue3';
    import axios from 'axios';
    const companyProfile = ref(null);
    // Fungsi untuk mengambil data company profile
    const fetchCompanyProfile = async () => {
        try {
            const response = await axios.get('/api/company-profile');
            companyProfile.value = response.data.data;
        } catch (error) {
            console.error('Error fetching company profile:', error);
        }
    };
    onMounted(() => {
        fetchCompanyProfile();
    });
    // Ambil data user dari Inertia share props
    const page = usePage();
    const user = computed(() => page.props.auth ?.user || null);
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}
</style>
