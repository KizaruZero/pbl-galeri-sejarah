<template>
    <section class="flex overflow-hidden relative justify-center items-center w-full bg-black bg-opacity-70">
        <div class="relative w-full max-w-[1440px] px-5">
            <!-- Garis Dekoratif Kiri -->
            <svg class="absolute left-0 top-1/2 transform -translate-y-1/2 max-md:hidden" width="447" height="72"
                viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path :d="leftLinePath" stroke="white" stroke-width="3.41894" />
            </svg>

            <!-- Teks JOIN US -->
            <div class="flex flex-col items-center justify-center text-center">
                <h1
                    class="font-bellefair text-8xl text-white leading-tight tracking-normal max-md:text-6xl max-sm:text-4xl">
                    JOIN
                    <span class="block">US</span>
                </h1>
            </div>

            <!-- Garis Dekoratif Kanan -->
            <svg class="absolute right-0 top-1/2 transform -translate-y-1/2 max-md:hidden" width="447" height="72"
                viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path :d="rightLinePath" stroke="white" stroke-width="3.41894" />
            </svg>
        </div>
    </section>

    <div class="relative flex flex-col items-center bg-black bg-opacity-70 overflow-hidden px-5">
        <div class="w-full max-w-[1300px] h-[650px] mt-5 rounded-lg overflow-hidden max-md:h-[400px] max-sm:h-[300px]">
            <img src="@/assets/img/join.png" class="object-cover w-full h-full rounded-lg"
                alt="Keraton Solo background image" />

            <!-- Tombol JOIN US -->
            <button @click="$router.push('/member')" class="absolute bottom-50 left-1/2 transform -translate-x-1/2 text-white font-bellefair text-3xl uppercase tracking-wider
        px-5 py-2 border-2 border-white backdrop-blur-sm rounded-md transition duration-300 hover:bg-white/20
        max-md:text-2xl max-sm:text-xl max-md:px-4 max-md:py-1 cursor-pointer">
                JOIN US
            </button>
        </div>
    </div>
</template>

<script setup>
    import {
        ref
    } from "vue";

    const leftLinePath = "M10 50 C150 100, 250 0, 400 50";
    const rightLinePath = "M10 50 C150 0, 250 100, 400 50";
</script>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

    .font-bellefair {
        font-family: "Bellefair", serif;
    }
</style>