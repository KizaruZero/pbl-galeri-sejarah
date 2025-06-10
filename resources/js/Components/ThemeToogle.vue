<template>
  <div class="flex items-center">
    <input type="checkbox" class="hidden" id="dark-toggle" />
    <label for="dark-toggle" class="flex items-center cursor-pointer">
      <div class="flex h-8 w-14  items-center rounded-full bg-slate-300 p-1 transition duration-300 ease-in-out">
        <div class="toggle-circle h-6 w-6 rounded-full bg-white transition duration-300 ease-in-out flex items-center justify-center">
          <i class="fas fa-sun text-yellow-500" v-if="!isDarkMode"></i>
          <i class="fas fa-moon text-blue-500" v-if="isDarkMode"></i>
        </div>
      </div>
    </label>
  </div>
</template>
  
<style scoped>
#dark-toggle:checked + label .toggle-circle {
  transform: translateX(100%);
}
#dark-toggle:checked + label .toggle-circle {
  background-color: #1e3a8a; /* Dark mode background */
}
</style>
  
<script>
export default {
  data() {
    return {
      isDarkMode: false,
    };
  },
  mounted() {
  const darkToggle = document.getElementById('dark-toggle');
  const body = document.querySelector('body');

  // Cek localStorage
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    this.isDarkMode = true;
    body.classList.add('dark');
    darkToggle.checked = true;
  } else {
    this.isDarkMode = false;
    body.classList.remove('dark');
    darkToggle.checked = false;
  }

  // Listener toggle switch
  darkToggle.addEventListener('change', () => {
    this.isDarkMode = darkToggle.checked;

    if (this.isDarkMode) {
      body.classList.add('dark');
      localStorage.setItem('theme', 'dark');
    } else {
      body.classList.remove('dark');
      localStorage.setItem('theme', 'light');
    }
  });
}

};
</script>