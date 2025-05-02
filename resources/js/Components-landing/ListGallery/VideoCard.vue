<template>
  <article class="overflow-hidden bg-black rounded-xl shadow-md transition-all duration-300 
           hover:scale-[1.02] hover:shadow-lg cursor-pointer"
    @click="$emit('click')">
    
    <!-- Image Container -->
    <div class="card relative w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px]">
      <img :src="video_url" :alt="title"
        class="w-full h-full object-cover rounded-t-xl"
        @error="handleVideoError"
      />
    </div>
    
    <!-- Rest of your card content -->
    <div class="p-5 text-center">
      <h3 class="font-semibold text-white leading-snug mb-2" :class="titleClass">
        {{ title || 'Untitled' }}
      </h3>
      <p v-if="description" class="text-sm text-white leading-relaxed line-clamp-2">
        {{ description }}
      </p>
      <p v-else class="text-sm text-gray-400 italic">
        No description available
      </p>
    </div>
  </article>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
        video_url: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            required: true,
        },
        titleSize: {
            type: String,
            default: "lg",
            validator: (value) => ["xs", "sm", "base", "lg", "xl"].includes(value),
        },
});

const titleClass = computed(() => {
        return {
            xs: "text-sm",
            sm: "text-base",
            base: "text-lg",
            lg: "text-xl",
            xl: "text-2xl",
        } [props.titleSize] || "text-lg";
    });

</script>