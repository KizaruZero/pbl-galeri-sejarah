<template>
    <article class="overflow-hidden bg-black rounded-xl shadow-md transition-all duration-300 
           hover:scale-[1.02] hover:shadow-lg cursor-pointer" @click="$emit('click')">

        <!-- Image Container -->
        <div class="card relative w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px]">
            <img :src="imageUrl" :alt="title" class="w-full h-full object-cover rounded-t-xl"
                />

            <!-- User Info Overlay -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                <div class="flex items-center gap-3">
                    <!-- User Avatar -->
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-700">
                        <img :src="userAvatar" :alt="userName" class="w-full h-full object-cover"
                            />
                    </div>
                    <!-- User Name -->
                    <span class="text-white text-sm font-medium">{{ userName }}</span>
                </div>
            </div>
        </div>

        <!-- Card Content -->
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
    import {
        computed
    } from "vue";

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
        // Add new props for user info
        userId: {
            type: Number,
            required: true
        },
        userName: {
            type: String,
            default: ""
        },
        userAvatar: {
            type: String,
            default: "" // Provide a default avatar path
        }
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

<style scoped>
    .card {
        position: relative;
    }

</style>
