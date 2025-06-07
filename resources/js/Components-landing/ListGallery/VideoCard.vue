<template>
    <article class="overflow-hidden bg-black rounded-xl shadow-md transition-all duration-300
           hover:scale-[1.02] hover:shadow-lg cursor-pointer" @click="$emit('click')">

        <!-- Image Container -->
        <div class="card relative w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[300px]">
            <img :src="thumbnailUrl" :alt="title" class="w-full h-full object-cover rounded-t-xl" />

            <!-- User Info Overlay -->
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/70 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-700 border border-white/20">
                        <img :src="userAvatar" :alt="userName" class="w-full h-full object-cover" />
                    </div>
                    <span class="text-white text-sm truncate">{{ userName }}</span>
                </div>
                <!-- Views and Likes -->
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1 text-white/80 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ viewsCount }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-white/80 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>{{ likesCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rest of your card content -->
        <div class="p-5 text-center">
            <h3 class="font-semibold text-white leading-snug mb-2 line-clamp-2" :class="titleClass">
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
        thumbnailUrl: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            default: "",
        },
        titleSize: {
            type: String,
            default: "lg",
            validator: (value) => ["xs", "sm", "base", "lg", "xl"].includes(value),
        },
        duration: {
            type: String,
            default: "00:00"
        },
        views: {
            type: Number,
            default: 0
        },
        userName: {
            type: String,
            default: "Anonymous"
        },
        userAvatar: {
            type: String,
            default: "/js/Assets/default-avatar.jpg"
        },
        createdAt: {
            type: String,
            default: ""
        },
        viewsCount: {
            type: Number,
            default: 0
        },
        likesCount: {
            type: Number,
            default: 0
        }
    });

    const emit = defineEmits(['click']);

    const titleClass = computed(() => {
        return {
            xs: "text-sm",
            sm: "text-base",
            base: "text-lg",
            lg: "text-xl",
            xl: "text-2xl",
        } [props.titleSize] || "text-lg";
    });

    const formatDuration = (duration) => {
        if (!duration) return '00:00';
        if (duration.includes(':')) return duration;
        const seconds = parseInt(duration);
        if (isNaN(seconds)) return '00:00';

        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    const formatRelativeDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));

        if (days < 1) return 'Today';
        if (days === 1) return 'Yesterday';
        if (days < 7) return `${days} days ago`;
        if (days < 30) return `${Math.floor(days / 7)} weeks ago`;
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric'
        });
    };

</script>
