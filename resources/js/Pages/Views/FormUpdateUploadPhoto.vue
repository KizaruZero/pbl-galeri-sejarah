<template>
    <MainLayout>
        <div class="mx-auto p-6 bg-[#0d0d0d] max-w-full">
            <button @click="visitBacktoProfile"
                class="mb-6 mt-4 flex items-center text-gray-400 hover:text-blue-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Profile
            </button>
            <h2 class="text-2xl font-bold text-center text-white mt-10 mb-8">
                UPDATE CONTENT PHOTO
            </h2>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-white mb-2">Title*</label>
                            <input type="text" id="title" v-model="form.title" required
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter content title" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Image</label>
                            <div
                                class="w-full aspect-photo border-2 border-dashed border-gray-500 rounded-lg overflow-hidden hover:bg-[#1f1f1f] transition cursor-pointer relative">
                                <div v-if="filePreview" class="h-full">
                                    <img :src="filePreview" alt="Preview"
                                        class="w-full h-full object-contain bg-[#1a1a1a]" />
                                    <button @click="removeImage"
                                        class="absolute top-2 right-2 p-1 hover:bg-black hover:text-white rounded-full text-black shadow-lg transition-colors z-10">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div v-else class="h-full flex flex-col items-center justify-center p-6"
                                    @click="$refs.fileInput.click()">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path d="M14 22l6 6 14-14" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <rect width="40" height="40" x="4" y="4" rx="2" ry="2" stroke-width="2" />
                                    </svg>
                                    <p class="text-sm text-gray-400">
                                        <span class="text-blue-400 underline">Upload a file</span> or drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                                <input type="file" ref="fileInput" class="hidden" accept="image/*"
                                    @change="handleFileUpload" />
                            </div>
                        </div>

                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="tag" class="block text-sm font-medium text-white mb-2">Tag</label>
                            <input type="text" id="tag" v-model="form.tag"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tag" />
                        </div>
                        <!-- Category Field -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-white mb-2">Category*</label>
                            <select
    id="category"
    v-model="form.category_id"
    required
    class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
>
    <option value="" disabled>Select a category</option>
    <option
        v-for="category in categories"
        :key="category.id"
        :value="Number(category.category_name)"
    >
        {{ category.category_name }}
    </option>
</select>
                        </div>
                    </div>

                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                    <textarea id="description" v-model="form.description"
                        class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-h-[100px]"
                        placeholder="Enter content description"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label for="source" class="block text-sm font-medium text-white mb-2">Source*</label>
                            <input type="text" id="source" v-model="form.source" required
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter content source" />
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="alt-text" class="block text-sm font-medium text-white mb-2">Alt Text</label>
                            <input type="text" id="alt-text" v-model="form.altText"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter alt text for accessibility" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" @click="resetForm"
                        class="px-6 py-2 bg-transparent border border-[#333333] text-white rounded-lg hover:bg-[#252525] transition font-medium">
                        Cancel
                    </button>
                    <button type="button" @click="submitForm"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
    import {
        ref,
        onMounted
    } from "vue";
    import MainLayout from "@/Layouts/MainLayout.vue";
    import axios from "axios";
    import Swal from "sweetalert2";

    const form = ref({
        title: "",
        media: null,
        description: "",
        source: "",
        tag: "",
        altText: "",
        category_id: "",
    });

    const categories = ref([]);
    const fileInput = ref(null);
    const filePreview = ref("");
    const photoId = window.location.pathname.split('/').pop();

    onMounted(async () => {
        try {
            // Load categories first
            const categoriesResponse = await axios.get('/api/categories');
            categories.value = categoriesResponse.data.data || [];
            categories.value.sort((a, b) => a.category_name.localeCompare(b.category_name));

            // Then load photo data
            const response = await axios.get(`/api/content-photo/${photoId}/edit`);
            const photo = response.data;
            console.log('Photo data received:', photo);

            // Initialize form with photo data
            form.value = {
                title: photo.title || '',
                description: photo.description || '',
                source: photo.source || '',
                tag: photo.tag || '',
                category_id: photo.category_id || '',
                altText: photo.alt_text || '',
                media: null
            };

            console.log('Form data after initialization:', form.value);

            if (photo.image_url) {
                filePreview.value = `/storage/${photo.image_url}`;
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            console.error('Error details:', error.response?.data);
            Swal.fire('Error', 'Failed to load data', 'error');
        }
    });

    const visitBacktoProfile = () => {
        window.location.href = "/profile-page";
    };

    const handleFileUpload = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        if (!file.type.startsWith("image/")) {
            Swal.fire('Error', 'Please upload an image file only', 'error');
            return;
        }

        form.value.media = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            filePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    };

    const removeImage = () => {
        form.value.media = null;
        filePreview.value = "";
        if (fileInput.value) {
            fileInput.value.value = "";
        }
    };

    const submitForm = async () => {
        try {
            const categoriesResponse = await axios.get('/api/categories');
            categories.value = categoriesResponse.data.data || [];
            categories.value.sort((a, b) => a.category_name.localeCompare(b.category_name));

            const formData = new FormData();
            formData.append("title", form.value.title);
            formData.append("description", form.value.description);
            formData.append("source", form.value.source);
            formData.append("alt_text", form.value.altText);
            formData.append("tag", form.value.tag);
            formData.append("category_id", form.value.category_id);
            formData.append("note", "");

            if (form.value.media) {
                formData.append("image", form.value.media);
            }

            const response = await axios.post(`/api/content-photo/${photoId}?_method=PUT`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Accept: 'application/json',
                }
            });

            if (response.status === 200) {
                await Swal.fire({
                    icon: "success",
                    title: "Photo updated successfully"
                });
                window.location.href = "/profile-page";
            }
        } catch (error) {
            console.error("Update error:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: error.response ?.data ?.message || "Failed to update photo"
            });
        }
    };

    const resetForm = () => {
        window.location.href = "/profile-page";
    };

</script>
