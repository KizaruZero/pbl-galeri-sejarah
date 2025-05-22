<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Galeri Sejarah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
    <div id="app" class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Welcome to Installation
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Let's set up your Galeri Sejarah application
                </p>
            </div>

            <installation-form></installation-form>
        </div>
    </div>

    <script>
        // Configure axios defaults
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.baseURL = window.location.origin;

        Vue.component('installation-form', {
            template: `
                <form @submit.prevent="submitForm" class="mt-8 space-y-6">
                    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline" v-text="error"></span>
                    </div>
                    
                    <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline" v-text="success"></span>
                    </div>

                    <div v-if="loading" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            <span>Installing application... This may take a few minutes. Please don't close this page.</span>
                        </div>
                    </div>

                    <div class="rounded-md shadow-sm space-y-4">
                        <!-- Application Settings -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Application Settings</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Application Name</label>
                                    <input v-model="form.app_name" type="text" required :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Application URL</label>
                                    <input v-model="form.app_url" type="url" required :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Application Email</label>
                                    <input v-model="form.app_email" type="email" required :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Application Phone</label>
                                    <input v-model="form.app_phone" type="tel" :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Application Address</label>
                                    <textarea v-model="form.app_address" :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Database Settings -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Database Settings</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Database Name</label>
                                    <input v-model="form.db_name" type="text" required :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Database Username</label>
                                    <input v-model="form.db_user" type="text" required :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Database Password</label>
                                    <input v-model="form.db_pass" type="password" :disabled="loading" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" :disabled="loading" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="loading">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Installing...
                            </span>
                            <span v-else>Install Application</span>
                        </button>
                    </div>
                </form>
            `,
            data() {
                return {
                    loading: false,
                    error: null,
                    success: null,
                    form: {
                        app_name: 'Galeri Sejarah',
                        app_url: window.location.origin,
                        app_email: '',
                        app_phone: '',
                        app_address: '',
                        db_name: '',
                        db_user: 'root',
                        db_pass: ''
                    }
                }
            },
            methods: {
                async submitForm() {
                    this.loading = true;
                    this.error = null;
                    this.success = null;

                    try {
                        // Convert reactive Vue data to plain object
                        const formData = {
                            app_name: this.form.app_name,
                            app_url: this.form.app_url,
                            app_email: this.form.app_email,
                            app_phone: this.form.app_phone,
                            app_address: this.form.app_address,
                            db_name: this.form.db_name,
                            db_user: this.form.db_user,
                            db_pass: this.form.db_pass
                        };

                        console.log('Submitting form data:', formData);

                        const response = await axios.post('/install', formData, {
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            timeout: 300000 // 5 minutes timeout
                        });

                        console.log('Response:', response);

                        if (response.data.success) {
                            this.success = response.data.message;
                            setTimeout(() => {
                                window.location.href = response.data.redirect || '/login';
                            }, 3000);
                        } else {
                            this.error = response.data.message || 'Installation failed. Please try again.';
                        }

                    } catch (error) {
                        console.error('Installation error:', error);

                        if (error.response) {
                            // Server responded with error
                            if (error.response.data.errors) {
                                // Laravel validation errors
                                const errors = Object.values(error.response.data.errors).flat();
                                this.error = errors.join(', ');
                            } else {
                                this.error = error.response.data.error || error.response.data.message ||
                                    'Installation failed';
                            }
                        } else if (error.request) {
                            // Network error
                            this.error =
                                'Network error. The installation might still be running. Please wait a moment and check if the application is accessible.';
                        } else {
                            // Other error
                            this.error = error.message || 'Installation failed. Please try again.';
                        }
                    } finally {
                        this.loading = false;
                    }
                }
            }
        });

        new Vue({
            el: '#app'
        });
    </script>
</body>

</html>
