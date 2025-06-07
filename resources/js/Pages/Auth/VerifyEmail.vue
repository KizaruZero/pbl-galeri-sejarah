<template>
  <main class="bg-stone-950">
    <div class="flex flex-col lg:flex-row min-h-screen">
      <!-- Image section - Smaller height on mobile -->
      <section class="w-full lg:w-1/2 h-[40vh] lg:h-screen">
        <ImageSection />
      </section>

      <!-- Email Verification Form section -->
      <section class="w-full lg:w-1/2 flex items-center justify-center py-8 px-4">
        <div class="w-full max-w-md space-y-6 text-white">
          <div>
            <h2 class="text-3xl font-bold mb-2">Email Verification</h2>
            <p class="text-gray-400 text-sm">
              Thanks for signing up! Before getting started, could you verify your
              email address by clicking on the link we just emailed to you? If you
              didn't receive the email, we will gladly send you another.
            </p>
          </div>

          <div
            v-if="verificationLinkSent"
            class="p-4 bg-green-500/10 text-green-400 rounded-lg text-sm"
          >
            A new verification link has been sent to the email address you
            provided during registration.
          </div>

          <form @submit.prevent="submit" class="space-y-4">
            <div class="flex items-center justify-between">
              <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="text-sm text-gray-400 hover:text-white transition-colors"
              >
                Log Out
              </Link>
              <PrimaryButton
                :class="[
                  'bg-white text-black hover:bg-gray-100',
                  { 'opacity-25': form.processing },
                ]"
                :disabled="form.processing"
              >
                Resend Verification Email
              </PrimaryButton>
            </div>
          </form>
        </div>
      </section>
    </div>
  </main>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ImageSection from "@/Components-landing/LoginPage/ImageSection.vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>
