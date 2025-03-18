<template>
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
      <p class="text-white text-2xl font-semibold">
        Loading<span class="dot1">.</span><span class="dot2">.</span><span class="dot3">.</span>
      </p>
    </div>
</template>
  
  <style scoped>
  @keyframes dotAnimation {
    0% { opacity: 0; }
    33% { opacity: 1; }
    66% { opacity: 0; }
  }
  .dot1 { animation: dotAnimation 1.5s infinite; }
  .dot2 { animation: dotAnimation 1.5s infinite 0.2s; }
  .dot3 { animation: dotAnimation 1.5s infinite 0.4s; }
  </style>
  
  <script setup>
  defineProps({
    isLoading: Boolean,
  });
  </script>
  