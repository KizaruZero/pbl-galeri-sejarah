<script setup>
import Navbar from '@/Components-landing/Navbar.vue'
import Footer from '@/Components-landing/Footer.vue'
</script>

<template>
  <Navbar />
  <main>
    <slot></slot>
  </main>
  <Footer />
</template>
