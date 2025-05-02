<template>
    <section class="relative flex items-center justify-center w-full aspect-[16/9] max-md:aspect-[4/3] max-sm:h-[300px]">
        <img 
            src="@assets/Budaya/budaya.jpg" 
            class="absolute inset-0 w-full h-full object-cover object-center" 
            alt="Keraton Solo background image"
        />
        <!-- Overlay Hitam Transparan -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <h1
            class="relative z-10 text-center text-white uppercase font-bellefair 
            text-6xl max-md:text-4xl max-sm:text-2xl px-5 leading-tight">
            Memahami Budaya yang Ada di <br> Keraton Solo
        </h1>
    </section>
</template>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}
</style>
