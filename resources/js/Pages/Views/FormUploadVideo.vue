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
        CREATE CONTENT VIDEO
      </h2>

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

            <!-- Video Upload Field -->
            <div>
              <label class="block text-sm font-medium text-white mb-2">Video*</label>
              <div
                class="w-full aspect-video border-2 border-dashed border-gray-500 rounded-lg overflow-hidden hover:bg-[#1f1f1f] transition cursor-pointer relative"
                @dragover.prevent
                @drop.prevent="handleVideoDrop"
              >
                <!-- Show YouTube video preview if exists -->
                <div v-if="youtubeVideoId" class="h-full relative">
                  <lite-youtube-embed
                    :videoid="youtubeVideoId"
                    :playlabel="form.title"
                    class="w-full h-full"
                  ></lite-youtube-embed>
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

                <!-- Show video preview if exists -->
                <div v-else-if="videoPreview" class="h-full">
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
              <label for="tag" class="block text-sm font-medium text-white mb-2"
                >Tag</label
              >
              <input
                type="text"
                id="tag"
                v-model="form.tag"
                class="w-full px-4 py-3 bg-gray-500 border border-[#333333] rounded-lg text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter tags"
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
import { ref, computed, onMounted, watch } from "vue";
import { router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import axios from "axios";
import Swal from "sweetalert2";
import 'lite-youtube-embed/src/lite-yt-embed.css';

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

const visitBacktoProfile = () => {
  window.history.back();
};

const videoName = ref("");
const videoPreview = ref("");
const thumbnailName = ref("");
const thumbnailPreview = ref("");
const youtubeVideoId = ref("");

// YouTube URL validation function
const getYoutubeVideoId = (url) => {
  if (!url) return null;
  
  const regularMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu.be\/)([^&\s]+)/);
  const shortMatch = url.match(/youtube.com\/shorts\/([^&\s]+)/);
  
  return regularMatch?.[1] || shortMatch?.[1];
};

const handleVideoUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  // Clear YouTube link when uploading a file
  form.value.link_youtube = "";
  youtubeVideoId.value = "";

  if (!file.type.startsWith("video/")) {
    Swal.fire("Oops!", "Please upload a video file only.", "error");
    return;
  }

  if (file.size > 100 * 1024 * 1024) {
    Swal.fire("Oops!", "Video file size should not exceed 100MB.", "error");
    return;
  }

  // Explicitly create new File object
  form.value.video = new File([file], file.name, {
    type: file.type,
    lastModified: file.lastModified,
  });
  videoName.value = file.name;

  const reader = new FileReader();
  reader.onload = (e) => {
    videoPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const handleThumbnailUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (!file.type.startsWith("image/")) {
    Swal.fire("Oops!", "Please upload an image file for thumbnail.", "error");
    return;
  }

  if (file.size > 10 * 1024 * 1024) {
    Swal.fire("Oops!", "Thumbnail file size should not exceed 10MB.", "error");
    return;
  }

  // Explicitly create new File object
  form.value.thumbnail = new File([file], file.name, {
    type: file.type,
    lastModified: file.lastModified,
  });
  thumbnailName.value = file.name;

  const reader = new FileReader();
  reader.onload = (e) => {
    thumbnailPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const fileInput = ref(null);
const loading = ref(false);

const submitForm = async () => {
  try {
    // Validate required fields
    if (!form.value.title || !form.value.source || !form.value.category_id) {
      Swal.fire({
        icon: "error",
        title: "Validation Error",
        text: "Please fill in all required fields (Title, Source, and Category)",
      });
      return;
    }

    const formData = new FormData();
    
    // Append basic form data
    formData.append('title', form.value.title);
    formData.append('description', form.value.description || '');
    formData.append('source', form.value.source);
    formData.append('tag', form.value.tag || '');
    formData.append('category_id', form.value.category_id);
    formData.append('thumbnail', form.value.thumbnail);

    // Handle video content
    if (form.value.link_youtube?.trim()) {
      // For YouTube videos
      formData.append('link_youtube', form.value.link_youtube.trim());
    } else if (form.value.video instanceof File) {
      // For uploaded videos
      formData.append('video_url', form.value.video);
    } else {
      Swal.fire({
        icon: "error",
        title: "Validation Error",
        text: "Please either upload a video file or provide a YouTube link",
      });
      return;
    }

    loading.value = true;

    const response = await axios.post('/api/content-video', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json',
      }
    });

    if (response.status === 201) {
      Swal.fire({
        icon: "success",
        title: "Success",
        text: "Video uploaded successfully",
      });
      resetForm();
      router.visit("/upload-video");
    }
  } catch (error) {
    console.error("Error details:", error.response?.data || error);
    let errorMessage = "An error occurred while uploading the video";
    
    if (error.response?.data?.message) {
      errorMessage = error.response.data.message;
    } else if (error.response?.data?.errors) {
      const errors = error.response.data.errors;
      errorMessage = Object.values(errors).flat().join("\n");
    }

    Swal.fire({
      icon: "error",
      title: "Upload Failed",
      text: errorMessage,
    });
  } finally {
    loading.value = false;
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
    category_id: "",
  };
  videoName.value = "";
  videoPreview.value = "";
  thumbnailName.value = "";
  thumbnailPreview.value = "";
  youtubeVideoId.value = "";
};

const videoInput = ref(null);
const thumbnailInput = ref(null);

const handleVideoDrop = (event) => {
  const file = event.dataTransfer.files[0];
  if (!file || !file.type.startsWith("video/")) {
    Swal.fire("Oops!", "Please upload a video file only.", "error");
    return;
  }
  
  if (file.size > 100 * 1024 * 1024) {
    Swal.fire("Oops!", "Video file size should not exceed 100MB.", "error");
    return;
  }

  // Explicitly create new File object
  form.value.video = new File([file], file.name, {
    type: file.type,
    lastModified: file.lastModified,
  });
  form.value.link_youtube = "";
  videoName.value = file.name;
  youtubeVideoId.value = "";

  const reader = new FileReader();
  reader.onload = (e) => {
    videoPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const handleThumbnailDrop = (event) => {
  const file = event.dataTransfer.files[0];
  if (!file || !file.type.startsWith("image/")) {
    Swal.fire("Oops!", "Please upload an image file for thumbnail.", "error");
    return;
  }

  if (file.size > 10 * 1024 * 1024) {
    Swal.fire("Oops!", "Thumbnail file size should not exceed 10MB.", "error");
    return;
  }

  // Explicitly create new File object
  form.value.thumbnail = new File([file], file.name, {
    type: file.type,
    lastModified: file.lastModified,
  });
  thumbnailName.value = file.name;

  const reader = new FileReader();
  reader.onload = (e) => {
    thumbnailPreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

const removeVideo = () => {
  form.value.video = null;
  form.value.link_youtube = "";
  videoName.value = "";
  videoPreview.value = "";
  youtubeVideoId.value = "";
  if (videoInput.value) {
    videoInput.value.value = "";
  }
};

const removeThumbnail = () => {
  form.value.thumbnail = null;
  thumbnailName.value = "";
  thumbnailPreview.value = "";
  if (thumbnailInput.value) {
    thumbnailInput.value.value = "";
  }
};

// Update the watch function
watch(() => form.value.link_youtube, async (newValue) => {
  if (newValue) {
    const videoId = getYoutubeVideoId(newValue);
    if (videoId) {
      youtubeVideoId.value = videoId;
      videoPreview.value = "";
      form.value.video = null; // Clear video file when using YouTube
      videoName.value = `YouTube Video (${videoId})`;
      
      if (videoInput.value) {
        videoInput.value.value = "";
      }
    } else {
      Swal.fire({
        icon: "error",
        title: "Invalid YouTube URL",
        text: "Please enter a valid YouTube video URL",
      });
      form.value.link_youtube = "";
      youtubeVideoId.value = "";
      videoName.value = "";
    }
  } else {
    youtubeVideoId.value = "";
    videoName.value = "";
  }
});

// Category handling
const categories = ref([]);
onMounted(async () => {
  if (form.value.video) {
    console.log('Video file:', form.value.video);
    console.log('Video file is File?', form.value.video instanceof File);
  }
  if (form.value.thumbnail) {
    console.log('Thumbnail file:', form.value.thumbnail);
    console.log('Thumbnail file is File?', form.value.thumbnail instanceof File);
  }
  
  try {
    const response = await axios.get("/api/categories");
    categories.value = response.data.data || [];
    categories.value.sort((a, b) => a.category_name.localeCompare(b.category_name));
  } catch (error) {
    console.error("Error fetching categories:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Failed to load categories. Please refresh the page.",
    });
  }
});
</script>