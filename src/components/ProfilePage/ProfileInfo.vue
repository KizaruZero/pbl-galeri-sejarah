<template>
    <section class="flex flex-col w-full px-4 sm:px-8 md:px-16 bg-black">
        <div class="w-full max-w-[1023px] mx-auto py-6 md:py-10">
            <!-- Main Profile Container -->
            <div class="flex flex-col md:flex-row md:gap-5">
                <!-- Profile Info Section -->
                <div class="w-full md:w-[58%] mb-8 md:mb-0">
                    <div class="flex flex-col sm:flex-row gap-5">
                        <!-- Profile Image and Details -->
                        <div class="w-full sm:w-[61%]">
                            <article class="flex flex-col text-base sm:text-lg md:text-xl text-white uppercase">
                                <img :src="profileData.profileImage" alt="Profile Picture"
                                    class="object-contain w-full max-w-[150px] md:max-w-[200px] aspect-square rounded-full " />
                                <h1 class="mt-6 md:mt-11 font-bold">{{ profileData.name }}</h1>
                                <h2 class="mt-4 md:mt-6">{{ profileData.username }}</h2>
                                <p class="mt-4 md:mt-6 break-words">{{ profileData.email }}</p>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- Social Stats Section -->
                <div class="w-full md:w-[42%]">
                    <div
                        class="flex justify-between sm:justify-around md:gap-6 lg:gap-24 text-base sm:text-lg md:text-xl text-center text-white uppercase mt-6 md:mt-20">
                        <div v-for="(stat, index) in socialStats" :key="index" class="flex flex-col">
                            <p class="text-lg md:text-xl font-bold">{{ stat.count }}</p>
                            <h3 class="mt-4 md:mt-10 text-sm md:text-base">{{ stat.label }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    import {
        ref,
        onMounted
    } from 'vue';

    const profileData = ref({
        profileImage: "../../src/assets/img/profile/pp.png",
        name: "Anonim",
        username: "Anonim",
        email: "Anonim@gmail.com",
    });

    const socialStats = ref([{
            label: "Postingan",
            count: 0
        },
        {
            label: "Like",
            count: 0
        },
        {
            label: "Favorite",
            count: 0
        }
    ]);

    const updateStats = (posts, likes, favorites) => {
        socialStats.value[0].count = posts;
        socialStats.value[1].count = likes;
        socialStats.value[2].count = favorites;
    };

    onMounted(() => {
        updateStats(5, 127, 43);
    });
</script>