<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const sidebarOpen = ref(false); // Start closed on mobile

const logout = () => {
    router.post(route('admin.logout'));
};

const closeSidebarOnMobile = () => {
    if (window.innerWidth < 1024) {
        sidebarOpen.value = false;
    }
};
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Mobile Overlay -->
        <div
            v-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-primary-1 text-white transition-transform duration-300 lg:relative lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                <img src="/brand/logo2.png" alt="Logo" class="h-8 w-auto sm:h-10" />
                <button
                    @click="sidebarOpen = false"
                    class="rounded-lg p-2 text-white hover:bg-white/10 lg:hidden"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-6">
                <Link
                    :href="route('admin.dashboard')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition hover:bg-white/10"
                    :class="route().current('admin.dashboard') ? 'bg-white/10' : ''"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </Link>

                <Link
                    :href="route('admin.homepage')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition hover:bg-white/10"
                    :class="route().current('admin.homepage') ? 'bg-white/10' : ''"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Homepage
                </Link>
            </nav>

            <div class="border-t border-white/10 p-4">
                <button
                    @click="logout"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition hover:bg-white/10"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="flex h-16 items-center justify-between border-b bg-white px-4 sm:px-6">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="rounded-lg p-2 text-gray-600 hover:bg-gray-100"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <h1 class="font-title text-lg font-bold text-primary-1 sm:text-xl">Admin Panel</h1>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
