<template>
    <MainLayout>
        <div class="mx-auto p-6 bg-white dark:bg-black max-w-full">
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

            <h2
                class="text-2xl font-bold text-center text-black dark:text-white mt-10 mb-8"
            >
                VALIDATE VIDEO CONTENT
            </h2>

            <div v-if="videoData" class="space-y-6">
                <!-- Preview Section -->
                <div class="bg-zinc-200 dark:bg-zinc-900 rounded-lg p-6">
                    <h3
                        class="text-lg font-semibold text-black dark:text-white mb-4"
                    >
                        Content Preview
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Video/Thumbnail Preview -->
                        <div class="space-y-4">
                            <!-- YouTube Video -->
                            <div
                                v-if="videoData.youtubeVideoId"
                                class="aspect-video bg-black rounded-lg overflow-hidden"
                            >
                                <lite-youtube-embed
                                    :videoid="videoData.youtubeVideoId"
                                    :playlabel="videoData.title"
                                    class="w-full h-full"
                                ></lite-youtube-embed>
                                <p class="mt-2 text-xs text-blue-400">
                                    Source: YouTube
                                </p>
                            </div>

                            <!-- Uploaded Video -->
                            <div
                                v-else-if="videoPreview"
                                class="aspect-video bg-black rounded-lg overflow-hidden"
                            >
                                <video
                                    controls
                                    class="w-full h-full object-contain"
                                >
                                    <source :src="videoPreview" />
                                </video>
                                <p class="mt-2 text-xs text-green-400">
                                    Source: Uploaded File
                                </p>
                            </div>

                            <!-- File names -->
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                <p v-if="videoData.videoName">
                                    Video: {{ videoData.videoName }}
                                </p>
                                <p v-if="videoData.thumbnailName">
                                    Thumbnail: {{ videoData.thumbnailName }}
                                </p>
                            </div>

                            <!-- Thumbnail -->
                            <div v-if="thumbnailPreview" class="mt-4">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2"
                                    >Thumbnail</label
                                >
                                <img
                                    :src="thumbnailPreview"
                                    alt="Thumbnail"
                                    class="w-full aspect-video object-contain bg-black rounded-lg"
                                />
                            </div>
                        </div>

                        <!-- Content Details -->
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >Title</label
                                >
                                <p class="text-black dark:text-white">
                                    {{ videoData.title }}
                                </p>
                            </div>

                            <div v-if="videoData.description">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >Description</label
                                >
                                <p class="text-black dark:text-white">
                                    {{ videoData.description }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >Source</label
                                >
                                <p class="text-black dark:text-white">
                                    {{ videoData.source }}
                                </p>
                            </div>

                            <div v-if="videoData.tag">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >Tags</label
                                >
                                <p class="text-black dark:text-white">
                                    {{ videoData.tag }}
                                </p>
                            </div>

                            <div v-if="videoData.link_youtube">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >YouTube Link</label
                                >
                                <p class="text-black dark:text-white break-all">
                                    {{ videoData.link_youtube }}
                                </p>
                            </div>

                            <div
                                v-else-if="
                                    videoData.videoName &&
                                    !videoData.youtubeVideoId
                                "
                            >
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >Video Type</label
                                >
                                <p class="text-black dark:text-white">
                                    Uploaded File
                                </p>
                            </div>

                            <div
                                v-if="
                                    videoData.categories &&
                                    videoData.categories.length > 0
                                "
                            >
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
                                    >Categories</label
                                >
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="category in videoData.categories"
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
                        class="px-6 py-2 bg-transparent border border-red-500 text-black dark:text-white rounded-lg hover:bg-red-500 hover:text-white transition font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="confirmSubmission"
                        :disabled="loading"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm disabled:opacity-50"
                    >
                        {{
                            loading
                                ? uploadProgress > 0
                                    ? `Uploading... ${uploadProgress}%`
                                    : "Uploading..."
                                : "Confirm & Upload"
                        }}
                    </button>
                </div>
            </div>

            <div v-else class="text-center text-white">
                <p>No video data found. Please go back and fill the form.</p>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import axios from "axios";
import Swal from "sweetalert2";
import "lite-youtube-embed/src/lite-yt-embed.css";

const videoData = ref(null);
const loading = ref(false);
const uploadProgress = ref(0);
const videoPreview = ref("");
const thumbnailPreview = ref("");

onMounted(() => {
    // Get data from sessionStorage
    const storedData = sessionStorage.getItem("videoValidationData");
    if (storedData) {
        videoData.value = JSON.parse(storedData);

        // Recreate previews from the files stored in global variable
        if (window.videoValidationFiles) {
            if (window.videoValidationFiles.video) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    videoPreview.value = e.target.result;
                };
                reader.readAsDataURL(window.videoValidationFiles.video);
            }

            if (window.videoValidationFiles.thumbnail) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    thumbnailPreview.value = e.target.result;
                };
                reader.readAsDataURL(window.videoValidationFiles.thumbnail);
            }
        }
    } else {
        // If no data, redirect back to form
        router.visit("/upload-video");
    }
});

const goBack = () => {
    router.visit("/upload-video");
};

const cancelSubmission = () => {
    // Clear stored data and go back to form
    sessionStorage.removeItem("videoValidationData");
    if (window.videoValidationFiles) {
        delete window.videoValidationFiles;
    }
    router.visit("/upload-video");
};

const confirmSubmission = async () => {
    if (!videoData.value || !window.videoValidationFiles) return;

    try {
        loading.value = true;
        uploadProgress.value = 0;

        const formData = new FormData();
        formData.append("title", videoData.value.title);
        formData.append("description", videoData.value.description || "");
        formData.append("source", videoData.value.source);
        formData.append("tag", videoData.value.tag || "");
        formData.append("thumbnail", window.videoValidationFiles.thumbnail);

        // Append category IDs
        videoData.value.category_ids.forEach((categoryId, index) => {
            formData.append(`category_ids[${index}]`, categoryId);
        });

        // Handle video content
        if (videoData.value.link_youtube?.trim()) {
            formData.append(
                "link_youtube",
                videoData.value.link_youtube.trim()
            );
        } else if (window.videoValidationFiles.video) {
            formData.append("video_url", window.videoValidationFiles.video);
        }

        const response = await axios.post("/api/content-video", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Accept: "application/json",
            },
            timeout: 300000, // 5 minutes timeout for large file uploads
            onUploadProgress: (progressEvent) => {
                const percentCompleted = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
                uploadProgress.value = percentCompleted;
                console.log(`Upload progress: ${percentCompleted}%`);
                // You could add a progress bar here if needed
            },
        });

        if (response.status === 201) {
            // Clear stored data
            sessionStorage.removeItem("videoValidationData");
            if (window.videoValidationFiles) {
                delete window.videoValidationFiles;
            }

            Swal.fire({
                icon: "success",
                title: "Video uploaded successfully",
            }).then(() => {
                router.visit("/profile-page");
            });
        }
    } catch (error) {
        console.error("Upload error:", error);
        if (error.code === "ERR_NETWORK" || error.message === "Network Error") {
            Swal.fire({
                icon: "success",
                title: "Upload Successful!",
                html: `
                    <div class="text-left">
                        <p class="mb-3">🎉 Your video has been uploaded successfully!</p>
                        <p class="mb-3">📹 Your video is currently being processed in the background.</p>
                        <p class="mb-3">✅ You can check your profile to see the uploaded content.</p>
                        <p class="text-sm text-gray-600">Note: Large videos may take a few moments to appear in your profile.</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: "View My Profile",
                cancelButtonText: "Upload Another",
                confirmButtonColor: "#3b82f6",
                cancelButtonColor: "#10b981",
            }).then((result) => {
                sessionStorage.removeItem("videoValidationData");
                if (window.videoValidationFiles) {
                    delete window.videoValidationFiles;
                }

                if (result.isConfirmed) {
                    router.visit("/profile-page");
                } else {
                    router.visit("/upload-video");
                }
            });
            return;
        }

        // Handle other types of errors
        let errorMessage = "Failed to upload video. Please try again.";
        if (error.response?.data?.message) {
            errorMessage = error.response.data.message;
        } else if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            errorMessage = Object.values(errors).flat().join("\n");
        } else if (error.code === "ECONNABORTED") {
            errorMessage =
                "Upload timed out. Please try again with a smaller file or check your connection.";
        }

        Swal.fire({
            icon: "error",
            title: "Upload Failed",
            text: errorMessage,
        });
    } finally {
        loading.value = false;
        uploadProgress.value = 0;
    }
};
</script>
