<template>
  <div class="relative flex flex-col items-center w-full bg-black py-10">
    <div class="relative flex flex-col items-center w-full max-w-[1300px] rounded-lg shadow-lg px-5 py-10">
      <!-- Search Form -->
      <form @submit.prevent="handleSearch" class="w-full mt-8">
        <div class="flex w-full">
          <!-- Mobile Sidebar Toggle Button -->
          <button
            @click.prevent="toggleSidebar"
            type="button"
            class="md:hidden px-4 py-3 bg-black text-white border border-white rounded-l-lg hover:bg-gray-800 transition-colors"
          >
            ☰
          </button>

          <!-- Category Dropdown (Desktop) -->
          <div class="hidden md:block relative">
            <button
              @click.prevent="toggleDropdown"
              type="button"
              class="z-10 inline-flex items-center px-5 py-3 text-base font-medium text-white bg-black border border-white rounded-l-lg hover:text-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-500 transition-colors"
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

            <!-- Dropdown Menu -->
            <div
              v-if="showDropdown"
              class="absolute top-full left-0 w-48 bg-black text-white rounded-lg shadow-lg mt-2 border border-gray-700 z-50"
            >
              <ul class="py-2 text-sm">
                <li v-for="category in categories" :key="category">
                  <button
                    @click.prevent="selectCategory(category)"
                    type="button"
                    class="block w-full px-4 py-3 text-left hover:bg-gray-800 transition-colors"
                  >
                    {{ category }}
                  </button>
                </li>
              </ul>
            </div>
          </div>

          <!-- Search Input -->
          <div class="flex-grow relative">
            <input
              type="search"
              v-model="searchQuery"
              class="w-full p-3 text-base text-white bg-black border border-white md:border-l-0 md:rounded-none rounded-r-lg focus:ring-gray-500 focus:border-gray-500"
              placeholder="Search Mockups, Logos, Design Templates..."
              required
            />
            <button
              type="submit"
              class="absolute top-0 right-0 h-full px-4 text-black bg-white border border-white rounded-r-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-500 transition-colors"
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

      <!-- Search Results -->
      <div v-if="searchPerformed" class="w-full mt-8">
        <div v-if="loading" class="text-center py-10">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-white"></div>
          <p class="mt-2 text-white">Searching...</p>
        </div>
        
        <div v-else>
          <div v-if="searchResults.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in searchResults" :key="`${item.type}-${item.id}`" class="bg-gray-900 rounded-lg overflow-hidden shadow-lg">
              <a :href="getDetailPage(item)" class="block">
                <img :src="item.image_url" :alt="item.title" class="w-full h-48 object-cover">
                <div class="p-4">
                  <h3 class="text-white font-semibold text-lg mb-2">{{ item.title }}</h3>
                  <p class="text-gray-300 text-sm mb-3">{{ truncateDescription(item.description) }}</p>
                  <div class="flex justify-between items-center text-sm text-gray-400">
                    <span>{{ item.category }}</span>
                    <span>{{ item.type }}</span>
                  </div>
                </div>
              </a>
            </div>
          </div>
          
          <div v-else class="text-center py-10 text-white">
            <p class="text-xl">No results found for "{{ searchQuery }}"</p>
            <p v-if="selectedCategory !== 'All categories'">in category "{{ selectedCategory }}"</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Sidebar -->
    <div 
      v-if="showSidebar" 
      class="fixed inset-0 bg-black bg-opacity-80 z-50 flex flex-col justify-center items-center text-white transition-opacity duration-300"
    >
      <div class="relative w-full max-w-md bg-black p-6 rounded-lg shadow-lg border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-4">
          <span class="text-lg font-semibold">Select Category</span>
          <button 
            @click="toggleSidebar" 
            class="text-gray-300 hover:text-white text-2xl transition-colors"
          >
            &times;
          </button>
        </div>
        <ul class="mt-5 space-y-3 text-lg">
          <li v-for="category in categories" :key="category">
            <button 
              @click="selectCategory(category)" 
              type="button"
              class="block w-full px-6 py-4 text-left bg-gray-900 hover:bg-gray-700 rounded-lg transition-colors"
            >
              {{ category }}
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from 'axios';

// UI State
const showDropdown = ref(false);
const showSidebar = ref(false);
const selectedCategory = ref("All categories");
const searchQuery = ref("");
const searchPerformed = ref(false);
const loading = ref(false);

// Data
const categories = ref(["All categories"]);
const searchResults = ref([]);

// Fetch categories on mount
onMounted(async () => {
  try {
    const response = await axios.get('/api/search');
    categories.value = ["All categories", ...response.data.categories];
  } catch (error) {
    console.error("Failed to load categories:", error);
  }
});


const getDetailPage = (item) => {
    if (item.type === 'photo') {
        window.location.href = `/gallery-photo/${item.slug}`;
    } else if (item.type === 'video') {
        window.location.href = `/gallery-video/${item.slug}`;
    }
};

// Truncate description for display
const truncateDescription = (desc) => {
  if (!desc) return '';
  return desc.length > 100 ? desc.substring(0, 100) + '...' : desc;
};

// Perform the actual search
const performSearch = async () => {
  if (!searchQuery.value.trim() && selectedCategory.value === "All categories") {
    searchResults.value = [];
    searchPerformed.value = false;
    return;
  }

  searchPerformed.value = true;
  loading.value = true;

  try {
    const params = {
      ...(searchQuery.value.trim() && { query: searchQuery.value.trim() }),
      ...(selectedCategory.value !== 'All categories' && { category: selectedCategory.value })
    };

    const response = await axios.get('/api/search', { params });
    searchResults.value = response.data.items || [];
  } catch (error) {
    console.error("Search failed:", error);
    searchResults.value = [];
  } finally {
    loading.value = false;
  }
};

// Handle search submission
const handleSearch = () => {
  performSearch();
};

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value;
};

const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value;
};

const selectCategory = (category) => {
  selectedCategory.value = category;
  showDropdown.value = false;
  showSidebar.value = false;
  handleSearch();
};
</script>