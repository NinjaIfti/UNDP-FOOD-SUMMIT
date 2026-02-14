<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import EventFooter from '@/Components/EventFooter.vue';

defineProps({
    title: { type: String, default: 'Global Conscious Food Systems Summit' },
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
            class="fixed top-0 left-0 right-0 z-50 h-20 border-b backdrop-blur-md transition-all duration-300 sm:h-24 lg:h-28"
            :class="isAtTop ? 'border-white/10 bg-black/95' : 'border-black/10 bg-white/95'"
        >
            <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex flex-shrink-0 items-center gap-2 sm:gap-3">
                    <Link href="/" class="flex items-center gap-2 sm:gap-3">
                        <img src="/brand/govt.png" alt="Government of Bhutan" class="h-14 w-auto object-contain sm:h-16 md:h-20 lg:h-20" />
                        <img src="/brand/logo2.png" alt="Global Conscious Food Systems Summit" class="h-24 w-auto max-w-[380px] object-contain sm:h-28 sm:max-w-[500px] md:h-32 md:max-w-[600px] lg:h-40 lg:max-w-[720px]" />
                    </Link>
                </div>
                <div class="flex flex-shrink-0 items-center gap-1 sm:gap-2 lg:gap-2">
                    <nav class="hidden items-center gap-1 sm:flex lg:gap-2">
                        <Link href="/" class="flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium transition lg:px-3 lg:text-base" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Home</Link>
                        <Link href="/programme" class="flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium uppercase transition lg:px-3 lg:text-base" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Programme/Experience</Link>
                        <Link href="/#bhutan-stay" class="flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium transition lg:px-3 lg:text-base" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Your Bhutan Stay</Link>
                        <Link href="/#faq" class="flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium transition lg:px-3 lg:text-base" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">FAQs</Link>
                        <Link href="/get-involved" class="flex-shrink-0 whitespace-nowrap rounded-md px-2 py-2 text-sm font-medium transition lg:px-3 lg:text-base" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Get Involved</Link>
                        <Link href="/register-summit" class="flex-shrink-0 whitespace-nowrap rounded-md bg-accent-2 px-3 py-2 text-sm font-semibold text-primary-1 transition hover:bg-accent-2/90 lg:px-4 lg:text-base">Register</Link>
                    </nav>
                    <button
                        type="button"
                        class="ml-auto flex h-10 w-10 items-center justify-center rounded-lg transition sm:hidden"
                        :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-primary-1 hover:bg-black/5'"
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
                class="border-t px-4 py-4 sm:hidden"
                :class="isAtTop ? 'border-white/10 bg-black' : 'border-black/10 bg-white'"
            >
                <nav class="flex flex-col gap-1">
                    <Link href="/" class="rounded-md px-4 py-3 text-base font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Home</Link>
                    <Link href="/programme" class="rounded-md px-4 py-3 text-base font-medium uppercase transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Programme/Experience</Link>
                    <Link href="/#bhutan-stay" class="rounded-md px-4 py-3 text-base font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Your Bhutan Stay</Link>
                    <Link href="/#faq" class="rounded-md px-4 py-3 text-base font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">FAQs</Link>
                    <Link href="/get-involved" class="rounded-md px-4 py-3 text-base font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Get Involved</Link>
                    <Link href="/register-summit" class="rounded-md bg-accent-2 px-4 py-3 text-center text-base font-semibold text-primary-1 transition hover:bg-accent-2/90" @click="mobileMenuOpen = false">Register</Link>
                </nav>
            </div>
        </header>
        <main class="flex-1 pt-20 sm:pt-24 lg:pt-28">
            <slot />
        </main>
        <EventFooter />
    </div>
</template>
