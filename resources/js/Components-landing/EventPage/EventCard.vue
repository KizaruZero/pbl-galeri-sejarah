<template>
  <article
    class="overflow-hidden bg-black rounded-xl shadow-md transition-all duration-300 
           hover:scale-[1.02] hover:shadow-lg"
  >
    <img
      :src="imageUrl"
      :alt="title"
      class="w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px] object-cover rounded-t-xl"
    />
    <div class="p-5 text-center">
      <h3 class="font-semibold text-white leading-snug mb-2" :class="titleClass">
        {{ title }}
      </h3>
      <p class="text-sm text-white leading-relaxed">
        {{ description }}
      </p>
    </div>
  </article>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  imageUrl: String,
  title: String,
  description: String,
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
  }[props.titleSize] || "text-lg";
});
</script>
