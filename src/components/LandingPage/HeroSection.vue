<template>
    <section class="overflow-hidden relative w-full h-screen">
        <img 
            src="@/assets/img/landing/keraton.jpg" 
            class="object-cover size-full" 
            alt="Keraton Solo background image"
        />
        <!-- Overlay Hitam Transparan -->
        <div class="absolute inset-0 bg-[var(--color-black)]/60"></div>
        <h1
            class="absolute top-3/4 left-2/4 w-full text-8xl text-center text-white uppercase 
            -translate-x-2/4 -translate-y-2/4 max-w-[1141px] font-bellefair 
            max-md:px-5 max-md:py-0 max-md:text-6xl max-sm:px-4 max-sm:py-0 max-sm:text-4xl">
            KARATON SURAKARTA HADININGRAT
        </h1>
    </section>
</template>

<script setup>
    
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}
</style>
