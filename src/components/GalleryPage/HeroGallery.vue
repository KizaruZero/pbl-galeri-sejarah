<template>
    <section class="relative flex items-center justify-center w-full h-screen max-sm:h-[500px]">
        <img 
            src="@/assets/img/gallery/hero.png" 
            class="absolute top-0 left-0 w-full h-full object-cover" 
            alt="Keraton Solo background image"
        />
        <!-- Overlay Hitam Transparan -->
        <div class="absolute inset-0 bg-[var(--color-black)]/60"></div>
        <h1
            class="relative text-center text-white uppercase font-bellefair 
            text-5xl max-md:text-6xl max-sm:text-4xl max-w-[1141px] px-5">
            Menelusuri Keindahan Kasunanan Surakarta
        </h1>
    </section>
</template>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}
</style>
