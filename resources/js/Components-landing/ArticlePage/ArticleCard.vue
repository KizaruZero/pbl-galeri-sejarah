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
                :src="image_url"
                :alt="title"
                class="w-full h-full object-cover rounded-t-xl"
                @error="handleImageError"
            />
        </div>

        <!-- Rest of your card content -->
        <div class="p-5 text-center">
            <h3
                class="font-semibold text-black dark:text-white leading-snug mb-2"
                :class="titleClass"
            >
                {{ title || "Untitled" }}
            </h3>
            <p
                v-if="content"
                class="text-sm text-black dark:text-white leading-relaxed line-clamp-2"
            >
                {{ content }}
            </p>
            <p v-else class="text-sm text-gray-400 italic">
                No content available
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Views: {{ total_views || 0 }}
            </p>
        </div>
    </article>
</template>

<script setup>
import { computed } from "vue";
import { ref } from "vue";

const isDarkTheme = ref(true);
const toggleTheme = () => {
    isDarkTheme.value = !isDarkTheme.value;
};

const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    content: {
        type: String,
        required: true,
    },
    image_url: {
        type: String,
        required: true,
    },
    status: {
        type: String,
        required: false,
    },
    total_views: {
        type: Number,
        required: false,
        default: 0,
    },
    titleSize: {
        type: String,
        default: "lg",
        validator: (value) => ["xs", "sm", "base", "lg", "xl"].includes(value),
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

const handleImageError = (e) => {
    e.target.style.display = "none";
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>
