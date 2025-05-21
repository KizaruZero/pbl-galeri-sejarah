<template>
  <footer class="bg-black text-white py-10 px-5 md:px-20">
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
              class="w-6 h-6 rounded-full border-2 border-white border-t-transparent animate-spin"
            ></div>
          </div>
          <div class="flex flex-col leading-tight">
            <h2 class="text-xl md:text-2xl uppercase font-semibold">Kasunanan</h2>
            <h2 class="text-xl md:text-2xl uppercase font-semibold">Surakarta</h2>
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
          <img src="@assets/Logo/Vector.png" class="w-5" alt="Email icon" />
          <a href="mailto:work@uxmcreativestudio.com" class="break-words"
            >work@uxmcreativestudio.com</a
          >
        </div>
        <div class="flex items-center gap-2">
          <img src="@assets/Logo/Vector2.png" class="w-5" alt="Location icon" />
          <p class="break-words">New York, USA</p>
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
