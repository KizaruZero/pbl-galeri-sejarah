<template>
    <section class="px-20 pt-11 pb-5 w-full bg-black bg-opacity-70 max-md:px-5 max-md:max-w-full">
        <div class="flex gap-5 max-md:grid max-md:grid-cols-2 max-sm:grid-cols-1 max-md:gap-4">
            <GalleryColumn :images="firstColumnImages" class="w-1/3 max-md:w-full" />
            <GalleryColumn :images="secondColumnImages" class="w-1/3 max-md:w-full" />
            <GalleryColumn :images="thirdColumnImages" class="w-1/3 max-md:w-full" />
        </div>
    </section>
</template>

<script>
import GalleryColumn from "@/components/GalleryColumn.vue";

export default {
    name: "ImageGallery",
    components: {
        GalleryColumn,
    },
    data() {
        return {
            firstColumnImages: [
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/d7260aac685e61947191f18b4cf411e594f892d9188d25d0fa47b26526c21632?placeholderIfAbsent=true" },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/243121631cf3be049eec15e3d96b08a1f53a2162ca0ff4fdd82eda8fbd40e220?placeholderIfAbsent=true" },
            ],
            secondColumnImages: [
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/1599eb55718778980605f80b0e499339a7b43dec1d5d06c7ff07ebd8873dcae7?placeholderIfAbsent=true" },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/38b70a1f4adba7e23b30f66bac719c6e63de893995deabb794461fcfe3c32265?placeholderIfAbsent=true" },
            ],
            thirdColumnImages: [
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/e500a86a9ee6b3b987abdfdf2529a869eb451fad7cdfc55b13fa0e2ffa1cb10b?placeholderIfAbsent=true" },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/487fe555b476520c3867cb24939b559619b95c94cc478aedc00c42f02aa8456b?placeholderIfAbsent=true" },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/575d9d1d1885c52de693ef10a84fb9f862e3c528a1957bffc9e03ee971cab9ff?placeholderIfAbsent=true" },
            ],
        };
    },
    computed: {
        uniformImages() {
            return [...this.firstColumnImages, ...this.secondColumnImages, ...this.thirdColumnImages].map(img => ({
                ...img,
                aspectRatio: "aspect-[1]", // Samakan semua rasio gambar agar tidak berantakan di mobile
                class: "object-cover w-full h-full", // Pastikan gambar menyesuaikan ukuran
            }));
        },
    },
};
</script>
