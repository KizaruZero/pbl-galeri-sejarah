<template>
  <nav
    class="bg-black text-white mb-4 py-3 px-4 md:px-6 shadow-md fixed top-0 left-0 w-full z-50 font-poppins"
  >
    <div class="max-w-screen-xl mx-auto flex justify-between items-center w-full">
      <!-- Mobile: Menu & Logo -->
      <div class="flex lg:hidden w-full items-center justify-between">
        <button @click="toggleMenu" class="text-white focus:outline-none">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16m-7 6h-8"
            />
          </svg>
        </button>
        <div class="w-10 h-10 flex items-center justify-center">
          <img
            v-if="!isLoading && companyProfile?.logo_url"
            :src="companyProfile.logo_url"
            alt="Logo"
            class="w-10 h-auto object-contain"
          />
          <div
            v-else
            class="w-6 h-6 rounded-full border-2 border-white border-t-transparent animate-spin"
          ></div>
        </div>
      </div>

      <!-- Desktop: Menu Kiri -->
      <div
        class="hidden lg:flex flex-1 justify-start gap-20 xl:gap-40 text-sm tracking-wider uppercase font-medium"
      >
        <Link href="/" class="relative group py-1"
          >Home
          <span
            class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
          ></span>
        </Link>
        <Link href="/events" class="relative group py-1"
          >Events
          <span
            class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
          ></span>
        </Link>
        <Link href="/gallery" class="relative group py-1"
          >Gallery
          <span
            class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
          ></span>
        </Link>
      </div>

      <!-- Desktop: Logo Tengah -->
      <div
        class="hidden lg:block mx-6 w-[50px] h-[60px] flex items-center justify-center"
      >
        <img
          v-if="!isLoading && companyProfile?.logo_url"
          :src="companyProfile.logo_url"
          alt="Logo"
          class="w-[50px] h-auto object-contain"
        />
        <div
          v-else
          class="mt-6 w-6 h-6 rounded-full border-2 border-white border-t-transparent animate-spin"
        ></div>
      </div>

      <!-- Desktop: Menu Kanan -->
      <div
        class="hidden lg:flex flex-1 justify-end gap-20 xl:gap-40 text-sm tracking-wider uppercase font-medium items-center"
      >
        <Link href="/article" class="relative group py-1"
          >Article
          <span
            class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
          ></span>
        </Link>
        <!-- Conditional rendering for Member/Contact link -->
        <Link :href="user ? '/contact' : '/member'" class="relative group py-1">
          {{ user ? "Contact" : "Member" }}
          <span
            class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
          ></span>
        </Link>

        <!-- Cek apakah user login -->
        <div v-if="user" class="relative group" ref="dropdownRef">
          <div class="div flex items-center gap-2">
            <div class="items-center">
              <NotificationList />
            </div>
            <img
              :src="userAvatar || `https://ui-avatars.com/api/?name=${user.name}`"
              :alt="user.name"
              class="w-14 h-14 rounded-full object-cover cursor-pointer"
              @click="dropdownOpen = !dropdownOpen"
            />
          </div>

          <div
            v-show="dropdownOpen"
            class="absolute right-0 mt-2 w-40 bg-white text-black rounded-lg shadow-lg py-2 z-50"
          >
            <template v-if="roles.includes('super_admin')">
              <Link href="/profile-page" class="block px-4 py-2 hover:bg-gray-100"
                >Profile</Link
              >
              <Link @click="goToDashboard" class="block px-4 py-2 hover:bg-gray-100"
                >Dashboard</Link
              >
            </template>
            <template v-else>
              <Link href="/profile-page" class="block px-4 py-2 hover:bg-gray-100"
                >Profile</Link
              >
            </template>
            <Link
              href="/logout"
              method="post"
              as="button"
              class="block px-4 py-2 hover:bg-gray-100 text-left w-full"
              >Logout</Link
            >
          </div>
        </div>

        <Link v-else href="/login" class="relative group py-1"
          >Login
          <span
            class="absolute left-0 bottom-0 w-full h-[2px] bg-white scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
          ></span>
        </Link>
      </div>
    </div>
  </nav>

  <!-- Add this spacer div after the nav element -->
  <div class="h-[60px] md:h-[70px]"></div>

  <!-- Sidebar (Mobile) -->
  <transition name="slide">
    <div v-if="menuOpen" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex">
      <div class="w-56 bg-black h-full p-6 flex flex-col gap-6">
        <!-- Close Button -->
        <button @click="toggleMenu" class="text-white self-end text-2xl">✕</button>

        <!-- User Profile Section (if logged in) -->
        <div v-if="user" class="flex flex-col items-center gap-4 mb-4">
          <img
            :src="userAvatar || `https://ui-avatars.com/api/?name=${user.name}`"
            :alt="user.name"
            class="w-20 h-20 rounded-full object-cover"
          />
          <span class="text-white text-lg">{{ user.name }}</span>
        </div>

        <!-- Navigation Links -->
        <Link href="/" class="text-white text-lg py-1" @click="toggleMenu">Home</Link>
        <Link href="/events" class="text-white text-lg py-1" @click="toggleMenu"
          >Events</Link
        >
        <Link href="/gallery" class="text-white text-lg py-1" @click="toggleMenu"
          >Gallery</Link
        >
        <Link href="/article" class="text-white text-lg py-1" @click="toggleMenu"
          >Article</Link
        >
        <Link
          :href="user ? '/contact' : '/member'"
          class="text-white text-lg py-1"
          @click="toggleMenu"
          >{{ user ? "Contact" : "Member" }}</Link
        >

        <!-- Conditional Login/User Actions -->
        <template v-if="user">
          <template v-if="roles.includes('super_admin')">
            <Link @click="goToDashboard" class="text-white text-lg py-1">Dashboard</Link>
          </template>
          <template v-else-if="roles.includes('member')">
            <Link href="/profile-page" class="text-white text-lg py-1" @click="toggleMenu"
              >Profile</Link
            >
          </template>
          <Link
            href="/logout"
            method="post"
            as="button"
            class="text-white text-lg py-1 text-left w-full"
            @click="toggleMenu"
            >Logout</Link
          >
        </template>
        <template v-else>
          <Link href="/login" class="text-white text-lg py-1" @click="toggleMenu"
            >Login</Link
          >
        </template>
      </div>
      <div class="flex-1" @click="toggleMenu"></div>
    </div>
  </transition>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import axios from "axios";
import NotificationList from "../Components/NotificationList.vue";

const dropdownRef = ref(null);
const menuOpen = ref(false);
const dropdownOpen = ref(false);
const companyProfile = ref(null);
const userAvatar = ref(null);
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false;
  }
};
const isLoading = ref(true);

const goToDashboard = () => {
  window.location.replace("/dashboard");
};

const roles = computed(() => {
  const user = usePage().props.auth.user;
  if (!user) return [];

  // Check if roles is an array
  if (Array.isArray(user.roles)) {
    return user.roles;
  }

  return [];
});

// Fungsi untuk mengambil data company profile
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

const getMediaUrl = (url) => {
  if (!url) return `https://ui-avatars.com/api/?name=${user.value?.name || "User"}`;
  if (url.startsWith("http")) return url;

  // Simplified path cleaning
  const cleanPath = url
    .replace(/^\//, "") // Remove leading slash
    .replace(/^(storage|public)\//, ""); // Remove storage/public prefix

  return cleanPath
    ? `/storage/${cleanPath}`
    : `https://ui-avatars.com/api/?name=${user.value?.name || "User"}`;
};

const fetchUserProfile = async () => {
  try {
    if (!user.value?.id) return;

    const { data: userData } = await axios.get(`/api/users/${user.value.id}`);

    if (userData && userData.profile_photo_url) {
      // Update userAvatar with processed URL
      userAvatar.value = getMediaUrl(userData.profile_photo_url);
      // Update user data
      user.value = {
        ...user.value,
        ...userData,
        avatar: userAvatar.value,
      };
    }
  } catch (error) {
    console.error("Error fetching user profile:", error);
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
  fetchCompanyProfile();
  // Always fetch user profile if user exists
  if (user.value?.id) {
    fetchUserProfile();
  }
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};

// Ambil data user dari Inertia share props
const page = usePage();
const user = computed(() => page.props.auth?.user || null);
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from {
  transform: translateX(-100%);
}

.slide-enter-to {
  transform: translateX(0);
}

.slide-leave-from {
  transform: translateX(0);
}

.slide-leave-to {
  transform: translateX(-100%);
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: opacity 0.2s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
}
</style>
