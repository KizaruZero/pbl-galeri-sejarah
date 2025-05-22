<template>
  <div class="my-20">
    <div class="border-t border-gray-700 my-10"></div>
    <h2 class="text-2xl md:text-3xl font-bold mb-6">CONTACT THE MEMBERSHIP OFFICE</h2>

    <div v-if="!isLoading" class="space-y-4">
      <div class="flex items-start">
        <span class="font-bold w-32">Email :</span>
        <a :href="'mailto:' + companyProfile?.cms_email" class="uppercase">
          {{ companyProfile?.cms_email }}
        </a>
      </div>
      <div class="flex items-start">
        <span class="font-bold w-32">Phone :</span>
        <span class="uppercase">{{ companyProfile?.cms_phone }}</span>
      </div>
      <div class="flex items-start">
        <span class="font-bold w-32">Location :</span>
        <span class="uppercase">{{ companyProfile?.cms_address }}</span>
      </div>
    </div>

    <div v-else class="space-y-4">
      <div class="animate-pulse bg-gray-700 h-6 w-3/4"></div>
      <div class="animate-pulse bg-gray-700 h-6 w-2/4"></div>
      <div class="animate-pulse bg-gray-700 h-6 w-3/4"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const companyProfile = ref(null);
const isLoading = ref(true);

const fetchCompanyProfile = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/company-profile');
    companyProfile.value = response.data.data;
  } catch (error) {
    console.error('Error fetching company profile:', error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchCompanyProfile();
});
</script>
