<template>
    <article
        class="overflow-hidden bg-black rounded-xl shadow-md transition-all duration-300 hover:scale-[1.02] hover:shadow-lg cursor-pointer"
        @click="$emit('click')"
    >
        <!-- Image Container -->
        <div
            class="card relative w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px]"
        >
            <img
                :src="imageUrl"
                :alt="title"
                class="w-full h-full object-cover rounded-t-xl"
                @error="handleImageError"
            />
        </div>

        <!-- Rest of your card content -->
        <div class="p-5 text-center">
            <h3
                class="font-semibold text-white leading-snug mb-2"
                :class="titleClass"
            >
                {{ title || "Untitled" }}
            </h3>
            <p
                v-if="description"
                class="text-sm text-white leading-relaxed line-clamp-2"
            >
                {{ description }}
            </p>
            <p v-else class="text-sm text-gray-400 italic">
                No description available
            </p>
            <div class="flex flex-wrap justify-center gap-2 mb-2">
                <p v-if="date_start" class="text-xs text-gray-300">
                    {{ formatDate(date_start) }}
                </p>
                <p v-if="date_end" class="text-xs text-gray-300">
                    - {{ formatDate(date_end) }}
                </p>
            </div>

            <p v-if="location" class="text-xs text-gray-300 mb-2">
                {{ location }}
            </p>

            <div v-if="contactPerson" class="text-xs text-gray-300 mb-2">
                Contact: {{ contactPerson }}
            </div>

            <!-- Social Links -->
            <div class="flex justify-center gap-3 mt-3">
                <a
                    v-if="instagramUrl && instagramUrl.trim().length > 0"
                    :href="instagramUrl"
                    target="_blank"
                    class="text-pink-500 hover:text-pink-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                        />
                    </svg>
                </a>
                <a
                    v-if="youtubeUrl && youtubeUrl.trim().length > 0"
                    :href="youtubeUrl"
                    target="_blank"
                    class="text-red-500 hover:text-red-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                        />
                    </svg>
                </a>
                <a
                    v-if="websiteUrl && websiteUrl.trim().length > 0"
                    :href="websiteUrl"
                    target="_blank"
                    class="text-blue-400 hover:text-blue-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                        />
                    </svg>
                </a>
                <a
                    v-if="googleMapsUrl && googleMapsUrl.trim().length > 0"
                    :href="googleMapsUrl"
                    target="_blank"
                    class="text-green-500 hover:text-green-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"
                        />
                    </svg>
                </a>
            </div>
        </div>
    </article>
</template>

<script setup>
import { computed, ref } from "vue";

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
    date_start: {
        type: String,
        default: "",
    },
    date_end: {
        type: String,
        default: "",
    },
    instagramUrl: {
        type: String,
        default: "",
    },
    youtubeUrl: {
        type: String,
        default: "",
    },
    websiteUrl: {
        type: String,
        default: "",
    },
    contactPerson: {
        type: String,
        default: "",
    },
    location: {
        type: String,
        default: "",
    },
    googleMapsUrl: {
        type: String,
        default: "",
    },
    titleSize: {
        type: String,
        default: "lg",
        validator: (value) => ["xs", "sm", "base", "lg", "xl"].includes(value),
    },
    showDebugInfo: {
        type: Boolean,
        default: false,
    },
});

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

const formatDate = (dateString) => {
    if (!dateString) return "";
    const options = {
        year: "numeric",
        month: "short",
        day: "numeric",
    };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const handleImageError = (e) => {
    e.target.src = "/js/Assets/default-photo.jpg";
};
</script>
