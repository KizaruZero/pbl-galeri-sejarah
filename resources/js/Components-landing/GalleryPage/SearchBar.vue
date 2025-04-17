<template>
    <div class="relative flex flex-col items-center w-full bg-black py-10">
        <div class="relative flex flex-col items-center w-full max-w-[1300px] rounded-lg shadow-lg px-5 py-10">
            <!-- Form Pencarian -->
            <form class="w-full mt-8">
                <div class="flex w-full">
                    <!-- Tombol Sidebar untuk Mobile -->
                    <button
                    @click="toggleSidebar"
                    class="md:hidden px-4 py-3 bg-black text-white border border-white rounded-l-lg"
                    >
                    ☰
                    </button>

                    <!-- Dropdown Kategori (Desktop) -->
                    <div class="hidden md:block relative">
                    <button
                        @click="toggleDropdown"
                        type="button"
                        class="z-10 inline-flex items-center px-5 py-3 text-base font-medium text-white bg-black border border-white rounded-l-lg hover:text-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-500"
                    >
                        {{ selectedCategory }}
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 10 6">
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 1 4 4 4-4"
                        />
                        </svg>
                    </button>

                    <!-- Dropdown List -->
                    <div
                        v-if="showDropdown"
                        class="absolute top-full left-0 w-48 bg-black text-white rounded-lg shadow-lg mt-2 border border-gray-700 z-50 transition-opacity duration-300"
                    >
                        <ul class="py-2 text-sm">
                        <li v-for="category in categories" :key="category">
                            <button
                            @click="selectCategory(category)"
                            type="button"
                            class="block w-full px-4 py-3 text-left hover:bg-gray-800"
                            >
                            {{ category }}
                            </button>
                        </li>
                        </ul>
                    </div>
                    </div>

                    <!-- Input + Tombol Search -->
                    <div class="flex-grow relative">
                    <input
                        type="search"
                        class="w-full p-3 text-base text-white bg-black border border-white md:rounded-none rounded-r-lg focus:ring-gray-500 focus:border-gray-500"
                        placeholder="Search Mockups, Logos, Design Templates..."
                        required
                    />
                    <button
                        type="submit"
                        class="absolute top-0 right-0 h-full px-4 text-black bg-white border border-white rounded-r-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-500"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 20 20">
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                        />
                        </svg>
                        <span class="sr-only">Search</span>
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Sidebar (Mobile) -->
    <div v-if="showSidebar" class="fixed inset-0 bg-black bg-opacity-80 flex flex-col justify-center items-center text-white transition-transform transform translate-x-0 md:hidden">
        <div class="relative w-full max-w-md bg-black p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center border-b border-gray-700 pb-4">
                <span class="text-lg font-semibold">Select Category</span>
                <button @click="toggleSidebar" class="text-gray-300 hover:text-white text-2xl">&times;</button>
            </div>
            <ul class="mt-5 space-y-3 text-lg">
                <li v-for="category in categories" :key="category">
                    <button @click="selectCategory(category)" type="button"
                        class="block w-full px-6 py-4 text-left bg-gray-900 hover:bg-gray-700 rounded-lg">
                        {{ category }}
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const showDropdown = ref(false);
const showSidebar = ref(false);
const selectedCategory = ref("All categories");
const categories = ref(["Mockups", "Templates", "Design", "Logos"]);

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
};

const toggleSidebar = () => {
    showSidebar.value = !showSidebar.value;
};

const selectCategory = (category) => {
    selectedCategory.value = category;
    showDropdown.value = false; // Tutup dropdown (Desktop)
    showSidebar.value = false; // Tutup sidebar (Mobile)
};
</script>

<style scoped>
/* Animasi Sidebar */
.fixed {
    transition: transform 0.3s ease-in-out;
}

/* Animasi Dropdown */
.dropdown-enter-active, .dropdown-leave-active {
    transition: opacity 0.3s ease-in-out;
}
.dropdown-enter, .dropdown-leave-to {
    opacity: 0;
}
</style>
