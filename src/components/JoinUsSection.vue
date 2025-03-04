<template>
    <section class="flex overflow-hidden relative justify-center items-center w-full bg-black bg-opacity-70">
        <div class="relative w-full max-w-[1440px]">
            <!-- Garis Dekoratif Kiri -->
            <svg class="absolute left-0 top-1/2 transform -translate-y-1/2" width="447" height="72" viewBox="0 0 447 72"
                fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path :d="leftLinePath" stroke="white" stroke-width="3.41894" />
            </svg>

            <!-- Teks JOIN US -->
            <div class="flex flex-col items-center justify-center">
                <h1 class="font-bellefair text-8xl text-white leading-tight tracking-normal text-center">
                    JOIN
                    <span class="block">US</span>
                </h1>
            </div>
            <!-- Garis Dekoratif Kanan -->
            <svg class="absolute right-0 top-1/2 transform -translate-y-1/2" width="447" height="72"
                viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path :d="rightLinePath" stroke="white" stroke-width="3.41894" />
            </svg>
        </div>
    </section>
    <div class="relative flex flex-col items-center bg-black bg-opacity-70 rounded-lg overflow-hidden">
        <div class="w-full max-w-[1300px] h-[650px] mt-5 rounded-lg overflow-hidden">
            <img src="@/assets/img/join.png" class="object-cover w-full h-full rounded-lg"
                alt="Keraton Solo background image" />

            <!-- Tombol "JOIN US" -->
            <button class="absolute bottom-16 left-10 text-white font-bellefair text-4xl uppercase tracking-wider 
            px-6 py-2 border-2 border-white backdrop-blur-sm rounded-md transition duration-300 hover:bg-white/20 ml-77 mb-30">
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