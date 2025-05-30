<template>
    <section
        class="flex flex-col justify-center w-full max-w-md px-6 py-12 bg-stone-950"
    >
        <div class="flex flex-col items-center">
            <h1 class="text-center text-4xl font-medium text-white uppercase">
                Welcome Back
            </h1>
            <h2 class="mt-1.5 text-3xl font-medium text-white uppercase">
                Login
            </h2>

            <!-- Alert for general errors -->
            <div
                v-if="form.errors.email"
                class="w-full mt-4 p-3 bg-red-500 text-white rounded-lg"
            >
                {{ form.errors.email }}
            </div>

            <form @submit.prevent="submit" class="w-full mt-8 space-y-6">
                <!-- Email Input -->
                <div>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        placeholder="Email"
                        class="w-full py-3 px-6 bg-zinc-300 text-zinc-800 rounded-3xl focus:outline-none"
                        required
                    />
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        id="password"
                        v-model="form.password"
                        placeholder="Password"
                        class="w-full py-3 px-6 bg-zinc-300 text-zinc-800 rounded-3xl focus:outline-none"
                        required
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-600"
                    >
                        <i
                            :class="
                                showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'
                            "
                        ></i>
                    </button>
                    <div
                        v-if="form.errors.password"
                        class="text-red-500 text-sm mt-1 text-left"
                    >
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="flex flex-col space-y-6">
                    <Link
                        href="/forgot-password"
                        class="self-start text-xs text-white uppercase hover:text-orange-200 transition-colors"
                    >
                        Forgot password?
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-16 py-2.5 mt-6 mx-auto text-xl text-black uppercase whitespace-nowrap rounded-3xl bg-zinc-300 w-[242px] hover:bg-zinc-400 transition-colors disabled:opacity-50"
                    >
                        {{ form.processing ? "Loading..." : "Login" }}
                    </button>

                    <!-- Google login button -->
                    <div
                        v-if="showGoogleLogin"
                        class="flex flex-col items-center"
                    >
                        <div class="text-white text-sm mb-2">- OR -</div>
                        <button
                            type="button"
                            @click="loginWithGoogle"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-gray-800 rounded-3xl w-[242px] hover:bg-gray-100 transition-colors"
                        >
                            <i class="fab fa-google"></i>
                            Login with Google
                        </button>
                    </div>
                </div>
            </form>

            <div
                class="flex gap-1.5 mt-10 max-w-full text-xs uppercase w-[190px]"
            >
                <p class="grow text-white">Don't have an account?</p>
                <Link
                    href="/member"
                    class="text-orange-200 hover:text-orange-300 transition-colors"
                >
                    Sign Up
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const showPassword = ref(false);
const showGoogleLogin = ref(false);

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

// Show Google login option if user should login with Google
const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
        onError: (errors) => {
            console.log("Login errors:", errors);
            // If error message indicates Google login is needed, show the Google login button
            if (
                errors.email &&
                errors.email.includes("registered with Google")
            ) {
                showGoogleLogin.value = true;
            }
        },
    });
};

const loginWithGoogle = () => {
    // Direct browser redirect instead of axios request
    window.location.href = "/oauth/google/redirect";
};
</script>
