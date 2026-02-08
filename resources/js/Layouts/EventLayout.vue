<script setup>
import { ref, provide, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import EventFooter from '@/Components/EventFooter.vue';
import RegisterModal from '@/Components/RegisterModal.vue';

defineProps({
    title: { type: String, default: 'Global Conscious Food Systems Summit' },
});

const mobileMenuOpen = ref(false);
const isAtTop = ref(true);
const showRegisterModal = ref(false);
function openRegister() { showRegisterModal.value = true; }
function closeRegister() { showRegisterModal.value = false; }

provide('openRegister', openRegister);

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
            class="fixed top-0 left-0 right-0 z-50 border-b backdrop-blur-md transition-all duration-300"
            :class="isAtTop ? 'border-white/10 bg-black/95' : 'border-black/10 bg-white/95'"
        >
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-3 bg-transparent">
                    <img src="/brand/logo1.png" alt="Global Conscious Food Systems Summit" class="h-12 w-12 shrink-0 object-contain mix-blend-multiply sm:h-14 sm:w-14" :class="isAtTop ? 'mix-blend-screen' : 'mix-blend-multiply'" />
                    <span class="font-title text-lg font-bold leading-tight sm:text-xl" :class="isAtTop ? 'text-white' : 'text-black'">
                        <span class="block">GCF</span>
                        <span class="block">SS</span>
                    </span>
                </Link>
                <nav class="hidden items-center gap-1 sm:flex sm:gap-2 lg:gap-4">
                    <Link href="/" class="rounded-md px-3 py-2 text-sm font-medium transition sm:px-4" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Home</Link>
                    <Link href="/#agenda" class="rounded-md px-3 py-2 text-sm font-medium transition sm:px-4" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Agenda</Link>
                    <Link href="/#speakers" class="rounded-md px-3 py-2 text-sm font-medium transition sm:px-4" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Speakers</Link>
                    <Link href="/#outcomes" class="rounded-md px-3 py-2 text-sm font-medium transition sm:px-4" :class="isAtTop ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-black hover:bg-black/5 hover:text-black'">Outcomes</Link>
                    <button @click="openRegister" class="rounded-md bg-accent-2 px-4 py-2 text-sm font-semibold text-primary-1 transition hover:bg-accent-2/90 sm:px-6">Register</button>
                </nav>
                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-lg transition sm:hidden"
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
            <div
                v-show="mobileMenuOpen"
                class="border-t px-4 py-4 sm:hidden"
                :class="isAtTop ? 'border-white/10 bg-black' : 'border-black/10 bg-white'"
            >
                <nav class="flex flex-col gap-1">
                    <Link href="/" class="rounded-md px-4 py-3 text-sm font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Home</Link>
                    <Link href="/#agenda" class="rounded-md px-4 py-3 text-sm font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Agenda</Link>
                    <Link href="/#speakers" class="rounded-md px-4 py-3 text-sm font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Speakers</Link>
                    <Link href="/#outcomes" class="rounded-md px-4 py-3 text-sm font-medium transition" :class="isAtTop ? 'text-white/90 hover:bg-white/10' : 'text-black hover:bg-black/5'" @click="mobileMenuOpen = false">Outcomes</Link>
                    <button class="rounded-md bg-accent-2 px-4 py-3 text-center text-sm font-semibold text-primary-1 transition hover:bg-accent-2/90" @click="mobileMenuOpen = false; openRegister()">Register</button>
                </nav>
            </div>
        </header>
        <main class="flex-1 pt-16 sm:pt-20">
            <slot />
        </main>
        <EventFooter />
        <RegisterModal :show="showRegisterModal" @close="closeRegister" />
    </div>
</template>
