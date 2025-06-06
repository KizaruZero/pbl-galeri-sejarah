<script setup>
import { Head, usePage } from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    description: {
        type: String,
        default: "",
    },
    image: {
        type: String,
        default: "",
    },
});

const page = usePage();
const url = new URL(page.url, window.location.origin).href;
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta name="description" :content="description" v-if="description" />
        <meta property="og:title" :content="title" />
        <meta
            property="og:description"
            :content="description"
            v-if="description"
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="url" />
        <meta property="og:image" :content="image" v-if="image" />
    </Head>
</template>
