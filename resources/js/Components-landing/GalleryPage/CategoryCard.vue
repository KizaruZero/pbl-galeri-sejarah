<template>
    <article class="overflow-hidden bg-zinc-200 dark:bg-zinc-900 rounded-xl shadow-md transition-all duration-300 
             hover:scale-[1.02] hover:shadow-lg cursor-pointer" @click="$emit('click')">

        <!-- Image Container -->
        <div class="card relative w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px]">
            <img :src="categoryImage" :alt="categoryName" class="w-full h-full object-cover rounded-t-xl"
                @error="handleImageError" />
        </div>

        <!-- Rest of your card content -->
        <div class="p-5 text-center">
            <h3 class="font-semibold text-black dark:text-white leading-snug mb-2" :class="titleClass">
                {{ categoryName || 'Untitled' }}
            </h3>
            <p v-if="categoryDescription" class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-2">
                {{ categoryDescription }}
            </p>
            <p v-else class="text-sm text-gray-400 italic">
                No description available
            </p>
        </div>
    </article>
</template>

<script setup>
    import {
        computed
    } from "vue";

    const props = defineProps({
        categoryImage: {
            type: String,
            required: true,
        },
        categoryName: {
            type: String,
            required: true,
        },
        categoryDescription: {
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
