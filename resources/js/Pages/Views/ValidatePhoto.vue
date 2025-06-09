<template>
    <MainLayout>
        <div class="mx-auto p-6 bg-[#0d0d0d] max-w-full">
            <button
                @click="goBack"
                class="mb-6 flex items-center text-gray-400 hover:text-blue-300 transition-colors"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
                Back to Edit
            </button>

            <h2 class="text-2xl font-bold text-center text-white mt-10 mb-8">
                VALIDATE PHOTO CONTENT
            </h2>

            <div v-if="photoData" class="space-y-6">
                <!-- Preview Section -->
                <div class="bg-[#1a1a1a] rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Content Preview</h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Image Preview -->
                        <div class="space-y-4">
                            <div class="aspect-video bg-black rounded-lg overflow-hidden">
                                <img
                                    v-if="photoData.imagePreview"
                                    :src="photoData.imagePreview"
                                    :alt="photoData.title"
                                    class="w-full h-full object-contain"
                                />
                            </div>
                            <p v-if="photoData.fileName" class="text-sm text-gray-400">
                                File: {{ photoData.fileName }}
                            </p>
                        </div>

                        <!-- Content Details -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Title</label>
                                <p class="text-white">{{ photoData.title }}</p>
                            </div>

                            <div v-if="photoData.description">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                                <p class="text-white">{{ photoData.description }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Source</label>
                                <p class="text-white">{{ photoData.source }}</p>
                            </div>

                            <div v-if="photoData.tag">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Tags</label>
                                <p class="text-white">{{ photoData.tag }}</p>
                            </div>

                            <div v-if="photoData.altText">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Alt Text</label>
                                <p class="text-white">{{ photoData.altText }}</p>
                            </div>

                            <div v-if="photoData.categories && photoData.categories.length > 0">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Categories</label>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="category in photoData.categories"
                                        :key="category.id"
                                        class="bg-blue-600 text-white px-2 py-1 rounded text-sm"
                                    >
                                        {{ category.category_name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6">
                    <button
                        type="button"
                        @click="cancelSubmission"
                        class="px-6 py-2 bg-transparent border border-[#333333] text-white rounded-lg hover:bg-[#252525] transition font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="confirmSubmission"
                        :disabled="loading"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm disabled:opacity-50"
                    >
                        {{ loading ? 'Uploading...' : 'Confirm & Upload' }}
                    </button>
                </div>
            </div>

            <div v-else class="text-center text-white">
                <p>No photo data found. Please go back and fill the form.</p>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const photoData = ref(null);
const loading = ref(false);

onMounted(() => {
    // Get data from sessionStorage
    const storedData = sessionStorage.getItem('photoValidationData');
    if (storedData) {
        photoData.value = JSON.parse(storedData);
    } else {
        // If no data, redirect back to form
        router.visit('/upload-photo');
    }
});

const goBack = () => {
    router.visit('/upload-photo');
};

const cancelSubmission = () => {
    // Clear stored data and go back to form
    sessionStorage.removeItem('photoValidationData');
    if (window.photoValidationFile) {
        delete window.photoValidationFile;
    }
    router.visit('/upload-photo');
};

const confirmSubmission = async () => {
    if (!photoData.value || !window.photoValidationFile) return;

    try {
        loading.value = true;

        // Create FormData from stored data
        const formData = new FormData();
        formData.append('title', photoData.value.title);
        formData.append('description', photoData.value.description || '');
        formData.append('image', window.photoValidationFile);
        formData.append('source', photoData.value.source || '');
        formData.append('alt_text', photoData.value.altText || '');
        formData.append('tag', photoData.value.tag || '');

        // Append category IDs
        photoData.value.category_ids.forEach((categoryId, index) => {
            formData.append(`category_ids[${index}]`, categoryId);
        });

        // Send POST request to upload photo
        const response = await axios.post('/api/content-photo', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                Accept: 'application/json',
            },
        });

        if (response.status === 201) {
            // Clear stored data
            sessionStorage.removeItem('photoValidationData');
            if (window.photoValidationFile) {
                delete window.photoValidationFile;
            }

            Swal.fire({
                icon: 'success',
                title: 'Photo uploaded successfully',
            }).then(() => {
                router.visit('/profile-page');
            });
        }
    } catch (error) {
        console.error('Upload error:', error);

        let errorMessage = 'Failed to upload photo. Please try again.';
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        } else if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            errorMessage = Object.values(errors).flat().join('\n');
        }

        Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: errorMessage,
        });
    } finally {
        loading.value = false;
    }
};
</script>
