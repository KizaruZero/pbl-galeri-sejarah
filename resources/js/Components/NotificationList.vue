<template>
    <div class="relative">
        <!-- Notification Bell Icon -->
        <button
            @click="toggleNotifications"
            class="relative p-2 text-gray-600 hover:text-gray-800"
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
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
            </svg>
            <!-- Notification Badge -->
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full"
            >
                {{ unreadCount }}
            </span>
        </button>

        <!-- Notification Dropdown -->
        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50"
        >
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold">Notifications</h3>
            </div>

            <div class="max-h-96 overflow-y-auto">
                <div
                    v-if="notifications.length === 0"
                    class="p-4 text-center text-gray-500"
                >
                    No notifications
                </div>

                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="p-4 border-b hover:bg-gray-50 cursor-pointer"
                    :class="{ 'bg-blue-50': !notification.read_at }"
                    @click="markAsRead(notification)"
                >
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">
                                {{ notification.data.data }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formatDate(notification.created_at) }}
                            </p>
                        </div>
                        <button
                            v-if="!notification.read_at"
                            @click.stop="markAsRead(notification)"
                            class="ml-2 text-blue-600 hover:text-blue-800"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t">
                <button
                    @click="markAllAsRead"
                    class="w-full text-center text-sm text-blue-600 hover:text-blue-800"
                >
                    Mark all as read
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
    name: "NotificationList",

    setup() {
        const notifications = ref([]);
        const isOpen = ref(false);
        const unreadCount = ref(0);

        const fetchNotifications = async () => {
            try {
                const response = await axios.get("/api/notifications");
                notifications.value = response.data;
                unreadCount.value = notifications.value.filter(
                    (n) => !n.read_at
                ).length;
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        };

        const markAsRead = async (notification) => {
            try {
                await axios.post(
                    `/api/notifications/${notification.id}/mark-as-read`
                );
                notification.read_at = new Date().toISOString();
                unreadCount.value--;
            } catch (error) {
                console.error("Error marking notification as read:", error);
            }
        };

        const markAllAsRead = async () => {
            try {
                await axios.post("/api/notifications/mark-all-as-read");
                notifications.value.forEach((notification) => {
                    notification.read_at = new Date().toISOString();
                });
                unreadCount.value = 0;
            } catch (error) {
                console.error(
                    "Error marking all notifications as read:",
                    error
                );
            }
        };

        const toggleNotifications = () => {
            isOpen.value = !isOpen.value;
            if (isOpen.value) {
                fetchNotifications();
            }
        };

        const formatDate = (date) => {
            return new Date(date).toLocaleString();
        };

        // Close dropdown when clicking outside
        const handleClickOutside = (event) => {
            if (!event.target.closest(".relative")) {
                isOpen.value = false;
            }
        };

        onMounted(() => {
            document.addEventListener("click", handleClickOutside);
            fetchNotifications();
        });

        return {
            notifications,
            isOpen,
            unreadCount,
            toggleNotifications,
            markAsRead,
            markAllAsRead,
            formatDate,
        };
    },
};
</script>
