<template>
    <section class="p-10 mx-auto my-0 w-full bg-black max-w-[1200px] max-sm:p-5">
        <h2 class="mb-8 text-3xl font-medium text-white max-sm:mb-5 max-sm:text-2xl">
            Trending Photo
        </h2>
        <div class="flex gap-5 w-full max-md:flex-col max-sm:gap-2.5">
            <div class="flex flex-col gap-5 w-[33%] max-md:w-full">
                <figure v-for="(photo, index) in leftColumnPhotos" :key="index"
                    class="overflow-hidden w-full rounded-lg">
                    <img :src="photo.src" :alt="photo.alt" class="object-cover size-full" />
                </figure>
            </div>
            <div class="flex flex-col gap-5 w-[33%] max-md:w-full">
                <figure v-for="(photo, index) in centerColumnPhotos" :key="index"
                    class="overflow-hidden w-full rounded-lg">
                    <img :src="photo.src" :alt="photo.alt" class="object-cover size-full" />
                </figure>
            </div>
            <div class="flex flex-col gap-5 w-[33%] max-md:w-full">
                <figure v-for="(photo, index) in rightColumnPhotos" :key="index"
                    class="overflow-hidden w-full rounded-lg">
                    <img :src="photo.src" :alt="photo.alt" class="object-cover size-full" />
                </figure>
            </div>
        </div>
    </section>
</template>

<script setup>
    const leftColumnPhotos = [{
            src: "https://placehold.co/600x300/1a365d/1a365d",
            alt: "Palace at night",
        },
        {
            src: "https://placehold.co/600x300/234876/234876",
            alt: "Palace with reflection",
        },
        {
            src: "https://placehold.co/600x300/8b7355/8b7355",
            alt: "Historic building",
        },
    ];

    const centerColumnPhotos = [{
            src: "https://placehold.co/400x500/2d2d2d/2d2d2d",
            alt: "Historical portrait 1",
        },
        {
            src: "https://placehold.co/400x500/2d2d2d/2d2d2d",
            alt: "Historical portrait 2",
        },
    ];

    const rightColumnPhotos = [{
            src: "https://placehold.co/400x600/2d2d2d/2d2d2d",
            alt: "Historical portrait 3",
        },
        {
            src: "https://placehold.co/400x300/a8a8a8/a8a8a8",
            alt: "Modern building",
        },
    ];
</script>