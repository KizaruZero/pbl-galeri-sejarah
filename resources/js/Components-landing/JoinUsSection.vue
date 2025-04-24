<template>
    <section class="relative bg-black text-white py-24 px-6 overflow-hidden border-y border-white">
        <!-- Subtle background shapes -->
        <div class="absolute top-10 left-10 w-72 h-72 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center relative z-10">
            <!-- Left side with icon and title -->
            <div class="flex items-center space-x-4">
                <div class="text-white text-5xl bg-white/10 p-4 rounded-full shadow-md">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold">Become a Member</h2>
            </div>

            <!-- Right side -->
            <div>
                <p class="text-gray-400 text-lg leading-relaxed mb-6">
                    Join as a member to enjoy exclusive features.<br />
                    Upload images and videos of your work, and<br />
                    share your creativity with the world.
                </p>

                <Link href="/register"
                    class="inline-block bg-white text-black font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-gray-100 transition duration-300">
                Join Us
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
    import {
        Link
    } from '@inertiajs/vue3';

</script>

<style scoped>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

</style>
