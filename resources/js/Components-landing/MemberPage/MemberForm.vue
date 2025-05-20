<template>
  <div>
    <div v-if="!isAuthenticated">
      <div class="border-t border-gray-700 my-10"></div>
      <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">FORM MEMBERSHIP</h2>
      <form @submit.prevent="submitForm" class="max-w-xl mx-auto space-y-6">
        <div>
          <label for="nama" class="block mb-2">Nama</label>
          <input
            type="text"
            id="nama"
            v-model="form.nama"
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
          <label for="konfirmasi_password" class="block mb-2">Konfirmasi Password</label>
          <input
            type="password"
            id="konfirmasi_password"
            v-model="form.konfirmasi_password"
            class="w-full py-3 px-4 bg-gray-200 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500"
            required
          />
        </div>

        <div class="flex space-x-4">
          <button
            type="submit"
            class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-md transition duration-300 border border-gray-700"
          >
            Submit
          </button>
          <button
            type="button"
            @click="resetForm"
            class="w-full py-3 bg-black hover:bg-gray-900 text-white rounded-md transition duration-300 border border-red-700"
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
import { ref, defineProps } from "vue";

const props = defineProps({
  isAuthenticated: {
    type: Boolean,
    default: false,
  },
});

const form = ref({
  nama: "",
  email: "",
  password: "",
  konfirmasi_password: "",
});

const emit = defineEmits(["submit", "reset"]);

const submitForm = () => {
  if (form.value.password !== form.value.konfirmasi_password) {
    alert("Password dan konfirmasi password tidak cocok!");
    return;
  }
  emit("submit", form.value);
};

const resetForm = () => {
  form.value = {
    nama: "",
    email: "",
    password: "",
    konfirmasi_password: "",
  };
  emit("reset");
};
</script>
