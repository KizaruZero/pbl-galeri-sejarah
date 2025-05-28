<template>
  <div class="flex flex-col mb-6 sm:mb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-8">
      <!-- Profile Info -->
      <div class="flex flex-col col-span-1 items-center md:items-start">
        <div class="relative w-28 h-28 sm:w-40 sm:h-40 md:w-48 md:h-48 mb-3 sm:mb-4">
          <img :src="userData?.photo_profile ? `/storage/${userData.photo_profile}` : defaultPhoto"
            alt="Profile Photo"
            class="w-full h-full object-cover rounded-full z-10 relative" />
          <div class="absolute top-0 left-0 w-full h-full border-4 border-white rounded-full blur-sm z-0" />
          <div class="absolute top-0 left-0 w-full h-full border border-white rounded-full z-10" />
        </div>

        <div class="text-white text-center md:text-left px-2 sm:px-0">
          <div class="flex items-center justify-center md:justify-start gap-2">
            <h1 class="text-lg sm:text-2xl md:text-3xl font-medium mb-1 sm:mb-2">{{ userData?.name }}</h1>
            <button @click="openModal"
              class="text-white hover:text-blue-400 transition duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 sm:w-6 sm:h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
              </svg>
            </button>
          </div>
          <p class="text-sm sm:text-lg md:text-xl mb-1">{{ userData?.role }}</p>
          <p class="text-sm sm:text-lg md:text-xl">{{ userData?.email }}</p>
        </div>
      </div>

      <!-- Stats -->
      <div class="flex col-span-2 justify-center md:justify-start items-center mt-4 sm:mt-0">
        <div class="flex flex-wrap justify-between w-full sm:justify-around gap-1 sm:gap-8 md:gap-12 lg:mb-20">
          <div class="flex flex-col items-center px-2 sm:px-0">
            <span class="text-white text-lg sm:text-2xl md:text-3xl font-semibold">{{ stats.posts }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">UNGGAHAN</span>
          </div>
          <div class="flex flex-col items-center px-2 sm:px-0">
            <span class="text-white text-lg sm:text-2xl md:text-3xl font-semibold">{{ stats.likes }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">LIKE</span>
          </div>
          <div class="flex flex-col items-center px-2 sm:px-0">
            <span class="text-white text-lg sm:text-2xl md:text-3xl font-semibold">{{ stats.favorites }}</span>
            <span class="text-white text-xs sm:text-sm md:text-base">FAVORITE</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Profile Update Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-96">
        <h2 class="text-xl font-bold mb-4">Update Profile</h2>

        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Profile Photo</label>
          <input type="file" @change="handlePhotoChange" accept="image/*" class="w-full">
          <img 
            :src="previewPhoto || (userData?.photo_profile ? `/storage/${userData.photo_profile}` : defaultPhoto)" 
            class="mt-2 w-32 h-32 object-cover rounded-full"
          >
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Name</label>
          <input type="text" v-model="formData.name" class="w-full border rounded p-2">
        </div>

        <div class="flex justify-end gap-2">
          <button @click="closeModal" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
            Cancel
          </button>
          <button @click="handleSubmit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import defaultPhoto from '@/Assets/default-photo.jpg';

const props = defineProps({
  userData: Object,
  stats: {
    type: Object,
    required: true,
    default: () => ({ posts: 0, likes: 0, favorites: 0 })
  }
});

const emit = defineEmits(['update-profile']);

const isModalOpen = ref(false);
const previewPhoto = ref(null);
const formData = ref({
  name: props.userData?.name || '',
  photo: null
});

const openModal = () => {
  isModalOpen.value = true;
  formData.value.name = props.userData?.name || '';
};

const closeModal = () => {
  isModalOpen.value = false;
  previewPhoto.value = null;
  formData.value.photo = null;
};

const handlePhotoChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    console.log('Selected file:', file);
    formData.value.photo = file;
    previewPhoto.value = URL.createObjectURL(file);
  }
};

const handleSubmit = async () => {
  try {
    const updateData = new FormData();
    updateData.append('name', formData.value.name);

    if (formData.value.photo) {
      console.log('Appending photo to FormData');
      updateData.append('photo', formData.value.photo);
    }

    const response = await axios.post('/profile/update', updateData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    console.log('Response:', response.data);
    emit('update-profile', response.data);
    closeModal();

    // Add page reload after successful update
    window.location.reload();

  } catch (error) {
    console.error('Error updating profile:', error);
  }
};
</script>
