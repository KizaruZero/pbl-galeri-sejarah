<template>
  <div>
    <!-- Floating Add Button di Pojok Kanan Bawah -->
    <button
      @click="toggleModal"
      class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 w-12 h-12 sm:w-14 sm:h-14 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg hover:bg-blue-700 transition-colors duration-300"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
        class="w-6 h-6 sm:w-8 sm:h-8"
      >
        <path
          fill-rule="evenodd"
          d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
          clip-rule="evenodd"
        />
      </svg>
    </button>

    <!-- Modal -->
    <div
      v-if="modelValue"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
    >
      <div
        class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md xl:max-w-2xl 2xl:max-w-xl mx-4"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg sm:text-xl xl:text-2xl font-semibold text-white">
            Upload Media
          </h2>
          <button @click="toggleModal" class="text-gray-400 hover:text-white">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 sm:h-6 sm:w-6 xl:h-7 xl:w-7"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:gap-6">
          <button
            @click="$emit('upload-photo')"
            class="flex flex-col items-center justify-center p-3 sm:p-4 xl:p-5 bg-[#2a2a2a] rounded-lg hover:bg-[#3a3a3a] transition-colors"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-8 w-8 sm:h-10 sm:w-10 xl:h-12 xl:w-12 text-blue-400 mb-1 sm:mb-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
            <span class="text-sm sm:text-base xl:text-lg text-white">Foto</span>
          </button>

          <button
            @click="$emit('upload-video')"
            class="flex flex-col items-center justify-center p-3 sm:p-4 xl:p-5 bg-[#2a2a2a] rounded-lg hover:bg-[#3a3a3a] transition-colors"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-8 w-8 sm:h-10 sm:w-10 xl:h-12 xl:w-12 text-red-400 mb-1 sm:mb-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 10l4.553-2.276A1 1 0 01221 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
              />
            </svg>
            <span class="text-sm sm:text-base xl:text-lg text-white">Video</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:modelValue", "upload-photo", "upload-video"]);

const toggleModal = () => {
  emit("update:modelValue", !props.modelValue);
};
</script>
