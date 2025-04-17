<!-- <template>
    <section class="px-20 pt-11 pb-5 w-full bg-black max-md:px-5 max-md:max-w-full">
        <div class="flex gap-5 max-md:flex-col">
            <GalleryColumn :images="firstColumnImages" />
            <GalleryColumn :images="secondColumnImages" />
            <GalleryColumn :images="thirdColumnImages" />
        </div>
    </section>
</template>

<script>
    import GalleryColumn from "@/Components-landing/GalleryColumn.vue";

    export default {
        name: "ImageGallery",
        components: {
            GalleryColumn,
        },
        data() {
            return {
                firstColumnImages: [{
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/d7260aac685e61947191f18b4cf411e594f892d9188d25d0fa47b26526c21632?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[0.55]",
                    },
                    {
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/243121631cf3be049eec15e3d96b08a1f53a2162ca0ff4fdd82eda8fbd40e220?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[1.35]",
                        marginTop: "mt-6",
                    },
                ],
                secondColumnImages: [{
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/1599eb55718778980605f80b0e499339a7b43dec1d5d06c7ff07ebd8873dcae7?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[0.76]",
                    },
                    {
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/38b70a1f4adba7e23b30f66bac719c6e63de893995deabb794461fcfe3c32265?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[0.79]",
                        marginTop: "mt-3.5",
                    },
                ],
                thirdColumnImages: [{
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/e500a86a9ee6b3b987abdfdf2529a869eb451fad7cdfc55b13fa0e2ffa1cb10b?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[1.22]",
                    },
                    {
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/487fe555b476520c3867cb24939b559619b95c94cc478aedc00c42f02aa8456b?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[1.22]",
                        marginTop: "mt-1",
                    },
                    {
                        src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/575d9d1d1885c52de693ef10a84fb9f862e3c528a1957bffc9e03ee971cab9ff?placeholderIfAbsent=true",
                        aspectRatio: "aspect-[1.04]",
                        marginTop: "mt-1",
                    },
                ],
            };
        },
    };
</script> -->

<template>
    <article
      class="overflow-hidden bg-black rounded-xl shadow-md cursor-pointer 
             transition-all duration-300 hover:scale-105 hover:shadow-lg"
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
  