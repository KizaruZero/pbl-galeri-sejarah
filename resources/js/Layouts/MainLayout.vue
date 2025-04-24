<script setup>
import Navbar from '@/Components-landing/Navbar.vue'
import Footer from '@/Components-landing/Footer.vue'
import BaseLoading from '@/Components/BaseLoading.vue'
</script>

<template>
    <BaseLoading /> 
  <Navbar />
  <main>
    <slot></slot>
  </main>
  <Footer />
</template>
