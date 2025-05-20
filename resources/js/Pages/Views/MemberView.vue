<template>
  <MainLayout>
    <HeroSection />
    <div class="min-h-screen bg-black text-white">
      <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto">
          <MemberFeature :isAuthenticated="isAuthenticated" />
          <MemberForm v-if="!isAuthenticated" :isAuthenticated="isAuthenticated" />
          <ContactInformation />
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import HeroSection from "@/Components-landing/MemberPage/HeroSection.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import MemberFeature from "@/Components-landing/MemberPage/MemberFeature.vue";
import MemberForm from "@/Components-landing/MemberPage/MemberForm.vue";
import ContactInformation from "@/Components-landing/MemberPage/ContactInformation.vue";

const isAuthenticated = computed(() => {
  return usePage().props.auth.user !== null;
});
</script>
