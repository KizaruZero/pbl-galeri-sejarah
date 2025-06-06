<template>
    <MainLayout>
        <div class="bg-white dark:bg-black min-h-screen w-full">
            <div class="max-w-7xl mx-auto px-4 py-8 sm:py-12 lg:py-16">
                <ProfileHeaderSection
                    :user-data="userData"
                    :profile-photo="profilePhoto"
                    :stats="{
                        posts: totalPost,
                        likes: totalLikes,
                        favorites: totalFavorites,
                    }"
                />

                <ProfileContentSection
                    :active-tab="activeTab"
                    :photos="userPhotos"
                    :videos="userVideos"
                    :photoFavorites="userPhotoFavorites"
                    :videoFavorites="userVideoFavorites"
                    @change-tab="activeTab = $event"
                />

                <UploadMediaButton
                    v-model="showModal"
                    @upload-photo="visitUploadPhoto"
                    @upload-video="visitUploadVideo"
                    @bulk-upload="visitBulkUpload"
                />
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import axios from "axios";
import MainLayout from "@/Layouts/MainLayout.vue";
import ProfileHeaderSection from "@/Components-landing/ProfilePage/ProfileHeaderSection.vue";
import ProfileContentSection from "@/Components-landing/ProfilePage/ProfileContentSection.vue";
import UploadMediaButton from "@/Components-landing/ProfilePage/UploadMediaButton.vue";

const showModal = ref(false);
const page = usePage();
const authUser = ref(page.props.auth.user);
const activeTab = ref("photo");
const userData = ref(null);
const profilePhoto = ref("/default-profile.jpg");
const totalPost = ref(0);
const totalLikes = ref(0);
const totalFavorites = ref(0);
const userPhotos = ref([]);
const userVideos = ref([]);
const userPhotoFavorites = ref([]);
const userVideoFavorites = ref([]);

const getMediaUrl = (url) => {
    if (!url) return "/default-post.jpg";
    if (url.startsWith("http")) return url;

    const cleanPath = url
        .replace(/^storage\//, "")
        .replace(/^public\//, "")
        .replace(/^\//, "")
        .replace(/^storage\//, "");

    return cleanPath ? `/storage/${cleanPath}` : "/default-post.jpg";
};

const visitUploadPhoto = () => {
    showModal.value = false;
    router.visit("/upload-photo");
};

const visitUploadVideo = () => {
    showModal.value = false;
    router.visit("/upload-video");
};

const visitBulkUpload = () => {
    showModal.value = false;
    router.visit("/bulk");
};

onMounted(async () => {
    try {
        if (!authUser.value?.id) return;

        const userId = authUser.value.id;

        // Get user data
        const { data: userDataResponse } = await axios.get(
            `/api/users/${userId}`
        );

        // Include both photo_profile and profile_photo_url
        userData.value = {
            ...userDataResponse,
        };
        // Remove this line as we're handling it in the component
        // profilePhoto.value = getMediaUrl(userDataResponse.profile_photo_url);

        // Get photos
        const { data: photosResponse } = await axios.get(
            `/api/content-photo/user/${userId}`
        );
        userPhotos.value =
            photosResponse?.photos?.map((photo) => ({
                ...photo,
                image_url: getMediaUrl(photo.image_url),
            })) || [];

        // Get Total Favorites
        const { data: favoritesResponse } = await axios.get(
            `/api/user/totalfavorite/${userId}`
        );
        totalFavorites.value = favoritesResponse?.total || 0;

        // Get Photo Favorites
        const { data: photoFavoritesResponse } = await axios.get(
            `/api/favorite/photo/user/${userId}`
        );
        userPhotoFavorites.value =
            photoFavoritesResponse?.data?.map((photo) => ({
                ...photo,
                image_url: getMediaUrl(photo.image_url),
            })) || [];

        // Get videos
        const { data: videosResponse } = await axios.get(
            `/api/content-video/user/${userId}`
        );
        userVideos.value =
            videosResponse?.videos?.map((video) => ({
                ...video,
                thumbnail: getMediaUrl(video.thumbnail),
                video_url: getMediaUrl(video.video_url),
            })) || [];

        // Get Video Favorites
        const { data: videoFavoritesResponse } = await axios.get(
            `/api/favorite/video/user/${userId}`
        );
        userVideoFavorites.value =
            videoFavoritesResponse?.data?.map((video) => ({
                ...video,
                thumbnail: getMediaUrl(video.thumbnail),
                video_url: getMediaUrl(video.video_url),
            })) || [];

        // Get Total Likes (Reactions)
        const { data: likesResponse } = await axios.get(
            `/api/reaction/user/${userId}/total`
        );
        totalLikes.value = likesResponse?.total || 0;

        // Calculate stats
        totalPost.value = userPhotos.value.length + userVideos.value.length;
    } catch (error) {
        console.error("Error loading profile data:", error);
    }
});
</script>
