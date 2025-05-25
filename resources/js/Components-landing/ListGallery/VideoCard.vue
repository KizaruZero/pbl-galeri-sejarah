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
