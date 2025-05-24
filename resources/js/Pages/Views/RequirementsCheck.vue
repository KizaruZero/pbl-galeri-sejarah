<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    System Requirements Check
                </h1>
                <p class="text-gray-600">
                    Verify your system meets all requirements for optimal
                    performance
                </p>
            </div>

            <!-- Version Requirements -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Version Requirements
                </h2>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <div
                        v-for="(version, name) in requirements.versions"
                        :key="name"
                        class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="text-sm font-medium text-gray-700 capitalize"
                                >{{ name }}</span
                            >
                            <span
                                :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    version.supported
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{
                                    version.supported
                                        ? "Supported"
                                        : "Not Supported"
                                }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500"
                                    >Current Version:</span
                                >
                                <span class="font-medium text-gray-900">{{
                                    version.current || "Not Found"
                                }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500"
                                    >Minimum Required:</span
                                >
                                <span class="font-medium text-gray-900">{{
                                    version.minimum
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PHP Extensions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    PHP Extensions
                </h2>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <div
                        v-for="req in phpRequirements"
                        :key="req.name"
                        class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{
                                req.name
                            }}</span>
                            <div
                                :class="[
                                    'flex items-center',
                                    req.status
                                        ? 'text-green-600'
                                        : 'text-red-600',
                                ]"
                            >
                                <span v-if="req.status" class="text-green-600">
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        ></path>
                                    </svg>
                                </span>
                                <span v-else class="text-red-600">
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Folder Permissions -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Folder Permissions
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                        v-for="perm in requirements.permissions"
                        :key="perm.folder"
                        class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">{{
                                perm.folder
                            }}</span>
                            <span
                                :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    perm.status
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{ perm.status ? "Correct" : "Incorrect" }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Current:</span>
                                <span class="font-medium text-gray-900">{{
                                    perm.current
                                }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Required:</span>
                                <span class="font-medium text-gray-900">{{
                                    perm.required
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

const requirements = ref({
    requirements: [],
    permissions: [],
    versions: {
        php: { current: null, minimum: null, supported: false },
        composer: { current: null, minimum: null, supported: false },
        filament: { current: null, minimum: null, supported: false },
        livewire: { current: null, minimum: null, supported: false },
        node: { current: null, minimum: null, supported: false },
        npm: { current: null, minimum: null, supported: false },
    },
});

const phpRequirements = computed(() => {
    return requirements.value.requirements.filter((req) => req.type === "PHP");
});

const fetchRequirements = async () => {
    try {
        const response = await axios.get("/requirements");
        requirements.value = response.data;
    } catch (error) {
        console.error("Error fetching requirements:", error);
    }
};

onMounted(() => {
    fetchRequirements();
});
</script>

<style scoped>
.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-blue-100 {
    background-color: #dbeafe;
}

.text-blue-800 {
    color: #1e40af;
}

.border-blue-200 {
    border-color: #bfdbfe;
}
</style>
