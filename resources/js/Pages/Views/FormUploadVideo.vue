<template>
    <MainLayout>
        <div class="mx-auto p-6 bg-[#0d0d0d] max-w-full">
            <button
                @click="visitBacktoProfile"
                class="mb-6 mt-14 flex items-center text-gray-400 hover:text-blue-300 transition-colors"
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
                Back to Profile
            </button>
            <h2 class="text-2xl font-bold text-center text-white mt-10 mb-8">
                CREATE CONTENT VIDEO
            </h2>

            <div class="space-y-6">
                <!-- Two Column Layout for most fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Title Field -->
                        <div>
                            <label
                                for="title"
                                class="block text-sm font-medium text-white mb-2"
                                >Title*</label
                            >
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                required
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter content title"
                            />
                        </div>

                        <!-- Video Upload Field -->
                        <div>
                            <label
                                class="block text-sm font-medium text-white mb-2"
                                >Video*</label
                            >
                            <div class="flex items-center gap-3">
                                <label for="video" class="cursor-pointer">
                                    <div
                                        class="px-4 py-2 bg-gray-500 text-white rounded-lg border border-[#333333] hover:bg-[#252525] transition flex items-center gap-2"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                            />
                                        </svg>
                                        <span>Choose Video</span>
                                    </div>
                                    <input
                                        type="file"
                                        id="video"
                                        ref="fileInput"
                                        @change="handleVideoUpload"
                                        accept="video/*"
                                        class="hidden"
                                    />
                                </label>
                                <span
                                    v-if="videoName"
                                    class="text-sm text-white truncate max-w-xs"
                                    >{{ videoName }}</span
                                >
                            </div>

                            <!-- Video Preview Section -->
                            <div v-if="videoPreview" class="mt-3">
                                <video
                                    controls
                                    class="rounded-lg border border-[#333333] w-full max-h-60"
                                >
                                    <source
                                        :src="videoPreview"
                                        :type="form.video.type"
                                    />
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>

                        <!-- Tag Field -->
                        <div>
                            <label
                                for="tag"
                                class="block text-sm font-medium text-white mb-2"
                                >Tag</label
                            >
                            <input
                                type="text"
                                id="tag"
                                v-model="form.tag"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Skig"
                            />
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Thumbnail Upload Field (full width) -->
                        <div>
                            <label
                                class="block text-sm font-medium text-white mb-2"
                                >Thumbnail*</label
                            >
                            <div class="flex items-center gap-3">
                                <label for="thumbnail" class="cursor-pointer">
                                    <div
                                        class="px-4 py-2 bg-gray-500 text-white rounded-lg border border-[#333333] hover:bg-[#252525] transition flex items-center gap-2"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                            />
                                        </svg>
                                        <span>Choose Thumbnail</span>
                                    </div>
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        id="thumbnail"
                                        @change="handleThumbnailUpload"
                                        accept="image/*"
                                        class="hidden"
                                    />
                                </label>
                                <span
                                    v-if="thumbnailName"
                                    class="text-sm text-white truncate max-w-xs"
                                    >{{ thumbnailName }}</span
                                >
                            </div>

                            <!-- Thumbnail Preview Section -->
                            <div v-if="thumbnailPreview" class="mt-3 max-w-xs">
                                <img
                                    :src="thumbnailPreview"
                                    alt="Thumbnail Preview"
                                    class="rounded-lg border border-[#333333] max-h-40 object-contain"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Field (full width) -->
                <div>
                    <label
                        for="description"
                        class="block text-sm font-medium text-white mb-2"
                        >Description</label
                    >
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-h-[100px]"
                        placeholder="Enter content description"
                    ></textarea>
                </div>

                <!-- Two Column Layout for most fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Source Field -->
                        <div>
                            <label
                                for="source"
                                class="block text-sm font-medium text-white mb-2"
                                >Source*</label
                            >
                            <input
                                type="text"
                                id="source"
                                v-model="form.source"
                                required
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter content source"
                            />
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Link Youtube Field -->
                        <div>
                            <label
                                for="link_youtube"
                                class="block text-sm font-medium text-white mb-2"
                                >Link Youtube</label
                            >
                            <input
                                type="text"
                                id="link-youtube"
                                v-model="form.link_youtube"
                                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-[#6B7280] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter Link youtube"
                            />
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6">
                    <button
                        type="button"
                        @click="resetForm"
                        class="px-6 py-2 bg-transparent border border-[#333333] text-white rounded-lg hover:bg-[#252525] transition font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="submitForm"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm"
                    >
                        Create
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import axios from "axios";
import Swal from "sweetalert2";
const form = ref({
    title: "",
    video: null,
    thumbnail: null,
    description: "",
    source: "",
    tag: "",
    link_youtube: "",
});

const visitBacktoProfile = () => {
    showModal.value = false;
    router.visit("/profile-page");
};

const videoName = ref("");
const videoPreview = ref("");
const thumbnailName = ref("");
const thumbnailPreview = ref("");

const handleVideoUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Check if the file is a video
    if (!file.type.startsWith("video/")) {
        alert("Please upload a video file only.");
        return;
    }

    form.value.video = file;
    videoName.value = file.name;

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
        videoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const handleThumbnailUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Check if the file is an image
    if (!file.type.startsWith("image/")) {
        alert("Please upload an image file for thumbnail.");
        return;
    }

    form.value.thumbnail = file;
    thumbnailName.value = file.name;

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
        thumbnailPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};
const fileInput = ref(null);
const loading = ref(false);
const submitForm = async () => {
    console.log("Form submitted:", form.value);
    const formData = new FormData();
    formData.append("title", form.value.title);
    formData.append("description", form.value.description);
    formData.append("video_url", form.value.video);
    formData.append("thumbnail", form.value.thumbnail);
    formData.append("source", form.value.source);
    formData.append("link_youtube", form.value.link_youtube);
    formData.append("tag", form.value.tag);

    loading.value = true;

    const response = await axios.post("/api/content-video", formData, {
        headers: {
            "Content-Type": "multipart/form-data",
            Accept: "application/json",
        },
    });

    if (response.status === 201) {
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Video uploaded successfully",
        });

        resetForm();
        // Reset file input
        if (fileInput.value) {
            fileInput.value.value = "";
        }

        router.visit("/videos");
    }

    if (response.status === 422) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Video uploaded failed",
        });
    }
};

const resetForm = () => {
    form.value = {
        title: "",
        video: null,
        thumbnail: null,
        description: "",
        source: "",
        tag: "",
        link_youtube: "",
    };
    videoName.value = "";
    videoPreview.value = "";
    thumbnailName.value = "";
    thumbnailPreview.value = "";
};
</script>
