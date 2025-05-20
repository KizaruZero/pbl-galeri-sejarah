<template>
  <div>
    <div v-if="!isAuthenticated">
      <div class="border-t border-gray-700 my-10"></div>
      <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">FORM MEMBERSHIP</h2>
      <form @submit.prevent="submit" class="max-w-xl mx-auto space-y-6">
        <div>
          <label for="name" class="block mb-2">Nama</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="w-full py-3 px-4 bg-gray-200 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500"
            required
          />
        </div>

        <div>
          <label for="email" class="block mb-2">Email</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="w-full py-3 px-4 bg-gray-200 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500"
            required
          />
        </div>

        <div>
          <label for="password" class="block mb-2">Password</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="w-full py-3 px-4 bg-gray-200 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500"
            required
          />
        </div>

        <div>
          <label for="password_confirmation" class="block mb-2"
            >Konfirmasi Password</label
          >
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="w-full py-3 px-4 bg-gray-200 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500"
            required
          />
        </div>

        <div class="flex space-x-4">
          <button
            type="submit"
            class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-md transition duration-300 border border-gray-700"
            :disabled="processing"
          >
            {{ processing ? "Processing..." : "Register" }}
          </button>
          <button
            type="button"
            @click="resetForm"
            class="w-full py-3 bg-black hover:bg-gray-900 text-white rounded-md transition duration-300 border border-red-700"
            :disabled="processing"
          >
            Reset
          </button>
        </div>
      </form>
    </div>
    <div v-else class="text-center py-8">
      <h2 class="text-2xl font-bold mb-4">Anda Sudah Login</h2>
      <p class="text-gray-300">Anda sudah terdaftar sebagai member.</p>
    </div>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
  isAuthenticated: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const processing = form.processing;

const submit = () => {
  form.post(route("register"), {
    onSuccess: () => {
      form.reset();
    },
  });
};

const resetForm = () => {
  form.reset();
};
</script>
