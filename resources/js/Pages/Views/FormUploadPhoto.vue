<template>
    <MainLayout>
        <div class="mx-auto p-6 bg-[#0d0d0d] max-w-full">
            <button @click="visitBacktoProfile"
                class="mb-6 mt-14 flex items-center text-gray-400 hover:text-blue-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Profile
            </button>
            <h2 class="text-2xl font-bold text-center text-white mt-10 mb-8">CREATE CONTENT PHOTO</h2>
            <!-- Changed title -->

            <div class="space-y-6">
                <!-- Two Column Layout for most fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Title Field -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-white mb-2">Title*</label>
                            <input type="text" id="title" v-model="form.title" required
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter content title">
                        </div>

                        <!-- Image Upload Field -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Image</label>
                            <!-- Changed label -->
                            <div class="flex items-center gap-3">
                                <label for="media" class="cursor-pointer">
                                    <div
                                        class="px-4 py-2 bg-gray-500 text-white rounded-lg border border-[#333333] hover:bg-[#252525] transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span>Choose Image</span> <!-- Changed button text -->
                                    </div>
                                    <input type="file" id="media" @change="handleFileUpload" accept="image/*"
                                        class="hidden">
                                </label>
                                <span v-if="fileName" class="text-sm text-white truncate max-w-xs">{{ fileName }}</span>
                            </div>

                            <!-- Preview Section - Removed video part -->
                            <div v-if="filePreview" class="mt-3">
                                <div class="max-w-xs">
                                    <img :src="filePreview" alt="Preview"
                                        class="rounded-lg border border-[#333333] max-h-40 object-contain">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="slug" class="block text-sm font-medium text-white mb-2">Slug</label>
                            <input type="text" id="slug" v-model="form.slug"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Skig">
                        </div>

                        <!-- Tag Field -->
                        <div>
                            <label for="tag" class="block text-sm font-medium text-white mb-2">Tag</label>
                            <input type="text" id="tag" v-model="form.tag"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Skig">
                        </div>
                    </div>
                </div>
                <!-- Description Field (full width) -->
                <div>
                    <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                    <textarea id="description" v-model="form.description"
                        class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-h-[100px]"
                        placeholder="Enter content description"></textarea>
                </div>
                <!-- Two Column Layout for most fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">

                        <!-- Source Field -->
                        <div>
                            <label for="source" class="block text-sm font-medium text-white mb-2">Source*</label>
                            <input type="text" id="source" v-model="form.source" required
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter content source">
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Alt Text Field -->
                        <div>
                            <label for="alt-text" class="block text-sm font-medium text-white mb-2">Alt Text</label>
                            <input type="text" id="alt-text" v-model="form.altText"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter alt text for accessibility">
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" @click="resetForm"
                        class="px-6 py-2 bg-transparent border border-[#333333] text-white rounded-lg hover:bg-[#252525] transition font-medium">
                        Cancel
                    </button>
                    <button type="button" @click="submitForm"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                        Create
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
    import {
        ref,
        computed
    } from 'vue';
    import {
        router
    } from '@inertiajs/vue3'
    import MainLayout from '@/Layouts/MainLayout.vue';

    const form = ref({
        title: '',
        slug: '',
        media: null,
        description: '',
        source: '',
        tag: '',
        altText: ''
    });

    const visitBacktoProfile = () => {
        showModal.value = false
        router.visit('/profile-page')
    }

    const fileName = ref('');
    const filePreview = ref('');

    const isImage = computed(() => {
        return form.value.media ?.type ?.startsWith('image/');
    });

    const handleFileUpload = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        // Check if the file is an image
        if (!file.type.startsWith('image/')) {
            alert('Please upload an image file only.');
            return;
        }

        form.value.media = file;
        fileName.value = file.name;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            filePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    };

    const submitForm = () => {
        console.log('Form submitted:', form.value);
        // Add your form submission logic here
    };

    const resetForm = () => {
        form.value = {
            title: '',
            media: null,
            description: '',
            source: '',
            tag: '',
            altText: ''
        };
        fileName.value = '';
        filePreview.value = '';
    };

</script>
