<script setup>
import HeaderContent from '../components/Navbar.vue'
import FooterContent from '../components/Footer.vue'

</script>

<template>
  <HeaderContent/>
  <main>
    <slot></slot>
  </main>
  <FooterContent/>
</template>
