<template>
    <section class="flex relative justify-center items-center w-full bg-black bg-opacity-70 py-10">
        <div class="relative flex flex-col items-center justify-center w-full max-w-[1440px] px-5 text-center">
            <!-- Garis Dekoratif Kiri (disembunyikan di mobile) -->
            <svg class="hidden md:block absolute left-0 w-40 lg:w-[447px]" viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path :d="leftLinePath" stroke="white" stroke-width="3.41894" />
            </svg>

            <!-- Teks JOIN US -->
            <h1 class="font-bellefair text-white text-6xl md:text-8xl tracking-wide leading-tight">
                JOIN
                <span class="block">US</span>
            </h1>

            <!-- Garis Dekoratif Kanan (disembunyikan di mobile) -->
            <svg class="hidden md:block absolute right-0 w-40 lg:w-[447px]" viewBox="0 0 447 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path :d="rightLinePath" stroke="white" stroke-width="3.41894" />
            </svg>
        </div>
    </section>

    <div class="relative flex flex-col items-center bg-black bg-opacity-70 overflow-hidden px-5 py-10">
        <div class="relative w-full max-w-[1300px] h-[500px] md:h-[650px] rounded-lg overflow-hidden">
            <img src="@/assets/img/Logo/joinus.png" class="object-cover w-full h-full rounded-lg" alt="Join Us Banner" />

            <!-- Wrapper untuk teks & tombol agar tetap berada di tengah -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/40  px-5">
                <h3 class="text-white text-lg md:text-2xl max-w-[80%]">
                    Gabung dengan kami untuk melihat dan menambah koleksi mengenai Kasunanan Surakarta.
                </h3>
                <button @click="$router.push('/member')" 
                    class="mt-5 text-white font-bellefair text-2xl md:text-3xl uppercase tracking-wider 
                    px-6 py-3 border-2 border-white bg-black/50 rounded-lg transition duration-300 hover:bg-white/20 cursor-pointer">
                    JOIN US
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const leftLinePath = "M10 50 C150 100, 250 0, 400 50";
const rightLinePath = "M10 50 C150 0, 250 100, 400 50";
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bellefair&display=swap");

.font-bellefair {
    font-family: "Bellefair", serif;
}
</style>
