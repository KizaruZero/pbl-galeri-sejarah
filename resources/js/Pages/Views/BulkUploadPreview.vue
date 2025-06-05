<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 p-6">
        <div
            class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8"
        >
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                    Preview Bulk Upload
                </h1>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Item {{ currentIndex + 1 }} of {{ items.length }}
                </div>
            </div>

            <!-- Progress Bar -->
            <div
                class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-8"
            >
                <div
                    class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                    :style="{
                        width: `${((currentIndex + 1) / items.length) * 100}%`,
                    }"
                ></div>
            </div>

            <!-- Content Preview -->
            <div v-if="currentItem" class="space-y-6">
                <!-- Media Preview -->
                <div
                    class="aspect-video bg-gray-900 rounded-lg overflow-hidden"
                >
                    <template v-if="currentItem.type === 'photo'">
                        <img
                            :src="`/storage/${currentItem.media_url}`"
                            :alt="currentItem.title"
                            class="w-full h-full object-contain"
                        />
                    </template>
                    <template v-else>
                        <video
                            v-if="!currentItem.link_youtube"
                            :src="`/storage/${currentItem.media_url}`"
                            controls
                            class="w-full h-full"
                        ></video>
                        <iframe
                            v-else
                            :src="getYoutubeEmbedUrl(currentItem.link_youtube)"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </template>
                </div>

                <!-- Content Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Title</label
                            >
                            <div
                                class="mt-1 text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ currentItem.title }}
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Description</label
                            >
                            <div class="mt-1 text-gray-600 dark:text-gray-400">
                                {{ currentItem.description }}
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Source</label
                            >
                            <div class="mt-1 text-gray-600 dark:text-gray-400">
                                {{ currentItem.source }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Categories</label
                            >
                            <div class="mt-1 flex flex-wrap gap-2">
                                <span
                                    v-for="category in currentItem.categories"
                                    :key="category"
                                    class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-sm"
                                >
                                    {{ category }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Tags</label
                            >
                            <div class="mt-1 flex flex-wrap gap-2">
                                <span
                                    v-for="tag in currentItem.tag.split(',')"
                                    :key="tag"
                                    class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-full text-sm"
                                >
                                    {{ tag.trim() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div
                    class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700"
                >
                    <button
                        @click="previousItem"
                        :disabled="currentIndex === 0"
                        class="px-6 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <div class="space-x-4">
                        <button
                            @click="skipItem"
                            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
                        >
                            Skip
                        </button>
                        <button
                            @click="saveItem"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            {{ isLastItem ? "Finish" : "Save & Next" }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Items Message -->
            <div v-else class="text-center py-12">
                <p class="text-gray-600 dark:text-gray-400">
                    No items to preview
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import Swal from "sweetalert2";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const currentIndex = ref(0);
const currentItem = computed(() => props.items[currentIndex.value]);
const isLastItem = computed(
    () => currentIndex.value === props.items.length - 1
);

const getYoutubeEmbedUrl = (url) => {
    const videoId = url.match(
        /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i
    )?.[1];
    return videoId ? `https://www.youtube.com/embed/${videoId}` : "";
};

const previousItem = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

const nextItem = () => {
    if (currentIndex.value < props.items.length - 1) {
        currentIndex.value++;
    }
};

const skipItem = async () => {
    try {
        // Remove the current item from the array
        props.items.splice(currentIndex.value, 1);

        // If we're at the end, go back one
        if (currentIndex.value >= props.items.length) {
            currentIndex.value = props.items.length - 1;
        }

        if (props.items.length === 0) {
            await Swal.fire({
                icon: "success",
                title: "Complete",
                text: "All items have been processed",
            });
            router.visit("/profile-page");
        }
    } catch (error) {
        console.error("Error skipping item:", error);
    }
};

const saveItem = async () => {
    try {
        const item = currentItem.value;
        const formData = new FormData();

        // First, get category IDs from names
        const categoryIds = [];
        for (const categoryName of item.categories) {
            try {
                const response = await axios.get(
                    `/api/categories/name/${categoryName.trim()}`
                );
                if (response.data && response.data.id) {
                    categoryIds.push(response.data.id);
                }
            } catch (error) {
                console.error(
                    `Error fetching category ID for ${categoryName}:`,
                    error
                );
            }
        }

        // Add all item data to formData
        Object.keys(item).forEach((key) => {
            if (key === "categories") {
                // Send category IDs
                categoryIds.forEach((id) => {
                    formData.append("category_ids[]", id);
                });
            } else if (key === "media_url" || key === "thumbnail_url") {
                // Skip media and thumbnail URLs as we'll handle them separately
                return;
            } else {
                formData.append(key, item[key]);
            }
        });

        // Handle media file based on type
        if (item.media_url) {
            try {
                if (item.type === "photo") {
                    // For photos, use the original file from the ZIP to preserve EXIF data
                    const photoPath = item.media_url;
                    const photoFile = await fetch(`/storage/${photoPath}`).then(
                        (r) => r.blob()
                    );
                    const extension = photoPath.split(".").pop().toLowerCase();

                    // Map common image extensions to their MIME types
                    const imageMimeTypes = {
                        jpeg: "image/jpeg",
                        jpg: "image/jpeg",
                        png: "image/png",
                        gif: "image/gif",
                    };

                    const mimeType = imageMimeTypes[extension] || "image/jpeg";

                    // Create a new File object with the correct MIME type
                    const photoFileObj = new File(
                        [photoFile],
                        photoPath.split("/").pop(),
                        { type: mimeType }
                    );

                    formData.append("image", photoFileObj);
                    // TAMBAHKAN EXIF DATA YANG SUDAH DI-EXTRACT
                    if (item.exif_data) {
                        // Send each EXIF field individually to maintain array structure
                        Object.entries(item.exif_data).forEach(
                            ([key, value]) => {
                                formData.append(
                                    `exif_data[${key}]`,
                                    value === null ? "" : value
                                );
                            }
                        );
                    }
                } else {
                    // For videos, use the original file from the ZIP
                    const videoPath = item.media_url;
                    const videoFile = await fetch(`/storage/${videoPath}`).then(
                        (r) => r.blob()
                    );
                    const extension = videoPath.split(".").pop().toLowerCase();

                    // Map common video extensions to their MIME types
                    const videoMimeTypes = {
                        mp4: "video/mp4",
                        avi: "video/x-msvideo",
                        mov: "video/quicktime",
                        wmv: "video/x-ms-wmv",
                        flv: "video/x-flv",
                        mpeg: "video/mpeg",
                        mpg: "video/mpeg",
                        m4v: "video/mp4",
                        webm: "video/webm",
                        mkv: "video/x-matroska",
                    };

                    const mimeType = videoMimeTypes[extension] || "video/mp4";

                    // Create a new File object with the correct MIME type
                    const videoFileObj = new File(
                        [videoFile],
                        videoPath.split("/").pop(),
                        { type: mimeType }
                    );

                    formData.append("video_url", videoFileObj);
                }
            } catch (error) {
                console.error("Error processing media file:", error);
                throw new Error("Failed to process media file");
            }
        }

        // Handle thumbnail file for videos
        if (item.type === "video" && item.thumbnail_url) {
            try {
                // Fetch the thumbnail file from storage
                const response = await axios.get(
                    `/storage/${item.thumbnail_url}`,
                    {
                        responseType: "blob",
                    }
                );
                // Create a File object from the blob
                const thumbnailFile = new File(
                    [response.data],
                    item.thumbnail_url.split("/").pop(),
                    { type: response.data.type }
                );

                // Append the file to formData
                formData.append("thumbnail", thumbnailFile);
            } catch (error) {
                console.error("Error fetching thumbnail file:", error);
                throw new Error("Failed to process thumbnail file");
            }
        }

        // Send to appropriate endpoint based on type
        const endpoint =
            item.type === "photo" ? "/api/content-photo" : "/api/content-video";
        await axios.post(endpoint, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        if (isLastItem.value) {
            await Swal.fire({
                icon: "success",
                title: "Complete",
                text: "All items have been saved successfully",
            });
            router.visit("/profile-page");
        } else {
            nextItem();
        }
    } catch (error) {
        console.error("Error saving item:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: error.response?.data?.message || "Failed to save item",
        });
    }
};
</script>
