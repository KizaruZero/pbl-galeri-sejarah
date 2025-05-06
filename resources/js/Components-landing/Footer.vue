<template>
    <footer class="bg-black text-white py-10 px-5 md:px-20">
        <div
            class="flex flex-col md:grid md:grid-cols-3 gap-10">

            <!-- Bagian Kiri: Logo -->
            <div class="w-full flex flex-col gap-3 items-center md:items-start text-center md:text-left">
                <div class="flex items-center gap-4">
                    <img src="@assets/Logo/LOGO.png" class="object-contain w-14 md:w-16" alt="Kasunanan logo" />
                    <div class="flex flex-col leading-tight">
                        <h2 class="text-xl md:text-2xl uppercase font-semibold">Kasunanan</h2>
                        <h2 class="text-xl md:text-2xl uppercase font-semibold">Surakarta</h2>
                    </div>
                </div>
            </div>

            <!-- Bagian Tengah: Navigasi -->
            <div class="w-full flex justify-center">
                <nav class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm md:text-base uppercase text-center">
                    <router-link to="/" class="hover:underline">Home</router-link>
                    <router-link to="/events" class="hover:underline">Events</router-link>
                    <router-link to="/gallery" class="hover:underline">Gallery</router-link>
                    <router-link to="/article" class="hover:underline">Article</router-link>
                    <router-link to="/contact" class="hover:underline">Contact</router-link>
                    <router-link to="/login" class="hover:underline">Member</router-link>
                </nav>
            </div>

            <!-- Bagian Kanan: Kontak -->
            <div
                class="w-full flex flex-col gap-3 items-center md:items-end text-center md:text-right text-sm md:text-base font-light">
                <div class="flex items-center gap-2">
                    <img src="@assets/Logo/Vector.png" class="w-5" alt="Email icon" />
                    <a href="mailto:work@uxmcreativestudio.com" class="break-words">work@uxmcreativestudio.com</a>
                </div>
                <div class="flex items-center gap-2">
                    <img src="@assets/Logo/Vector2.png" class="w-5" alt="Location icon" />
                    <p class="break-words">New York, USA</p>
                </div>
            </div>

        </div>
    </footer>
</template>
