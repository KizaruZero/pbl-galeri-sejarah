<template>
    <section class="px-20 pt-11 pb-5 w-full bg-black bg-opacity-70 max-md:px-5 max-md:max-w-full">
        <div class="flex gap-5 max-md:flex-col">
            <GalleryColumn 
                v-for="(images, index) in [firstColumnImages, secondColumnImages, thirdColumnImages]" 
                :key="index"
                :images="images"
                class="max-md:w-3/4 max-sm:w-1/2 mx-auto"
                @image-clicked="goToDetail"
            />
        </div>
    </section>
</template>

<script>
import GalleryColumn from "@/components/GalleryColumn.vue";
import { useRouter } from "vue-router";

export default {
    name: "ImageGallery",
    components: {
        GalleryColumn,
    },
    setup() {
        const router = useRouter();
        
        const goToDetail = (image) => {
            router.push({
                name: 'ImageDetail',
                query: { imageUrl: image.src, description: image.description }
            });
        };

        return { goToDetail };
    },
    data() {
        return {
            firstColumnImages: [
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/d7260aac685e61947191f18b4cf411e594f892d9188d25d0fa47b26526c21632?placeholderIfAbsent=true", aspectRatio: "aspect-[0.55]", description: "Gambar pertama di kolom pertama." },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/243121631cf3be049eec15e3d96b08a1f53a2162ca0ff4fdd82eda8fbd40e220?placeholderIfAbsent=true", aspectRatio: "aspect-[1.35]", marginTop: "mt-6", description: "Gambar kedua di kolom pertama." },
            ],
            secondColumnImages: [
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/1599eb55718778980605f80b0e499339a7b43dec1d5d06c7ff07ebd8873dcae7?placeholderIfAbsent=true", aspectRatio: "aspect-[0.76]", description: "Gambar pertama di kolom kedua." },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/38b70a1f4adba7e23b30f66bac719c6e63de893995deabb794461fcfe3c32265?placeholderIfAbsent=true", aspectRatio: "aspect-[0.79]", marginTop: "mt-3.5", description: "Gambar kedua di kolom kedua." },
            ],
            thirdColumnImages: [
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/e500a86a9ee6b3b987abdfdf2529a869eb451fad7cdfc55b13fa0e2ffa1cb10b?placeholderIfAbsent=true", aspectRatio: "aspect-[1.22]", description: "Gambar pertama di kolom ketiga." },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/487fe555b476520c3867cb24939b559619b95c94cc478aedc00c42f02aa8456b?placeholderIfAbsent=true", aspectRatio: "aspect-[1.22]", description: "Gambar kedua di kolom ketiga." },
                { src: "https://cdn.builder.io/api/v1/image/assets/79875f50433f4dddb865cc88bfb43a5c/575d9d1d1885c52de693ef10a84fb9f862e3c528a1957bffc9e03ee971cab9ff?placeholderIfAbsent=true", aspectRatio: "aspect-[1.04]", description: "Gambar ketiga di kolom ketiga." },
            ],
        };
    },
};
</script>
