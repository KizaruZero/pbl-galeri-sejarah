<template>
  <MainLayout>
    <div class="mx-auto p-6 bg-[#0d0d0d] max-w-full">
      <button
        @click="visitBacktoProfile"
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
        Back to Profile
      </button>
      <h2 class="text-2xl font-bold text-center text-white mt-10 mb-8">
        UPDATE CONTENT VIDEO
      </h2>

      <div class="space-y-6">
        <!-- Two Column Layout for most fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div class="space-y-6">
            <!-- Title Field -->
            <div>
              <label for="title" class="block text-sm font-medium text-white mb-2">Title*</label>
              <input
                type="text"
                id="title"
                v-model="form.title"
                required
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter content title"
              />
            </div>

            <!-- Video Upload Field -->
            <div>
              <label class="block text-sm font-medium text-white mb-2">Video*</label>
              <div
                class="w-full aspect-video border-2 border-dashed border-gray-500 rounded-lg overflow-hidden hover:bg-[#1f1f1f] transition cursor-pointer relative"
                @dragover.prevent
                @drop.prevent="handleVideoDrop"
              >
                <!-- Show video preview if exists -->
                <div v-if="videoPreview" class="h-full">
                  <video
                    controls
                    class="w-full h-full object-contain bg-[#1a1a1a]"
                    @click.stop
                  >
                    <source :src="videoPreview" :type="form.video?.type" />
                    Your browser does not support the video tag.
                  </video>
                  <!-- Remove Button -->
                  <button
                    @click="removeVideo"
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

                <!-- Show upload placeholder if no video -->
                <div
                  v-else
                  class="h-full flex flex-col items-center justify-center p-6"
                  @click="$refs.videoInput.click()"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-12 w-12 text-gray-400 mb-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                    />
                  </svg>
                  <p class="text-sm text-gray-400">
                    <span class="text-blue-400 underline">Upload a video</span> or drag
                    and drop
                  </p>
                  <p class="mt-1 text-xs text-gray-500">MP4, WebM, OGG up to 100MB</p>
                </div>

                <input
                  type="file"
                  ref="videoInput"
                  id="video"
                  @change="handleVideoUpload"
                  accept="video/*"
                  class="hidden"
                />
              </div>
              <!-- File Name -->
              <p v-if="videoName" class="mt-2 text-sm text-gray-400 truncate">
                {{ videoName }}
              </p>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Tag Field -->
            <div>
              <label for="tag" class="block text-sm font-medium text-white mb-2">Tag</label>
              <input
                type="text"
                id="tag"
                v-model="form.tag"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Tag"
              />
            </div>

            <!-- Thumbnail Upload Field -->
            <div>
              <label class="block text-sm font-medium text-white mb-2">Thumbnail*</label>
              <div
                class="w-full aspect-video border-2 border-dashed border-gray-500 rounded-lg overflow-hidden hover:bg-[#1f1f1f] transition cursor-pointer"
                @dragover.prevent
                @drop.prevent="handleThumbnailDrop"
              >
                <!-- Show thumbnail preview if exists -->
                <div v-if="thumbnailPreview" class="h-full relative">
                  <img
                    :src="thumbnailPreview"
                    alt="Thumbnail Preview"
                    class="w-full h-full object-contain bg-[#1a1a1a]"
                  />
                  <!-- Remove Button -->
                  <button
                    @click="removeThumbnail"
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

                <!-- Show upload placeholder if no thumbnail -->
                <div
                  v-else
                  class="h-full flex flex-col items-center justify-center p-6"
                  @click="$refs.thumbnailInput.click()"
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
                    <span class="text-blue-400 underline">Upload a thumbnail</span> or
                    drag and drop
                  </p>
                  <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                </div>

                <input
                  type="file"
                  ref="thumbnailInput"
                  id="thumbnail"
                  @change="handleThumbnailUpload"
                  accept="image/*"
                  class="hidden"
                />
              </div>
              <!-- File Name -->
              <p v-if="thumbnailName" class="mt-2 text-sm text-gray-400 truncate">
                {{ thumbnailName }}
              </p>
            </div>

            <!-- Category Field -->
            <div>
              <label for="category" class="block text-sm font-medium text-white mb-2"
                >Category*</label
              >
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
                  :value="category.id"
                >
                  {{ category.category_name }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <!-- Description Field (full width) -->
        <div>
          <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
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
              <label for="source" class="block text-sm font-medium text-white mb-2">Source*</label>
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
            <!-- Link Youtube Field -->
            <div>
              <label for="link_youtube" class="block text-sm font-medium text-white mb-2"
                >Link Youtube</label
              >
              <input
                type="text"
                id="link-youtube"
                v-model="form.link_youtube"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter Link youtube"
              />
            </div>
          </div>
        </div>

        <!-- Add metadata section before Action Buttons -->
        <div v-if="showMetadataForm" class="space-y-6 mt-8 border-t border-[#333333] pt-8">
          <h3 class="text-xl font-bold text-white mb-6">Update Video Metadata</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-white mb-2">Collection Date</label>
              <input
                type="datetime-local"
                v-model="metadataForm.collection_date"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-white mb-2">Model</label>
              <input
                type="text"
                v-model="metadataForm.model"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Camera/Device model"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-white mb-2">Resolution</label>
              <input
                type="text"
                v-model="metadataForm.resolution"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="1920x1080"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-white mb-2">Frame Rate</label>
              <input
                type="text"
                v-model="metadataForm.frame_rate"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="30 fps"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-white mb-2">Location</label>
              <input
                type="text"
                v-model="metadataForm.location"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Location where video was taken"
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
            Update
          </button>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
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
  category_id: "",
});

// Add refs for existing data
const videoId = ref(window.location.pathname.split('/').pop());
const existingVideoUrl = ref('');
const existingThumbnailUrl = ref('');

// Add categories ref
const categories = ref([]);
const loading = ref(false);
const videoName = ref("");
const videoPreview = ref("");
const thumbnailName = ref("");
const thumbnailPreview = ref("");

// Add these new refs
const showMetadataForm = ref(true);
const metadataForm = ref({
  collection_date: '',
  model: '',
  resolution: '',
  frame_rate: '',
  location: '',
});
    const formatDateForInput = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
    };

const visitBacktoProfile = () => {
  window.location.href = "/profile-page";
};

// Modify submitForm to handle updates
const submitForm = async () => {
  try {
    const formData = new FormData();

    // Always append these fields
    formData.append("title", form.value.title);
    formData.append("description", form.value.description);
    formData.append("source", form.value.source);
    formData.append("link_youtube", form.value.link_youtube);
    formData.append("tag", form.value.tag);
    formData.append("category_id", form.value.category_id);

    // Only append files if they were changed
    if (form.value.video instanceof File) {
      formData.append("video_url", form.value.video_url);
    }
    if (form.value.thumbnail instanceof File) {
      formData.append("thumbnail", form.value.thumbnail);
    }

    // Add metadata to formData
    formData.append("metadata[collection_date]", metadataForm.value.collection_date);
    formData.append("metadata[model]", metadataForm.value.model);
    formData.append("metadata[resolution]", metadataForm.value.resolution);
    formData.append("metadata[frame_rate]", metadataForm.value.frame_rate);
    formData.append("metadata[location]", metadataForm.value.location);

    loading.value = true;

    const response = await axios.post(`/api/content-video/${videoId.value}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
      }
    });

    if (response.status === 200) {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Video updated successfully'
      });
      router.visit('/profile-page');
    }
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Update Failed',
      text: error.response?.data?.message || 'An error occurred'
    });
  } finally {
    loading.value = false;
  }
};

// Modify the loadVideoData function to include proper categories loading
const loadData = async () => {
  try {
    // Load categories
    const categoriesResponse = await axios.get('/api/categories');
    categories.value = categoriesResponse.data.data || [];
    categories.value.sort((a, b) => a.category_name.localeCompare(b.category_name));

    // Load video data
    const videoResponse = await axios.get(`/api/content-video/edit/${videoId.value}`);
    const videoData = videoResponse.data;

    // Populate form
    form.value = {
      title: videoData.video.title,
      description: videoData.video.description,
      source: videoData.video.source,
      tag: videoData.video.tag,
      link_youtube: videoData.video.link_youtube,
      category_id: videoData.category_id,
      video: videoData.video.video_url,
      thumbnail: videoData.video.thumbnail,
    };

    // Set previews
    if (videoData.video.video_url) {
      existingVideoUrl.value = `/storage/${videoData.video.video_url}`;
      videoPreview.value = existingVideoUrl.value;
    }
    if (videoData.video.thumbnail) {
      existingThumbnailUrl.value = `/storage/${videoData.video.thumbnail}`;
      thumbnailPreview.value = existingThumbnailUrl.value;
    }

    // Add this after loading video data
    if (videoData.video.metadata_video) {
      metadataForm.value = {
        collection_date: formatDateForInput(videoData.video.metadata_video.collection_date),
        model: videoData.video.metadata_video.model,
        resolution: videoData.video.metadata_video.resolution,
        frame_rate: videoData.video.metadata_video.frame_rate,
        location: videoData.video.metadata_video.location,
      };
    }

  } catch (error) {
    console.error('Error loading data:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to load data. Please try again.'
    });
  }
};

const handleVideoUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.video = file;
    videoName.value = file.name;
    videoPreview.value = URL.createObjectURL(file);
  }
};

const handleThumbnailUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.thumbnail = file;
    thumbnailName.value = file.name;
    thumbnailPreview.value = URL.createObjectURL(file);
  }
};

const removeVideo = () => {
  form.value.video = null;
  videoName.value = "";
  videoPreview.value = existingVideoUrl.value || "";
};

const removeThumbnail = () => {
  form.value.thumbnail = null;
  thumbnailName.value = "";
  thumbnailPreview.value = existingThumbnailUrl.value || "";
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
    category_id: "",
  };
  videoName.value = "";
  videoPreview.value = "";
  thumbnailName.value = "";
  thumbnailPreview.value = "";
};

// Update onMounted to use the new loadData function
onMounted(() => {
  loadData();
});
</script>
