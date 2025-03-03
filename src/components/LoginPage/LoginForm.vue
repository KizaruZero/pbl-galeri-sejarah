<template>
    <section class="ml-5 w-[46%] max-md:ml-0 max-md:w-full">
        <div class="flex flex-col grow text-xl text-center uppercase max-md:mt-10 max-md:max-w-full">
            <img :src="https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/ec8b49e8421cc376fa1a3e8abce01b5d93cf2a79119470daef15608baf3b9ae2?placeholderIfAbsent=true"
                alt="Logo" class="object-contain self-end mr-6 aspect-square w-[30px] max-md:mr-2.5" />
            <div
                class="flex overflow-hidden flex-col items-center px-16 pt-14 pb-40 mt-12 w-full bg-neutral-950 max-md:px-5 max-md:pb-24 max-md:mt-10 max-md:max-w-full">
                <h1 class="text-5xl text-white max-md:text-4xl">Welcome Back</h1>
                <h2 class="text-4xl text-white">Login</h2>

                <form @submit.prevent="handleSubmit" class="w-full">
                    <FormInput v-model="email" label="email" type="email" id="email"
                        :icon-src="https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/7debc8e95007082e443e13c2246a379774ae3f26dd4715326eeb4e69782d45a3?placeholderIfAbsent=true"
                        class="mt-24 max-md:mt-10" />

                    <FormInput v-model="password" label="Password" type="password" id="password"
                        :icon-src="https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/988eb6eb3d3f7b4afd8e4c3fc554aa01bb0182336fc8bbc63f920ab5d781a30f?placeholderIfAbsent=true" />

                    <button type="submit"
                        class="px-16 py-2.5 mt-24 max-w-full text-black whitespace-nowrap rounded-3xl bg-zinc-300 w-[373px] max-md:px-5 max-md:mt-10">
                        Login
                    </button>
                </form>

                <div class="flex gap-1.5 mt-8 mb-0 max-w-full text-xs w-[190px] max-md:mb-2.5">
                    <p class="grow text-white">Dont have an account?</p>
                    <a href="#" class="text-orange-200">Sign Up</a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    import {
        ref
    } from "vue";
    import FormInput from "./LoginPage/FormInput.vue";

    const email = ref("");
    const password = ref("");

    const handleSubmit = () => {
        // Handle form submission
        console.log("Form submitted:", {
            email: email.value,
            password: password.value,
        });
    };
</script>