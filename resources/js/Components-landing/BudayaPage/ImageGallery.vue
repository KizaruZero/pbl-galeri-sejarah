<template>
    <section class="px-20 pt-11 pb-5 w-full bg-black bg-opacity-70 max-md:px-5 max-md:max-w-full">
        <!-- Loading State -->
        <div v-if="loading" class="text-center text-white py-10">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-white"></div>
            <p class="mt-2">Memuat galeri...</p>
        </div>
        
        <!-- Error State -->
        <div v-if="error" class="text-center text-red-400 py-10">
            <p class="font-medium">Gagal memuat galeri</p>
            <p class="text-sm">{{ error }}</p>
            <button 
                @click="fetchPhotos"
                class="mt-3 px-4 py-2 bg-white text-black rounded hover:bg-gray-200 transition"
            >
                Coba Lagi
            </button>
        </div>
        
        <!-- Gallery Content -->
        <div v-if="!loading && !error" class="flex gap-5 max-md:flex-col">
            <GalleryColumn
                v-for="(images, index) in columnImages"
                :key="index"
                :images="images"
                class="max-md:w-3/4 max-sm:w-1/2 mx-auto"
                @image-clicked="goToDetail"
            />
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import GalleryColumn from "@/Components-landing/GalleryColumn.vue";

const router = useRouter();
const photos = ref([]);
const loading = ref(true);
const error = ref(null);

// Fetch photos from API
const fetchPhotos = async () => {
    try {
        loading.value = true;
        error.value = null;
        const response = await axios.get('/api/content-photo');
        
        if (!response.data || response.data.length === 0) {
            throw new Error('Tidak ada foto yang tersedia');
        }
        
        photos.value = response.data;
    } catch (err) {
        error.value = err.response?.data?.message || err.message || 'Terjadi kesalahan saat memuat galeri';
        console.error('Error fetching photos:', err);
    } finally {
        loading.value = false;
    }
};

// Distribute photos to columns
const columnImages = computed(() => {
    if (!photos.value.length) return [[], [], []];
    
    const columns = [[], [], []];
    photos.value.forEach((photo, index) => {
        const columnIndex = index % 3;
        
        columns[columnIndex].push({
            src: photo.metadata_photo?.photo_url || '/placeholder-image.jpg',
            aspectRatio: getAspectRatio(photo),
            marginTop: index > 2 ? 'mt-6' : '',
            description: photo.description || photo.title || 'Foto budaya',
            id: photo.id,
            slug: photo.slug
        });
    });
    
    return columns;
});

// Helper function to determine aspect ratio
const getAspectRatio = (photo) => {
    // You can customize this based on your needs
    const ratios = ["aspect-[0.55]", "aspect-[0.76]", "aspect-[1.22]"];
    return ratios[photo.id % ratios.length] || "aspect-square";
};

// Navigate to detail page
const goToDetail = (image) => {
    router.push({
        name: 'budaya.detail',
        params: { slug: image.slug }
    });
};

onMounted(() => {
    fetchPhotos();
});
</script>