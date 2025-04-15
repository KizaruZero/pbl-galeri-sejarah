<template>
    <div class="w-[33%] max-md:ml-0 max-md:w-full">
        <div class="grow max-md:mt-10">
            <template v-for="(image, index) in images" :key="index">
                <img :src="image.src" :class="[
                    'object-contain w-full',
                    image.aspectRatio,
                    index > 0 ? image.marginTop : '',
                ]" :alt="image.alt || ''" />
            </template>
        </div>
    </div>
</template>

<script setup>
    import {
        defineProps
    } from "vue";

    defineProps({
        images: {
            type: Array,
            required: true,
            validator: (value) => value.every((image) => image.src && image.aspectRatio),
        },
    });
</script>