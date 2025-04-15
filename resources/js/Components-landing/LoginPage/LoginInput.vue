<template>
    <div
        class="flex gap-5 justify-between self-stretch px-9 py-5 mt-14 whitespace-nowrap rounded-3xl bg-zinc-300 text-zinc-600 max-md:px-5 max-md:mt-10 max-md:max-w-full">
        <label :for="id" class="sr-only">{{ label }}</label>
        <input :type="type" :id="id" :placeholder="label" class="bg-transparent outline-none w-full text-xl uppercase"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        <img :src="iconSrc" :alt="`${label} icon`" class="object-contain shrink-0 aspect-square w-[30px]" />
    </div>
</template>

<script setup>
    defineProps({
        label: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: "text",
        },
        id: {
            type: String,
            required: true,
        },
        iconSrc: {
            type: String,
            required: true,
        },
        modelValue: {
            type: String,
            required: true,
        },
    });

    defineEmits(["update:modelValue"]);
</script>