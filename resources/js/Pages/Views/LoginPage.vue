<template>
  <main class="min-h-screen bg-stone-950">
    <div class="flex min-h-screen">
      <!-- Left section - Image -->
      <section class="hidden lg:flex lg:w-1/2 p-8">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/c67971bfdb224af7ac1abd38d94b93d0/3afdba83c7cccfa5981867fd3f1620f87d352528"
          alt="Login illustration"
          class="object-cover w-full rounded-2xl"
        />
      </section>

      <!-- Right section - Login Form -->
      <section class="w-full lg:w-1/2 flex items-center justify-center">
        <LoginForm />
      </section>
    </div>
  </main>
</template>

<script setup>
import LoginForm from "@/Components-landing/LoginPage/LoginForm.vue";
</script>
