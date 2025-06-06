<template>
  <footer class="bg-white dark:bg-black text-black dark:text-white py-10 px-5 md:px-20">
    <div class="flex flex-col md:grid md:grid-cols-3 gap-10">
      <!-- Bagian Kiri: Logo -->
      <div
        class="w-full flex flex-col gap-3 items-center md:items-start text-center md:text-left"
      >
        <div class="flex items-center gap-4">
          <img
            v-if="!isLoading && companyProfile?.logo_url"
            :src="companyProfile.logo_url"
            class="object-contain w-14 md:w-16"
            alt="Company logo"
          />
          <div v-else class="w-14 md:w-16 h-14 md:h-16 flex items-center justify-center">
            <div
              class="w-6 h-6 rounded-full border-2 border-black border-t-transparent animate-spin"
            ></div>
          </div>
          <div class="flex flex-col leading-tight">
            <h2 v-if="!isLoading && companyProfile?.cms_name" class="text-xl md:text-2xl uppercase font-semibold">
              {{ companyProfile.cms_name }}
            </h2>
            <h2 v-else class="text-xl md:text-2xl uppercase font-semibold">Loading...</h2>
          </div>
        </div>
      </div>

      <!-- Bagian Tengah: Navigasi -->
      <div class="w-full flex justify-center">
        <nav
          class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm md:text-base uppercase text-center"
        >
          <Link href="/" class="hover:underline">Home</Link>
          <Link href="/events" class="hover:underline">Events</Link>
          <Link href="/gallery" class="hover:underline">Gallery</Link>
          <Link href="/article" class="hover:underline">Article</Link>
          <!-- Conditional rendering for Member/Contact link -->
          <Link
            :href="user ? '/contact' : '/member'"
            class="hover:underline"
          >{{ user ? 'Contact' : 'Member' }}</Link>
          <Link v-if="!user" href="/login" class="hover:underline">Login</Link>
        </nav>
      </div>

      <!-- Bagian Kanan: Kontak -->
      <div
        class="w-full flex flex-col gap-3 items-center md:items-end text-center md:text-right text-sm md:text-base font-light"
      >
        <div class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
  </svg>
  <a :href="'mailto:' + companyProfile?.cms_email" class="break-words">
    {{ companyProfile?.cms_email || 'Loading...' }}
  </a>
</div>
<div class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
  </svg>
  <p class="break-words">{{ companyProfile?.cms_phone || 'Loading...' }}</p>
</div>
<div class="flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
  </svg>
  <p class="break-words">{{ companyProfile?.cms_address || 'Loading...' }}</p>
</div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import axios from "axios";

const companyProfile = ref(null);
const isLoading = ref(true);

const isDarkTheme = ref(true);
const toggleTheme = () => {
  isDarkTheme.value = !isDarkTheme.value;
};

// Get user data from Inertia page props
const user = computed(() => usePage().props.auth?.user || null);

// Fetch company profile
const fetchCompanyProfile = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get("/api/company-profile");
    companyProfile.value = response.data.data;
  } catch (error) {
    console.error("Error fetching company profile:", error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchCompanyProfile();
});
</script>
