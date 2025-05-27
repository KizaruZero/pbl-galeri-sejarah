<template>
  <main class="bg-stone-950">
    <div class="flex flex-col lg:flex-row min-h-screen">
      <!-- Image section - Smaller height on mobile -->
      <section class="w-full lg:w-1/2 h-[40vh] lg:h-screen">
        <ImageSection />
      </section>

      <!-- Forgot Password Form section -->
      <section class="w-full lg:w-1/2 flex items-center justify-center py-8 px-4">
        <div class="w-full max-w-md space-y-6 text-white">
          <div>
            <h2 class="text-3xl font-bold mb-2">Forgot Password</h2>
            <p class="text-gray-400 text-sm">
              Forgot your password? No problem. Just let us know your email address and we
              will email you a password reset link that will allow you to choose a new
              one.
            </p>
          </div>

          <div
            v-if="status"
            class="p-4 bg-green-500/10 text-green-400 rounded-lg text-sm"
          >
            {{ status }}
          </div>

          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <InputLabel for="email" value="Email" class="text-white" />
              <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full bg-zinc-900 border-zinc-700 focus:border-white focus:ring-white text-white"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-between">
              <Link
                href="/login"
                class="text-sm text-gray-400 hover:text-white transition-colors"
              >
                Back to login
              </Link>
              <PrimaryButton
                :class="[
                  'bg-white text-black hover:bg-gray-100',
                  { 'opacity-25': form.processing },
                ]"
                :disabled="form.processing"
              >
                Send Reset Link
              </PrimaryButton>
            </div>
          </form>
        </div>
      </section>
    </div>
  </main>
</template>

<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ImageSection from "@/Components-landing/LoginPage/ImageSection.vue";

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.post(route("password.email"));
};
</script>
