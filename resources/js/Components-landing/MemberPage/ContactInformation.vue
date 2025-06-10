<template>
    <div class="my-20 mx-auto px-4">
        <!-- Divider -->
        <div class="relative mb-16">
            <div class="absolute inset-0 flex items-center">
                <div
                    class="w-full border-t border-gray-300 dark:border-gray-700"
                ></div>
            </div>
            <div class="relative flex justify-center">
                <div class="bg-white dark:bg-black px-6">
                    <div
                        class="w-12 h-12 bg-black dark:bg-white rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-white dark:text-black"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
            <!-- Contact Information -->
            <div class="space-y-8">
                <div class="text-center lg:text-left">
                    <h2
                        class="text-3xl md:text-4xl font-bold mb-4 text-black dark:text-white"
                    >
                        HUBUNGI KAMI
                    </h2>
                    <p
                        class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed"
                    >
                        Punya pertanyaan tentang membership? Tim kami siap
                        membantu Anda 24/7
                    </p>
                </div>

                <div v-if="!isLoading" class="space-y-6">
                    <!-- Email -->
                    <div
                        class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl transition-all duration-300"
                    >
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-black dark:bg-white rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-white dark:text-black"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="font-bold text-black dark:text-white mb-2"
                                >
                                    Email
                                </h3>
                                <a
                                    :href="
                                        'mailto:' + companyProfile?.cms_email
                                    "
                                    class="text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors duration-200 break-all"
                                >
                                    {{ companyProfile?.cms_email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div
                        class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl transition-all duration-300"
                    >
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-black dark:bg-white rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-white dark:text-black"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="font-bold text-black dark:text-white mb-2"
                                >
                                    Telepon
                                </h3>
                                <span
                                    class="text-gray-600 dark:text-gray-400"
                                    >{{ companyProfile?.cms_phone }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div
                        class="group relative overflow-hidden rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl transition-all duration-300"
                    >
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-black dark:bg-white rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-white dark:text-black"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="font-bold text-black dark:text-white mb-2"
                                >
                                    Lokasi
                                </h3>
                                <span
                                    class="text-gray-600 dark:text-gray-400 leading-relaxed"
                                    >{{ companyProfile?.cms_address }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-else class="space-y-6">
                    <div
                        v-for="i in 3"
                        :key="i"
                        class="rounded-2xl bg-white dark:bg-black border border-gray-200 dark:border-gray-800 p-6"
                    >
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-gray-200 dark:bg-gray-800 rounded-xl animate-pulse"
                            ></div>
                            <div class="flex-1 space-y-2">
                                <div
                                    class="h-4 bg-gray-200 dark:bg-gray-800 rounded animate-pulse w-1/4"
                                ></div>
                                <div
                                    class="h-4 bg-gray-200 dark:bg-gray-800 rounded animate-pulse w-3/4"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="space-y-8">
                <div class="text-center lg:text-left">
                    <h2
                        class="text-3xl md:text-4xl font-bold mb-4 text-black dark:text-white"
                    >
                        KRITIK & SARAN
                    </h2>
                    <p
                        class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed"
                    >
                        Bantuan Anda sangat berharga untuk meningkatkan layanan
                        kami
                    </p>
                </div>

                <form @submit.prevent="sendFeedback" class="space-y-6">
                    <!-- Name Input -->
                    <div class="space-y-2">
                        <label
                            for="name"
                            class="block text-sm font-bold text-black dark:text-white"
                        >
                            Nama Lengkap
                        </label>
                        <input
                            v-model="feedbackForm.name"
                            type="text"
                            id="name"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all duration-200"
                            placeholder="Masukkan nama lengkap Anda"
                        />
                    </div>

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label
                            for="email"
                            class="block text-sm font-bold text-black dark:text-white"
                        >
                            Email
                        </label>
                        <input
                            v-model="feedbackForm.email"
                            type="email"
                            id="email"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all duration-200"
                            placeholder="nama@email.com"
                        />
                    </div>

                    <!-- Subject Input -->
                    <div class="space-y-2">
                        <label
                            for="subject"
                            class="block text-sm font-bold text-black dark:text-white"
                        >
                            Subjek
                        </label>
                        <input
                            v-model="feedbackForm.subject"
                            type="text"
                            id="subject"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all duration-200"
                            placeholder="Kritik dan Saran Membership"
                        />
                    </div>

                    <!-- Message Textarea -->
                    <div class="space-y-2">
                        <label
                            for="message"
                            class="block text-sm font-bold text-black dark:text-white"
                        >
                            Pesan
                        </label>
                        <textarea
                            v-model="feedbackForm.message"
                            id="message"
                            rows="6"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="Tuliskan kritik dan saran Anda di sini..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-black dark:bg-white text-white dark:text-black font-bold py-4 px-6 rounded-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] group"
                    >
                        <span class="flex items-center justify-center">
                            <svg
                                class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                />
                            </svg>
                            Kirim via Gmail
                        </span>
                    </button>
                </form>

                <!-- Info Box -->
                <div
                    class="rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6"
                >
                    <div class="flex items-start space-x-3">
                        <div
                            class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0"
                        >
                            <svg
                                class="w-4 h-4 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h4
                                class="font-bold text-black dark:text-white mb-1"
                            >
                                Cara Kerja
                            </h4>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed"
                            >
                                Setelah mengklik "Kirim via Gmail", aplikasi
                                Gmail akan terbuka dengan template email yang
                                sudah terisi. Anda tinggal klik kirim untuk
                                mengirim feedback Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const companyProfile = ref(null);
const isLoading = ref(true);

const feedbackForm = ref({
    name: "",
    email: "",
    subject: "Kritik dan Saran Membership",
    message: "",
});

const fetchCompanyProfile = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get("/api/company-profile");
        companyProfile.value = response.data.data;
    } catch (error) {
        console.error("Error fetching company profile:", error);
    } finally {
        isLoading.value = false;
    }
};

const sendFeedback = () => {
    if (
        !feedbackForm.value.name ||
        !feedbackForm.value.email ||
        !feedbackForm.value.message
    ) {
        Swal.fire({
            icon: "warning",
            title: "Oops...",
            text: "Mohon lengkapi semua field yang diperlukan",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    // Create email template
    const emailTemplate = `
Halo Tim ${companyProfile.value?.cms_company_name || "Membership"},

Saya ingin menyampaikan kritik dan saran terkait layanan membership:

---
INFORMASI PENGIRIM:
Nama: ${feedbackForm.value.name}
Email: ${feedbackForm.value.email}

KRITIK & SARAN:
${feedbackForm.value.message}

---

Terima kasih atas perhatiannya.

Hormat saya,
${feedbackForm.value.name}
  `.trim();

    // Create Gmail compose URL
    const gmailUrl = `https://mail.google.com/mail/?view=cm&to=${encodeURIComponent(
        companyProfile.value?.cms_email || ""
    )}&su=${encodeURIComponent(
        feedbackForm.value.subject
    )}&body=${encodeURIComponent(emailTemplate)}`;

    // Open Gmail in new tab
    window.open(gmailUrl, "_blank");

    // Reset form
    feedbackForm.value = {
        name: "",
        email: "",
        subject: "Kritik dan Saran Membership",
        message: "",
    };

    // Show success message
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: "Gmail telah terbuka dengan template email. Silakan klik kirim untuk mengirim feedback Anda.",
        confirmButtonColor: "#3085d6",
    });
};

onMounted(() => {
    fetchCompanyProfile();
});
</script>
