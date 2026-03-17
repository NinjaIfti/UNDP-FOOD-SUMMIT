<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import EventFooter from '@/Components/EventFooter.vue';

defineProps({
    title: { type: String, default: 'Global Conscious Food Systems Summit' },
});

const page = usePage();
const blogEnabled = page.props.blog_enabled ?? false;
const siteSettings = page.props.site_settings ?? {};
const isHomePage = computed(() => page.url === '/' || page.url === '');
const logos = computed(() => {
    const raw = [
        siteSettings.logo_secondary || '/brand/govt.png',
        siteSettings.logo || '/brand/logo2.png',
        siteSettings.logo_third || '',
        siteSettings.logo_fourth || '',
    ];
    return raw.filter((v, index) => v && index < 4);
});
const mobileMenuOpen = ref(false);
const isAtTop = ref(true);

function onScroll() {
    isAtTop.value = window.scrollY < 20;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
});
onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <header
            class="fixed top-0 left-0 right-0 z-50 h-20 transition-all duration-300 sm:h-24 lg:h-28"
            :class="isAtTop && isHomePage ? 'bg-transparent' : 'bg-white/95 backdrop-blur-md'"
        >
            <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex flex-shrink-0 items-center gap-2 sm:gap-3">
                    <Link href="/" class="flex items-center gap-2 sm:gap-3">
                        <img
                            v-for="(logo, index) in logos"
                            :key="index"
                            :src="logo"
                            :alt="`Navbar logo ${index + 1}`"
                            :class="[
                                index === 0
                                    ? 'h-14 w-auto object-contain sm:h-16 md:h-20 lg:h-20'
                                    : 'h-24 w-auto max-w-[380px] object-contain sm:h-28 sm:max-w-[500px] md:h-32 md:max-w-[600px] lg:h-40 lg:max-w-[720px]',
                            ]"
                        />
                    </Link>
                </div>
                <div class="flex flex-shrink-0 items-center gap-1 sm:gap-2 lg:gap-2">
                    <nav class="hidden items-center gap-1 sm:flex lg:gap-2">
                        <Link href="/" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-base font-medium transition lg:px-3 lg:text-lg" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">Home</Link>
                        <Link href="/programme" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-base font-medium transition lg:px-3 lg:text-lg" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">Experience</Link>
                        <!-- <Link href="/speakers" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium transition lg:px-3 lg:text-base" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">Speakers</Link> -->
                        <Link href="/your-stay-in-bhutan" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-base font-medium transition lg:px-3 lg:text-lg" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">Your Bhutan Stay</Link>
                        <Link v-if="blogEnabled" href="/blog" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-base font-medium transition lg:px-3 lg:text-lg" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">Blog</Link>
                        <Link href="/get-involved" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-base font-medium transition lg:px-3 lg:text-lg" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">Get Involved</Link>
                        <Link href="/faq" class="font-body flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-base font-medium transition lg:px-3 lg:text-lg" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-primary-1 hover:bg-black/5 hover:text-black'">FAQs</Link>
                        <Link
                            href="/register-summit"
                            class="font-body flex-shrink-0 inline-flex items-center gap-2 whitespace-nowrap rounded-md bg-accent-2 px-3 py-2 text-sm font-semibold text-white transition hover:bg-accent-2/90 lg:px-4 lg:text-base"
                        >
                            Register
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                    </nav>
                    <button
                        type="button"
                        class="ml-auto flex h-10 w-10 items-center justify-center rounded-lg transition sm:hidden"
                        :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'"
                        aria-label="Toggle menu"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div
                v-show="mobileMenuOpen"
                class="px-4 py-4 sm:hidden"
                :class="(isAtTop && isHomePage) ? 'bg-primary-1' : 'bg-white'"
            >
                <nav class="flex flex-col gap-1">
                    <Link href="/" class="font-body rounded-md px-4 py-3 text-lg font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">Home</Link>
                    <Link href="/programme" class="font-body rounded-md px-4 py-3 text-lg font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">Experience</Link>
                    <!-- <Link href="/speakers" class="font-body rounded-md px-4 py-3 text-base font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">Speakers</Link> -->
                    <Link href="/your-stay-in-bhutan" class="font-body rounded-md px-4 py-3 text-lg font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">Your Bhutan Stay</Link>
                    <Link v-if="blogEnabled" href="/blog" class="font-body rounded-md px-4 py-3 text-lg font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">Blog</Link>
                    <Link href="/get-involved" class="font-body rounded-md px-4 py-3 text-lg font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">Get Involved</Link>
                    <Link href="/faq" class="font-body rounded-md px-4 py-3 text-lg font-medium transition" :class="(isAtTop && isHomePage) ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'" @click="mobileMenuOpen = false">FAQs</Link>
                    <Link
                        href="/register-summit"
                        class="font-body rounded-md bg-accent-2 px-4 py-3 text-center text-base font-semibold text-white transition hover:bg-accent-2/90 inline-flex items-center justify-center gap-2"
                        @click="mobileMenuOpen = false"
                    >
                        Register
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </nav>
            </div>
        </header>
        <!-- Same page bg as SummitIntroSection / home sections (#F4F1EA) -->
        <main class="flex-1 bg-[#F4F1EA] pt-20 sm:pt-24 lg:pt-28">
            <slot />
        </main>
        <EventFooter />
    </div>
</template>
