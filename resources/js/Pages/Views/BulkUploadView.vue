<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 p-6">
        <div
            v-if="!showPreview"
            class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8"
        >
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
                📦 Bulk Upload ZIP
            </h1>

            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Upload satu file <strong>.zip</strong> yang berisi
                <code>metadata.xlsx</code> dan folder <code>media/</code> berisi
                foto/video.
            </p>

            <!-- Upload Area -->
            <label
                for="zipInput"
                class="flex flex-col items-center justify-center border-2 border-dashed rounded-xl h-52 cursor-pointer transition hover:border-blue-500 dark:border-gray-700 dark:hover:border-blue-400"
                :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="handleDrop"
            >
                <svg
                    class="w-12 h-12 text-blue-500 mb-2"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4v16m8-8H4"
                    />
                </svg>
                <p class="text-gray-600 dark:text-gray-300">
                    Drag & Drop ZIP file here or click to select
                </p>
                <input
                    id="zipInput"
                    type="file"
                    accept=".zip"
                    class="hidden"
                    @change="handleFileChange"
                />
            </label>

            <div
                v-if="zipFile"
                class="mt-4 text-sm text-gray-700 dark:text-gray-300"
            >
                ✅ Selected file: <strong>{{ zipFile.name }}</strong>
            </div>

            <!-- Upload Button -->
            <button
                @click="uploadZip"
                :disabled="!zipFile || isLoading"
                class="mt-6 w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition disabled:bg-gray-400"
            >
                {{ isLoading ? "Uploading..." : "Upload ZIP" }}
            </button>

            <!-- Upload Result -->
            <div
                v-if="message"
                class="mt-4 p-4 rounded-xl bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
            >
                {{ message }}
            </div>

            <div
                v-if="error"
                class="mt-4 p-4 rounded-xl bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
            >
                ❌ {{ error }}
            </div>

            <!-- Tips Section -->
            <div class="mt-8">
                <h2
                    class="text-lg font-bold text-gray-700 dark:text-white mb-2"
                >
                    📝 Struktur ZIP yang benar:
                </h2>
                <pre
                    class="bg-gray-200 dark:bg-gray-700 text-sm p-4 rounded-lg text-gray-800 dark:text-gray-100"
                >
upload.zip
├── metadata.xlsx
└── media/
    ├── foto1.jpg
    ├── video1.mp4
    └── ...
        </pre
                >
            </div>
        </div>

        <!-- Preview Component -->
        <BulkUploadPreview
            v-else
            :items="parsedItems"
            @back="showPreview = false"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import BulkUploadPreview from "./BulkUploadPreview.vue";

const zipFile = ref(null);
const isDragging = ref(false);
const isLoading = ref(false);
const message = ref("");
const error = ref("");
const showPreview = ref(false);
const parsedItems = ref([]);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (
        file &&
        (file.name.toLowerCase().endsWith(".zip") ||
            file.type === "application/zip" ||
            file.type === "application/x-zip-compressed")
    ) {
        zipFile.value = file;
    } else {
        Swal.fire({
            icon: "error",
            title: "Invalid File",
            text: "Please select a valid ZIP file",
        });
    }
};

const handleDrop = (e) => {
    isDragging.value = false;
    const file = e.dataTransfer.files[0];
    if (
        file &&
        (file.name.toLowerCase().endsWith(".zip") ||
            file.type === "application/zip" ||
            file.type === "application/x-zip-compressed")
    ) {
        zipFile.value = file;
    } else {
        Swal.fire({
            icon: "error",
            title: "Invalid File",
            text: "Please drop a valid ZIP file",
        });
    }
};

const uploadZip = async () => {
    if (!zipFile.value) return;

    isLoading.value = true;
    error.value = "";
    message.value = "";

    const formData = new FormData();
    formData.append("zip_file", zipFile.value);

    try {
        const response = await axios.post("/api/bulk-upload", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        parsedItems.value = response.data.items;
        showPreview.value = true;

        Swal.fire({
            icon: "success",
            title: "Success!",
            text: response.data.message,
        });
    } catch (err) {
        error.value = err.response?.data?.message || "Upload failed";

        Swal.fire({
            icon: "error",
            title: "Upload Failed",
            text: error.value,
        });
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
pre {
    font-family: "Fira Code", monospace;
}
</style>
