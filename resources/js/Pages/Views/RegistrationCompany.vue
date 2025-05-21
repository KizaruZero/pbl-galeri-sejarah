<template>
    <div
        class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-100"
    >
        <div
            class="max-w-2xl w-full space-y-8 bg-white p-8 rounded-xl shadow-lg"
        >
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Welcome to Installation
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Let's set up your Galeri Sejarah application
                </p>
            </div>

            <form @submit.prevent="submitForm" class="mt-8 space-y-6">
                <!-- Status Messages -->
                <div
                    v-if="error"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert"
                >
                    <span class="block sm:inline">{{ error }}</span>
                </div>

                <div
                    v-if="success"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert"
                >
                    <span class="block sm:inline">{{ success }}</span>
                </div>

                <div
                    v-if="loading"
                    class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4"
                    role="alert"
                >
                    <div class="flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <span
                            >Installing application... This may take a few
                            minutes. Please don't close this page.</span
                        >
                    </div>
                </div>

                <!-- Application Settings -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Application Settings
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Application Name</label
                            >
                            <input
                                v-model="form.app_name"
                                type="text"
                                required
                                :disabled="loading"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Application URL</label
                            >
                            <input
                                v-model="form.app_url"
                                type="url"
                                required
                                :disabled="loading"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Application Email</label
                            >
                            <input
                                v-model="form.app_email"
                                type="email"
                                required
                                :disabled="loading"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Application Phone</label
                            >
                            <input
                                v-model="form.app_phone"
                                type="tel"
                                :disabled="loading"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Application Address</label
                            >
                            <textarea
                                v-model="form.app_address"
                                :disabled="loading"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Installing....
                        </span>
                        <span v-else>Install Application</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "InstallationForm",
    data() {
        return {
            loading: false,
            error: null,
            success: null,
            form: {
                app_name: "Galeri Sejarah",
                app_url: window.location.origin,
                app_email: "",
                app_phone: "",
                app_address: "",
            },
        };
    },

    methods: {
        async submitForm() {
            this.loading = true;
            this.error = null;
            this.success = null;

            try {
                const response = await axios.post("/install", this.form, {
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    timeout: 300000, // 5 minutes timeout
                });

                if (response.data.success) {
                    this.success = response.data.message;
                    if (response.data.reload) {
                        window.location.href = "/login"; // Full page reload
                    }
                } else {
                    this.error =
                        response.data.message ||
                        "Installation failed. Please try again.";
                }
            } catch (error) {
                this.handleError(error);
            } finally {
                this.loading = false;
            }
        },
        handleError(error) {
            if (error.response) {
                // Server responded with error
                if (error.response.data.errors) {
                    // Laravel validation errors
                    const errors = Object.values(
                        error.response.data.errors
                    ).flat();
                    this.error = errors.join(", ");
                } else {
                    this.error =
                        error.response.data.error ||
                        error.response.data.message ||
                        "Installation failed";
                }
            } else if (error.request) {
                // Network error
                this.error =
                    "Network error. The installation might still be running. Please wait and check if the application is accessible.";
            } else {
                // Other error
                this.error =
                    error.message || "Installation failed. Please try again.";
            }
        },
    },
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
