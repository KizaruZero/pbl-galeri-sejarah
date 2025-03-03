<template>
    <section class="overflow-hidden relative w-full h-screen">
        <img 
            src="@/assets/img/budaya/budaya.jpg" 
            class="object-cover size-full" 
            alt="Keraton Solo background image"
        />
        <!-- Overlay Hitam Transparan -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <h1
            class="absolute top-2/4 left-2/4 w-full text-8xl text-center text-white uppercase 
            -translate-x-2/4 -translate-y-2/4 max-w-[1141px] font-bellefair 
            max-md:px-5 max-md:py-0 max-md:text-6xl max-sm:px-4 max-sm:py-0 max-sm:text-4xl">
            Memahami Budaya yang Ada di Keraton Solo
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
