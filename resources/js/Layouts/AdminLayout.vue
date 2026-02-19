<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const sidebarOpen = ref(false);
const pagesExpanded = ref(true);
const sidebarNav = ref(null);
const sidebarScrollPosition = ref(0);

const logout = () => {
    router.post(route('admin.logout'));
};

const closeSidebarOnMobile = () => {
    if (window.innerWidth < 1024) {
        sidebarOpen.value = false;
    }
};

const saveSidebarScroll = () => {
    if (sidebarNav.value) {
        sidebarScrollPosition.value = sidebarNav.value.scrollTop;
        localStorage.setItem('adminSidebarScroll', sidebarScrollPosition.value.toString());
    }
};

const restoreSidebarScroll = () => {
    nextTick(() => {
        const savedScroll = localStorage.getItem('adminSidebarScroll');
        if (sidebarNav.value && savedScroll) {
            sidebarNav.value.scrollTop = parseInt(savedScroll, 10);
        }
    });
};

onMounted(() => {
    restoreSidebarScroll();
    
    if (sidebarNav.value) {
        sidebarNav.value.addEventListener('scroll', saveSidebarScroll);
    }
    
    router.on('start', () => {
        saveSidebarScroll();
    });
    
    router.on('finish', () => {
        setTimeout(() => {
            restoreSidebarScroll();
        }, 100);
    });
});

const page = usePage();
const sidebarLogo = computed(() => page.props.site_settings?.logo || '/brand/logo2.png');

const isDashboardActive = computed(() => route().current('admin.dashboard'));
const isHomepageActive = computed(() => route().current('admin.homepage'));
const isYourStayActive = computed(() => route().current('admin.your-stay'));
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
            class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col text-white transition-transform duration-300 lg:relative lg:translate-x-0"
            style="background-color: var(--color-sidebar-bg);"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="relative flex h-20 items-center justify-center px-4 sm:px-6">
                <img :src="sidebarLogo" alt="Logo" class="h-14 w-auto max-w-full object-contain sm:h-16" />
                <button
                    @click="sidebarOpen = false"
                    class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg p-2 text-white hover:bg-white/10 lg:hidden"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav ref="sidebarNav" class="sidebar-scroll flex-1 space-y-1 overflow-y-auto px-4 py-6">
                <!-- Dashboard -->
                <Link
                    :href="route('admin.dashboard')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="isDashboardActive ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </Link>

                <!-- Pages (collapsible) -->
                <div class="space-y-1">
                    <button
                        @click="pagesExpanded = !pagesExpanded"
                        class="flex w-full items-center justify-between gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition hover:bg-white/10"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Pages
                        </div>
                        <svg
                            class="h-4 w-4 shrink-0 transition-transform"
                            :class="pagesExpanded ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div v-show="pagesExpanded" class="ml-4 space-y-1 border-l border-white/20 pl-3">
                        <Link
                            :href="route('admin.homepage')"
                            @click="closeSidebarOnMobile"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 font-body text-sm font-medium transition"
                            :class="isHomepageActive ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Homepage
                        </Link>
                        <Link
                            :href="route('admin.programme')"
                            @click="closeSidebarOnMobile"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 font-body text-sm font-medium transition"
                            :class="route().current('admin.programme') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Programme/Experience
                        </Link>
                        <Link
                            :href="route('admin.your-stay')"
                            @click="closeSidebarOnMobile"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 font-body text-sm font-medium transition"
                            :class="isYourStayActive ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Your Stay in Bhutan
                        </Link>
                        <Link
                            :href="route('admin.get-involved')"
                            @click="closeSidebarOnMobile"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 font-body text-sm font-medium transition"
                            :class="route().current('admin.get-involved') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Get Involved
                        </Link>
                        <Link
                            :href="route('admin.faq')"
                            @click="closeSidebarOnMobile"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 font-body text-sm font-medium transition"
                            :class="route().current('admin.faq') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            FAQs
                        </Link>
                    </div>
                </div>

                <!-- Blog -->
                <Link
                    :href="route('admin.blog.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.blog.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Blog
                </Link>

                <!-- Applications -->
                <Link
                    :href="route('admin.applications.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.applications.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Applications
                </Link>

                <!-- Payments -->
                <Link
                    :href="route('admin.payments.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.payments.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Payments
                </Link>

                <!-- Packages -->
                <Link
                    :href="route('admin.packages.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.packages.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Packages
                </Link>

                <!-- Communications -->
                <Link
                    :href="route('admin.communications.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.communications.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Communications
                </Link>

                <!-- Users -->
                <Link
                    :href="route('admin.users.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.users.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Users
                </Link>

                <!-- Settings -->
                <Link
                    :href="route('admin.settings.index')"
                    @click="closeSidebarOnMobile"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 font-body text-sm font-medium transition"
                    :class="route().current('admin.settings.*') ? 'bg-[var(--color-sidebar-active)]' : 'hover:bg-white/10'"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
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
