<template>
    <div class="flex flex-col mb-6 sm:mb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-8">
            <!-- Profile Info -->
            <div class="flex flex-col col-span-1 items-center md:items-start">
                <div
                    class="relative w-28 h-28 sm:w-40 sm:h-40 md:w-48 md:h-48 mb-3 sm:mb-4"
                >
                    <img
                        :src="
                            userData?.photo_profile
                                ? `/storage/${userData.photo_profile}`
                                : userData?.profile_photo_url || defaultPhoto
                        "
                        alt="Profile Photo"
                        class="w-full h-full object-cover rounded-full z-10 relative"
                        @error="handleImageError"
                    />
                    <div
                        class="absolute top-0 left-0 w-full h-full border-4 border-black dark:border-white rounded-full blur-sm z-0"
                    />
                    <div
                        class="absolute top-0 left-0 w-full h-full border border-black dark:border-white rounded-full z-10"
                    />
                </div>

                <div
                    class="text-black dark:text-white text-center md:text-left px-2 sm:px-0"
                >
                    <div
                        class="flex items-center justify-center md:justify-start gap-2"
                    >
                        <h1
                            class="text-lg sm:text-2xl md:text-3xl font-medium mb-1 sm:mb-2"
                        >
                            {{ userData?.name }}
                        </h1>
                        <button
                            @click="openModal"
                            class="text-black dark:text-white hover:text-blue-400 dark:hover:text-blue-400 transition duration-200"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 sm:w-6 sm:h-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                />
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm sm:text-lg md:text-xl mb-1">
                        {{ userData?.role }}
                    </p>
                    <p class="text-sm sm:text-lg md:text-xl">
                        {{ userData?.email }}
                    </p>
                    <!-- Add the download button at the top -->
                    <div class="flex justify-end gap-4 mb-4 mt-2">
                        <div class="flex flex-col-2 gap-4">
                            <!-- Buttons -->
                            <div class="flex flex-col gap-2">
                                <button
                                    @click="downloadTemplate"
                                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                        />
                                    </svg>
                                    Download Bulk Upload Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div
                class="flex col-span-2 justify-center md:justify-start items-center mt-4 sm:mt-0"
            >
                <div
                    class="flex flex-wrap justify-between w-full sm:justify-around gap-1 sm:gap-8 md:gap-12 lg:mb-20"
                >
                    <div class="flex flex-col items-center px-2 sm:px-0">
                        <span
                            class="text-black dark:text-white text-lg sm:text-2xl md:text-3xl font-semibold"
                            >{{ stats.posts }}</span
                        >
                        <span
                            class="text-black dark:text-white text-xs sm:text-sm md:text-base"
                            >UNGGAHAN</span
                        >
                    </div>
                    <div class="flex flex-col items-center px-2 sm:px-0">
                        <span
                            class="text-black dark:text-white text-lg sm:text-2xl md:text-3xl font-semibold"
                            >{{ stats.likes }}</span
                        >
                        <span
                            class="text-black dark:text-white text-xs sm:text-sm md:text-base"
                            >LIKE</span
                        >
                    </div>
                    <div class="flex flex-col items-center px-2 sm:px-0">
                        <span
                            class="text-black dark:text-white text-lg sm:text-2xl md:text-3xl font-semibold"
                            >{{ stats.favorites }}</span
                        >
                        <button
                            @click="showFavoritesModal"
                            class="text-black dark:text-white text-xs sm:text-sm md:text-base hover:text-blue-400 dark:hover:text-blue-400 transition duration-200"
                        >
                            FAVORITE
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Update Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-bold mb-4">Update Profile</h2>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2"
                        >Profile Photo</label
                    >
                    <input
                        type="file"
                        @change="handlePhotoChange"
                        accept="image/*"
                        class="w-full"
                    />
                    <img
                        :src="previewPhoto || getCurrentProfilePhoto()"
                        :alt="userData?.name || 'Profile Photo'"
                        class="mt-2 w-32 h-32 object-cover rounded-full"
                        @error="handleModalImageError"
                    />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Name</label>
                    <input
                        type="text"
                        v-model="formData.name"
                        class="w-full border rounded p-2"
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Cancel
                    </button>
                    <button
                        @click="handleSubmit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Favorites List Modal -->
        <div
            v-if="isFavoritesModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white rounded-lg p-6 w-96 max-h-[80vh] overflow-y-auto"
            >
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Daftar Penggemar</h2>
                    <button
                        @click="closeFavoritesModal"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div v-if="favoritesList?.length > 0" class="space-y-3">
                    <div
                        v-for="favorite in favoritesList"
                        :key="favorite.id"
                        class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded"
                    >
                        <img
                            :src="
                                favorite.user_photo
                                    ? `/storage/${favorite.user_photo}`
                                    : defaultPhoto
                            "
                            class="w-10 h-10 rounded-full object-cover"
                            @error="handleImageError"
                        />
                        <div>
                            <p class="font-medium">{{ favorite.user_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ formatDate(favorite.created_at) }}
                            </p>
                            <p class="text-xs text-gray-400">
                                Menyukai "{{ favorite.post_title }}"
                            </p>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 py-4">
                    Belum ada yang menyukai postingan Anda
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import defaultPhoto from "@/Assets/default-photo.jpg";

const props = defineProps({
    userData: {
        type: Object,
        default: () => ({
            name: "",
            email: "",
            role: "",
            profile_photo_url: null,
            id: null,
        }),
    },
    stats: {
        type: Object,
        required: true,
        default: () => ({
            posts: 0,
            likes: 0,
            favorites: 0,
        }),
    },
});

const emit = defineEmits(["update-profile", "refresh-data"]);

// Profile Edit Modal
const isModalOpen = ref(false);
const previewPhoto = ref(null);
const formData = ref({
    name: props.userData?.name || "",
    photo: null,
});

// Favorites Modal
const isFavoritesModalOpen = ref(false);
const favoritesList = ref([]);

// Import Modal
const isImportModalOpenVideo = ref(false);
const isImportModalOpenPhoto = ref(false);
const selectedFile = ref(null);
const uploadProgress = ref(0);
const isImporting = ref(false);
const importStatus = ref(null);

// Download Template
const downloadTemplate = () => {
    const link = document.createElement("a");
    link.href = "js/Assets/metadata_template.xlsx";
    link.download = "Bulk_Upload_Template.xlsx";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Profile Edit Functions
const openModal = () => {
    isModalOpen.value = true;
    formData.value.name = props.userData?.name || "";
};

const closeModal = () => {
    isModalOpen.value = false;
    previewPhoto.value = null;
    formData.value.photo = null;
};

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        formData.value.photo = file;
        previewPhoto.value = URL.createObjectURL(file);
    }
};

const handleSubmit = async () => {
    try {
        const updateData = new FormData();
        updateData.append("name", formData.value.name);

        if (formData.value.photo) {
            updateData.append("photo", formData.value.photo);
        }

        const response = await axios.post("/profile/update", updateData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        emit("update-profile", response.data);
        closeModal();
        window.location.reload();
    } catch (error) {
        console.error("Error updating profile:", error);
    }
};

// Favorites Functions
const showFavoritesModal = async () => {
    try {
        const response = await axios.get(
            `/api/favorite/total/user/${props.userData.id}`,
            {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            }
        );
        favoritesList.value = response.data.data || [];
        isFavoritesModalOpen.value = true;
    } catch (error) {
        console.error("Error fetching favorites:", error);
        favoritesList.value = [];
    }
};

const closeFavoritesModal = () => {
    isFavoritesModalOpen.value = false;
};

// Import Functions
const openImportModalVideo = () => {
    isImportModalOpenVideo.value = true;
    selectedFile.value = null;
    uploadProgress.value = 0;
    importStatus.value = null;
};

const openImportModalPhoto = () => {
    isImportModalOpenPhoto.value = true;
    selectedFile.value = null;
    uploadProgress.value = 0;
    importStatus.value = null;
};

const closeImportModal = () => {
    isImportModalOpenVideo.value = false;
    isImportModalOpenPhoto.value = false;
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const validTypes = [
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];

        if (
            validTypes.includes(file.type) ||
            file.name.endsWith(".xlsx") ||
            file.name.endsWith(".xls")
        ) {
            selectedFile.value = file;
            importStatus.value = null;
        } else {
            importStatus.value = {
                type: "error",
                message: "Please upload a valid Excel file (.xlsx or .xls)",
            };
            selectedFile.value = null;
        }
    }
};

const submitImportVideo = async () => {
    if (!selectedFile.value) return;

    isImporting.value = true;
    uploadProgress.value = 0;
    importStatus.value = null;

    const formData = new FormData();
    formData.append("file", selectedFile.value);

    try {
        const response = await axios.post("/videos/import", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
            onUploadProgress: (progressEvent) => {
                uploadProgress.value = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
            },
        });

        let message = response.data.message;
        if (response.data.skipped > 0) {
            message += ` (${response.data.skipped} baris dilewati karena data tidak lengkap)`;
        }

        importStatus.value = {
            type: "success",
            message: message,
        };

        emit("refresh-data");
    } catch (error) {
        let errorMessage =
            error.response?.data?.message || "Error mengimpor file";

        // Tambahkan detail validasi jika ada
        if (error.response?.data?.errors) {
            errorMessage +=
                ": " + Object.values(error.response.data.errors).join(" ");
        }

        importStatus.value = {
            type: "error",
            message: errorMessage,
        };
    } finally {
        isImporting.value = false;
    }
};

const submitImportPhoto = async () => {
    if (!selectedFile.value) return;

    isImporting.value = true;
    uploadProgress.value = 0;
    importStatus.value = null;

    const formData = new FormData();
    formData.append("file", selectedFile.value);

    try {
        const response = await axios.post("/photos/import", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
            onUploadProgress: (progressEvent) => {
                uploadProgress.value = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
            },
        });

        let message = response.data.message;
        if (response.data.skipped > 0) {
            message += ` (${response.data.skipped} baris dilewati karena data tidak lengkap)`;
        }

        importStatus.value = {
            type: "success",
            message: message,
        };

        emit("refresh-data");
    } catch (error) {
        let errorMessage =
            error.response?.data?.message || "Error mengimpor file";

        // Tambahkan detail validasi jika ada
        if (error.response?.data?.errors) {
            errorMessage +=
                ": " + Object.values(error.response.data.errors).join(" ");
        }

        importStatus.value = {
            type: "error",
            message: errorMessage,
        };
    } finally {
        isImporting.value = false;
    }
};

// Add helper function to get current profile photo
const getCurrentProfilePhoto = () => {
    if (props.userData?.photo_profile) {
        return `/storage/${props.userData.photo_profile}`;
    } else if (props.userData?.profile_photo_url) {
        return props.userData.profile_photo_url;
    }
    return defaultPhoto;
};

// Add error handler for modal image
const handleModalImageError = (e) => {
    console.log("Modal image error, falling back to default photo");
    e.target.src = defaultPhoto;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
