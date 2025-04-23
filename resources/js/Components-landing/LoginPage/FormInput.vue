<template>
    <div class="flex items-center justify-between w-full bg-white rounded-full py-3 px-6 shadow-sm">
        <label :for="id" class="sr-only">{{ label }}</label>
        <input
            :type="type"
            :id="id"
            :placeholder="label"
            class="bg-transparent w-full text-gray-600 placeholder-gray-500 text-base focus:outline-none uppercase"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <img
            :src="iconSrc"
            :alt="`${label} icon`"
            class="ml-2 h-6 w-6 flex-shrink-0"
        />
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

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
