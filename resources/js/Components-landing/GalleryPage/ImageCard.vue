<template>
  <article
    class="overflow-hidden bg-black rounded-xl shadow-md cursor-pointer transition-all duration-300 hover:scale-105 hover:shadow-lg"
    @click="handleClick"
  >
    <img
      :src="imageUrl"
      :alt="title"
      class="w-full h-[240px] md:h-[280px] lg:h-[320px] object-cover rounded-t-xl"
    />
    <div class="p-5 text-center">
      <h3 class="font-semibold text-white leading-snug" :class="titleClass">
        {{ title }}
      </h3>
      <p class="mt-2 text-sm text-white leading-relaxed">
        {{ description }}
      </p>
    </div>
  </article>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  imageUrl: {
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
  videoUrl: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["play-video"]);

const titleClass = computed(() => {
  return (
    {
      xs: "text-sm",
      sm: "text-base",
      base: "text-lg",
      lg: "text-xl",
      xl: "text-2xl",
    }[props.titleSize] || "text-lg"
  );
});

const handleClick = () => {
  if (props.videoUrl) {
    emit("play-video", props.videoUrl);
  }
};
</script>
