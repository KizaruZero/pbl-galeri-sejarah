<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" v-if="show">
        <looping-rhombuses-spinner :animation-duration="2500" :rhombus-size="15" color="#ffffff" />
    </div>
</template>

<script setup>
    import {
        LoopingRhombusesSpinner
    } from 'epic-spinners'
    import {
        ref,
        onMounted
    } from 'vue'
    import {
        router
    } from '@inertiajs/vue3'

    const show = ref(false)

    onMounted(() => {
        router.on('start', () => show.value = true)
        router.on('finish', () => show.value = false)
    })

</script>
