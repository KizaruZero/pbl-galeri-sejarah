<template>
  <section
    class="relative w-full h-full flex justify-center items-center p-4 overflow-hidden"
  >
    <div v-if="isLoading" class="flex items-center justify-center">
      <div
        class="w-10 h-10 border-4 border-white border-t-transparent rounded-full animate-spin"
      ></div>
    </div>

    <template v-else>
      <div class="relative w-full h-full max-w-3xl mx-auto">
        <!-- First Image - Left Top -->
        <div
          class="absolute w-28 sm:w-32 md:w-40 lg:w-72 aspect-[3/4] left-0 top-10 lg:top-20 transform -rotate-6 hover:rotate-0 transition-all duration-500"
        >
          <img
            :src="companyProfile?.bg_home_urls?.[0]"
            alt="Gallery image 1"
            class="w-full h-full object-cover rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500"
          />
        </div>

        <!-- Second Image - Center -->
        <div
          class="absolute w-32 sm:w-40 md:w-48 lg:w-80 aspect-[3/4] left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20 hover:scale-105 transition-all duration-500"
        >
          <img
            :src="companyProfile?.bg_home_urls?.[1]"
            alt="Gallery image 2"
            class="w-full h-full object-cover rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500"
          />
        </div>

        <!-- Third Image - Right Bottom -->
        <div
          class="absolute w-28 sm:w-32 md:w-40 lg:w-72 aspect-[3/4] right-0 bottom-10 lg:bottom-20 transform rotate-6 hover:rotate-0 transition-all duration-500"
        >
          <img
            :src="companyProfile?.bg_home_urls?.[2]"
            alt="Gallery image 3"
            class="w-full h-full object-cover rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500"
          />
        </div>
      </div>
    </template>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const companyProfile = ref(null);
const isLoading = ref(true);

const fetchCompanyProfile = async () => {
  try {
    const response = await axios.get("/api/company-profile");
    const data = response.data.data;

    data.bg_home_urls = [
      `/storage/${data.bg_home_1}`,
      `/storage/${data.bg_home_2}`,
      `/storage/${data.bg_home_3}`,
    ];

    companyProfile.value = data;
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

<style scoped>
img {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  backface-visibility: hidden;
}

img:hover {
  filter: brightness(1.1);
}
</style>
