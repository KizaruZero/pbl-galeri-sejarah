<template>
  <main class="bg-stone-950">
    <div class="flex flex-col lg:flex-row min-h-screen">
      <!-- Image section - Smaller height on mobile -->
      <section class="w-full lg:w-1/2 h-[40vh] lg:h-screen">
        <ImageSection />
      </section>

      <!-- Login Form section -->
      <section class="w-full lg:w-1/2 flex items-center justify-center py-2">
        <LoginForm />
      </section>
    </div>
  </main>
</template>

<script setup>
import ImageSection from "@/Components-landing/LoginPage/ImageSection.vue";
import LoginForm from "@/Components-landing/LoginPage/LoginForm.vue";
</script>
