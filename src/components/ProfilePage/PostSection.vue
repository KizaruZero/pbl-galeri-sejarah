<template>
    <section class="flex flex-col items-center justify-center bg-black text-white px-4 sm:px-6 md:px-8 mb-20">
        <!-- Header Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/0ce39460f5c1fcc7de01dc86abe1b0a179e02b667addd787dc679416c23e4fb7?placeholderIfAbsent=true"
            alt="Gallery header image" class="object-contain w-full max-w-[1299px] my-4" />

        <!-- Navigation Tabs -->
        <div class="flex content-center justify-center gap-5 my-5 w-full max-w-[990px]">
            <button @click="activeTab = 'posts'" :class="{'border-b-2 border-white': activeTab === 'posts'}"
                class="px-3 sm:px-4 py-2 text-sm sm:text-base transition-all">
                Posts
            </button>
            <button @click="activeTab = 'saved'" :class="{'border-b-2 border-white': activeTab === 'saved'}"
                class="px-3 sm:px-4 py-2 text-sm sm:text-base transition-all">
                Saved
            </button>
        </div>

        <!-- Second Header Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/0ce39460f5c1fcc7de01dc86abe1b0a179e02b667addd787dc679416c23e4fb7?placeholderIfAbsent=true"
            alt="Gallery header image" class="object-contain w-full max-w-[1299px] mb-4" />

        <!-- Grid Layout with Smaller Images -->
        <div
            class="grid grid-cols-3 gap-2 sm:gap-3 w-full max-w-[990px] mt-5">
            <div v-for="(image, index) in displayedImages" :key="index"
                class="aspect-square border border-white cursor-pointer overflow-hidden max-w-full">
                <img :src="image.url" :alt="image.alt"
                    class="object-cover w-full h-full hover:scale-105 transition-transform duration-300" />
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="displayedImages.length === 0" class="flex flex-col items-center justify-center py-10 text-center">
            <p class="text-lg mb-2">No images to display</p>
            <p class="text-sm text-gray-400">Images will appear here when available</p>
        </div>
    </section>
</template>

<script setup>
    import {
        ref,
        computed
    } from 'vue';

    const activeTab = ref('posts');

    const posts = ref([{
            url: 'https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/15d18ac9f5dc6b3c7f7490d47d0ca2cd2844e5e7cd0284150aaa860e69b50296?placeholderIfAbsent=true',
            alt: 'Gallery image 1'
        },
        {
            url: 'https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/15d18ac9f5dc6b3c7f7490d47d0ca2cd2844e5e7cd0284150aaa860e69b50296?placeholderIfAbsent=true',
            alt: 'Gallery image 2'
        },
        {
            url: 'https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/15d18ac9f5dc6b3c7f7490d47d0ca2cd2844e5e7cd0284150aaa860e69b50296?placeholderIfAbsent=true',
            alt: 'Gallery image 3'
        },
    ]);

    const saved = ref([{
        url: 'https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/15d18ac9f5dc6b3c7f7490d47d0ca2cd2844e5e7cd0284150aaa860e69b50296?placeholderIfAbsent=true',
        alt: 'Saved image 1'
    }, ]);

    const displayedImages = computed(() => activeTab.value === 'posts' ? posts.value : saved.value);
</script>