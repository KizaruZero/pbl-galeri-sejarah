<template>
    <article
        class="overflow-hidden bg-zinc-200 dark:bg-zinc-900 rounded-xl shadow-md transition-all duration-300 hover:scale-[1.02] hover:shadow-lg cursor-pointer"
        @click="$emit('click')"
    >
        <!-- Image Container -->
        <div
            class="card relative w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px]"
        >
            <img
                :src="imageUrl"
                :alt="title"
                @error="handleAvatarError"
                class="w-full h-full object-cover rounded-t-xl"
            />

            <!-- User Info Overlay -->
            <div
                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4"
            >
                <div class="flex items-center gap-3">
                    <!-- User Avatar -->
                    <div
                        class="w-8 h-8 rounded-full overflow-hidden bg-gray-700"
                    >
                        <img
                            :src="userAvatar"
                            :alt="userName"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <!-- User Name -->
                    <span class="text-white text-sm font-medium">{{
                        userName
                    }}</span>
                </div>

                <!-- Views and Likes -->
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1 text-white/80 text-xs">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                        <span>{{ viewsCount }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-white/80 text-xs">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                            />
                        </svg>
                        <span>{{ likesCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Content -->
        <div class="p-5 text-center">
            <h3
                class="font-semibold text-black dark:text-white leading-snug mb-2"
                :class="titleClass"
            >
                {{ title || "Untitled" }}
            </h3>
            <p
                v-if="description"
                class="text-sm text-black dark:text-white leading-relaxed line-clamp-2"
            >
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
    userId: {
        type: Number,
        required: true,
    },
    userName: {
        type: String,
        default: "",
    },
    userAvatar: {
        type: String,
        default: "",
    },
    viewsCount: {
        type: Number,
        default: 0,
    },
    likesCount: {
        type: Number,
        default: 0,
    },
});

// Handle avatar image error
const handleAvatarError = (e) => {
    e.target.src = "/js/Assets/default-photo.jpg";
};

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
</script>

<style scoped>
.card {
    position: relative;
}
</style>
