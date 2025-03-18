<template>
    <MainLayout>
        <HeroSection />
        <ProfileInfo />
        <PostSection />
    </MainLayout>
</template>

<script setup>
    import MainLayout from '../components/layouts/MainLayout.vue';
    import PostSection from '../components/ProfilePage/PostSection.vue';
    import ProfileInfo from '../components/ProfilePage/ProfileInfo.vue';
</script>