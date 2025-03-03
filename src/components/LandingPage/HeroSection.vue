<template>
    <section class="relative w-full h-screen overflow-hidden">
        <img src="@/assets/img/landing/keraton.jpg"
            alt="Kasunanan Surakarta" class="w-full h-full object-cover" />

        <!-- Gradient overlay -->
        <div class="absolute left-0 top-[33%] w-full h-[49.7%]"
            style="background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.7) 100%);"
            role="presentation"></div>

        <!-- Hero content -->
        <h1
            class="absolute left-1/2 top-[47.3%] w-full max-w-[1141px] text-8xl text-center text-white uppercase font-bellefair -translate-x-1/2 max-md:px-5 max-md:text-7xl max-sm:px-4 max-sm:text-5xl">
            KARATON SURAKARTA HADININGRAT
        </h1>
    </section>
</template>

<script setup>
    // HeroSection component
</script>

<style>
    /* Import Bellefair font */
    @import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

    /* Define custom font family */
    .font-bellefair {
        font-family: "Bellefair", serif;
    }
</style>