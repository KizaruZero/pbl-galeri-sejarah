<template>
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Application Configuration
        </h2>

        <form @submit.prevent="submitForm" class="space-y-4">
            <!-- App Name -->
            <div>
                <label
                    for="app_name"
                    class="block text-sm font-medium text-gray-700"
                    >App Name</label
                >
                <input
                    v-model="form.app_name"
                    type="text"
                    id="app_name"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    required
                />
            </div>

            <!-- App URL -->
            <div>
                <label
                    for="app_url"
                    class="block text-sm font-medium text-gray-700"
                    >App URL</label
                >
                <input
                    v-model="form.app_url"
                    type="url"
                    id="app_url"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    required
                />
            </div>

            <!-- Database Configuration -->
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Database Configuration
                </h3>

                <!-- DB Name -->
                <div class="mt-4">
                    <label
                        for="db_name"
                        class="block text-sm font-medium text-gray-700"
                        >Database Name</label
                    >
                    <input
                        v-model="form.db_name"
                        type="text"
                        id="db_name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    />
                </div>

                <!-- DB User -->
                <div class="mt-4">
                    <label
                        for="db_user"
                        class="block text-sm font-medium text-gray-700"
                        >Database User</label
                    >
                    <input
                        v-model="form.db_user"
                        type="text"
                        id="db_user"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    />
                </div>

                <!-- DB Password -->
                <div class="mt-4">
                    <label
                        for="db_pass"
                        class="block text-sm font-medium text-gray-700"
                        >Database Password</label
                    >
                    <input
                        v-model="form.db_pass"
                        type="password"
                        id="db_pass"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="loading">Processing...</span>
                    <span v-else>Save Configuration</span>
                </button>
            </div>

            <!-- Success Message -->
            <div
                v-if="success"
                class="p-4 bg-green-100 text-green-700 rounded-md"
            >
                {{ successMessage }}
            </div>

            <!-- Error Message -->
            <div v-if="error" class="p-4 bg-red-100 text-red-700 rounded-md">
                {{ errorMessage }}
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                app_name: "",
                app_url: "",
                db_name: "",
                db_user: "",
                db_pass: "",
            },
            loading: false,
            success: false,
            error: false,
            successMessage: "",
            errorMessage: "",
        };
    },
    methods: {
        async submitForm() {
            this.loading = true;
            this.success = false;
            this.error = false;

            try {
                // Get fresh CSRF token before submitting
                await axios.get("/sanctum/csrf-cookie");

                const response = await axios.post(
                    "/change-database",
                    this.form
                );

                if (response.data.reload_required) {
                    // Show success message briefly
                    this.success = true;
                    this.successMessage = response.data.message;

                    // Store the redirect path in sessionStorage
                    sessionStorage.setItem(
                        "post_reload_redirect",
                        "/registration-company"
                    );

                    // Wait a moment before reloading
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } catch (error) {
                sessionStorage.setItem(
                        "post_reload_redirect",
                        "/registration-company"
                    );

                    // Wait a moment before reloading
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                this.loading = false;
            }
        },
    },
    mounted() {
        // Check if we need to redirect after reload
        const redirectPath = sessionStorage.getItem("post_reload_redirect");
        if (redirectPath) {
            sessionStorage.removeItem("post_reload_redirect");
            // Get fresh CSRF token before redirecting
            axios.get("/sanctum/csrf-cookie").then(() => {
                window.location.href = redirectPath;
            });
        }
    },
};
</script>
