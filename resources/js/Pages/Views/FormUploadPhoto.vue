<template>
  <MainLayout>
    <div class="mt-14 mx-auto p-6 bg-[#0d0d0d] max-w-full">
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
        CREATE CONTENT PHOTO
      </h2>
      <!-- Changed title -->

      <div class="space-y-6">
        <!-- Two Column Layout for most fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-6">
            <!-- Title Field -->
            <div>
              <label for="title" class="block text-sm font-medium text-white mb-2"
                >Title*</label
              >
              <input
                type="text"
                id="title"
                v-model="form.title"
                required
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter content title"
              />
            </div>

            <!-- Image Upload Field -->
            <div>
              <label class="block text-sm font-medium text-white mb-2">Image</label>
              <div
                class="w-full aspect-video border-2 border-dashed border-gray-500 rounded-lg overflow-hidden hover:bg-[#1f1f1f] transition cursor-pointer relative"
                @dragover.prevent
                @drop.prevent="handleDrop"
              >
                <!-- Show image preview if exists -->
                <div v-if="filePreview" class="h-full">
                  <img
                    :src="filePreview"
                    alt="Preview"
                    class="w-full h-full object-contain bg-[#1a1a1a]"
                  />
                  <!-- Remove Button -->
                  <button
                    @click="removeImage"
                    class="absolute top-2 right-2 p-1 hover:bg-black rounded-full text-white shadow-lg transition-colors z-10"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="size-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18 18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>

                <!-- Show upload placeholder if no image -->
                <div
                  v-else
                  class="h-full flex flex-col items-center justify-center p-6"
                  @click="$refs.fileInput.click()"
                >
                  <svg
                    class="h-12 w-12 text-gray-400 mb-4"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 48 48"
                  >
                    <path
                      d="M14 22l6 6 14-14"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <rect
                      width="40"
                      height="40"
                      x="4"
                      y="4"
                      rx="2"
                      ry="2"
                      stroke-width="2"
                    />
                  </svg>
                  <p class="text-sm text-gray-400">
                    <span class="text-blue-400 underline">Upload a file</span> or drag and
                    drop
                  </p>
                  <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                </div>

                <input
                  type="file"
                  ref="fileInput"
                  class="hidden"
                  accept="image/*"
                  @change="handleFileUpload"
                />
              </div>
              <!-- File Name -->
              <p v-if="fileName" class="mt-2 text-sm text-gray-400 truncate">
                {{ fileName }}
              </p>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Tag Field -->
            <div>
              <label for="tag" class="block text-sm font-medium text-white mb-2"
                >Tag</label
              >
              <input
                type="text"
                id="tag"
                v-model="form.tag"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Tag"
              />
            </div>
          </div>
        </div>
        <!-- Description Field (full width) -->
        <div>
          <label for="description" class="block text-sm font-medium text-white mb-2"
            >Description</label
          >
          <textarea
            id="description"
            v-model="form.description"
            class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-h-[100px]"
            placeholder="Enter content description"
          ></textarea>
        </div>
        <!-- Two Column Layout for most fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-6">
            <!-- Source Field -->
            <div>
              <label for="source" class="block text-sm font-medium text-white mb-2"
                >Source*</label
              >
              <input
                type="text"
                id="source"
                v-model="form.source"
                required
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter content source"
              />
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Alt Text Field -->
            <div>
              <label for="alt-text" class="block text-sm font-medium text-white mb-2"
                >Alt Text</label
              >
              <input
                type="text"
                id="alt-text"
                v-model="form.altText"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter alt text for accessibility"
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
  slug: "",
  media: null,
  description: "",
  source: "",
  tag: "",
  altText: "",
});

const fileInput = ref(null);

const visitBacktoProfile = () => {
  showModal.value = false;
  router.visit("/profile-page");
};

const fileName = ref("");
const filePreview = ref("");

const isImage = computed(() => {
  return form.value.media?.type?.startsWith("image/");
});

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  // Check if the file is an image
  if (!file.type.startsWith("image/")) {
    alert("Please upload an image file only.");
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

const handleDrop = (event) => {
  const file = event.dataTransfer.files[0];
  if (!file || !file.type.startsWith("image/")) {
    Swal.fire("Oops!", "Please upload an image file only.", "error");
    return;
  }
  form.value.media = file;
  fileName.value = file.name;

  const reader = new FileReader();
  reader.onload = (e) => {
    filePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const loading = ref(false);

const submitForm = async () => {
  try {
    // Create FormData object to handle file upload
    const formData = new FormData();
    formData.append("title", form.value.title);
    formData.append("description", form.value.description);
    formData.append("image", form.value.media);
    formData.append("source", form.value.source || "");
    formData.append("alt_text", form.value.altText || "");
    formData.append("note", form.value.note || "");
    formData.append("tag", form.value.tag || "");

    // Show loading state
    loading.value = true;

    // Send POST request to upload photo
    const response = await axios.post("/api/content-photo", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        Accept: "application/json",
      },
    });

    // Handle successful upload
    if (response.status === 201) {
      // Show success message sweetalert
      Swal.fire({
        icon: "success",
        title: "Photo uploaded successfully",
      });

      // Reset form
      form.value = {
        title: "",
        description: "",
        media: null,
        source: "",
        altText: "",
        note: "",
        tag: "",
      };

      // Reset file input
      if (fileInput.value) {
        fileInput.value.value = "";
      }

      // Redirect to photos page or show preview
      router.push("/photos");
    }
  } catch (error) {
    // Handle validation errors
    if (error.response?.status === 422) {
      const errors = error.response.data.errors;
      Object.keys(errors).forEach((key) => {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: errors[key][0],
        });
      });
    } else {
      // Handle other errors
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Failed to upload photo. Please try again.",
      });
      console.error("Upload error:", error);
    }
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.value = {
    title: "",
    media: null,
    description: "",
    source: "",
    tag: "",
    altText: "",
  };
  fileName.value = "";
  filePreview.value = "";
};

// Add to script setup section
const removeImage = () => {
  form.value.media = null;
  fileName.value = "";
  filePreview.value = "";
  if (fileInput.value) {
    fileInput.value.value = "";
  }
};
</script>
