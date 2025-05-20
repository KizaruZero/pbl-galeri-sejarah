<template>
  <MainLayout>
    <HeroSection />
    <div class="min-h-screen bg-black text-white">
      <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto">
          <MemberFeature />
          <!-- Tampilkan form hanya jika user belum login -->
          <MemberForm 
            v-if="!isAuthenticated"
            :isAuthenticated="isAuthenticated"
            @submit="handleSubmit"
            @reset="handleReset"
          />
          <ContactInformation />
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import HeroSection from "@/Components-landing/MemberPage/HeroSection.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import MemberFeature from "@/Components-landing/MemberPage/MemberFeature.vue";
import MemberForm from "@/Components-landing/MemberPage/MemberForm.vue";
import ContactInformation from "@/Components-landing/MemberPage/ContactInformation.vue";

// Menggunakan computed property untuk mengecek status auth
const isAuthenticated = computed(() => {
    return usePage().props.auth.user !== null;
});

const handleSubmit = (formData) => {
    console.log("Form data received:", formData);
    // Lakukan API call ke backend
};

const handleReset = () => {
    console.log("Form reset");
};
</script>
