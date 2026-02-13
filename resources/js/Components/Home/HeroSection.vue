<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({
            hero_title: 'Global Conscious Food Systems Summit',
            hero_date: '31 August – 4 September 2026',
            hero_location: 'Bhutan',
            hero_description: 'Let\'s co-create a food future rooted in wisdom, nature connection, compassion, and deep collaboration.',
            countdown_date: '2026-08-31T09:00:00',
        }),
    },
});

const eventDate = new Date(props.settings.countdown_date);
const now = ref(new Date());
let timer;
let slideInterval;

const countdown = computed(() => {
    const diff = eventDate - now.value;
    if (diff <= 0) return { days: 0, hours: 0, minutes: 0, seconds: 0 };
    return {
        days: Math.floor(diff / (1000 * 60 * 60 * 24)),
        hours: Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
        minutes: Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)),
        seconds: Math.floor((diff % (1000 * 60)) / 1000),
    };
});

const slides = ['/brand/img55.jpg', '/brand/img34.jpg', '/brand/img32.jpg', '/brand/img30.jpg', '/brand/img22.jpg'];
const currentIndex = ref(0);

function nextSlide() {
    currentIndex.value = (currentIndex.value + 1) % slides.length;
}

const titleText = props.settings.hero_title;
const displayedTitle = ref('');
let charIndex = 0;
let typingInterval;
let eraseInterval;
let pauseTimeout;
const isTyping = ref(true);

function startTyping() {
    isTyping.value = true;
    typingInterval = setInterval(() => {
        if (charIndex < titleText.length) {
            displayedTitle.value = titleText.slice(0, charIndex + 1);
            charIndex++;
        } else {
            clearInterval(typingInterval);
            pauseTimeout = setTimeout(startErasing, 3000);
        }
    }, 60);
}

function startErasing() {
    isTyping.value = false;
    eraseInterval = setInterval(() => {
        if (charIndex > 0) {
            charIndex--;
            displayedTitle.value = titleText.slice(0, charIndex);
        } else {
            clearInterval(eraseInterval);
            pauseTimeout = setTimeout(startTyping, 500);
        }
    }, 30);
}

onMounted(() => {
    timer = setInterval(() => { now.value = new Date(); }, 1000);
    slideInterval = setInterval(nextSlide, 5000);
    startTyping();
});
onUnmounted(() => {
    clearInterval(timer);
    clearInterval(slideInterval);
    clearInterval(typingInterval);
    clearInterval(eraseInterval);
    clearTimeout(pauseTimeout);
});
</script>

<template>
    <section class="relative min-h-[85vh] overflow-hidden bg-primary-1 sm:min-h-[90vh]">
        <div class="absolute inset-0">
            <div
                v-for="(slide, i) in slides"
                :key="slide"
                class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
                :class="i === currentIndex ? 'opacity-30' : 'opacity-0'"
                :style="{ backgroundImage: `url(${slide})` }"
            />
        </div>
        <div class="absolute inset-0 z-10 bg-gradient-to-b from-primary-1/80 via-primary-1/60 to-primary-1" />
        <div class="relative mx-auto flex min-h-[85vh] max-w-7xl flex-col items-center justify-center px-4 py-24 text-center sm:min-h-[90vh] sm:px-6 lg:px-8">
            <img src="/brand/logo2.png" alt="" class="mb-6 h-28 w-28 object-contain sm:h-36 sm:w-36 md:h-44 md:w-44 lg:h-52 lg:w-52" aria-hidden="true" />
            <h1 class="w-full text-center font-title text-3xl font-bold leading-tight text-white sm:text-4xl md:text-5xl lg:text-6xl">
                {{ displayedTitle }}<span class="inline-block w-[3px] animate-pulse bg-accent-2 ml-1" style="height: 1em;">&nbsp;</span>
            </h1>
            <p class="mt-4 font-title text-lg font-medium text-accent-2 sm:text-xl">{{ settings.hero_date }} · {{ settings.hero_location }}</p>
            <div class="mt-8 flex justify-center gap-2 sm:gap-6">
                <div class="flex flex-col items-center rounded-lg bg-white/10 px-3 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                    <span class="font-title text-2xl font-bold text-white sm:text-3xl md:text-4xl">{{ countdown.days }}</span>
                    <span class="text-xs text-white/80 sm:text-sm">Days</span>
                </div>
                <div class="flex flex-col items-center rounded-lg bg-white/10 px-3 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                    <span class="font-title text-2xl font-bold text-white sm:text-3xl md:text-4xl">{{ countdown.hours }}</span>
                    <span class="text-xs text-white/80 sm:text-sm">Hours</span>
                </div>
                <div class="flex flex-col items-center rounded-lg bg-white/10 px-3 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                    <span class="font-title text-2xl font-bold text-white sm:text-3xl md:text-4xl">{{ countdown.minutes }}</span>
                    <span class="text-xs text-white/80 sm:text-sm">Min</span>
                </div>
                <div class="flex flex-col items-center rounded-lg bg-white/10 px-3 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                    <span class="font-title text-2xl font-bold text-white sm:text-3xl md:text-4xl">{{ countdown.seconds }}</span>
                    <span class="text-xs text-white/80 sm:text-sm">Sec</span>
                </div>
            </div>
            <p class="mt-6 max-w-3xl font-bold text-2xl leading-relaxed text-white/90 sm:text-2xl">
                {{ settings.hero_description }}
            </p>
            <Link
                href="/register-summit"
                class="mt-10 inline-flex items-center gap-2 rounded-full bg-accent-2 px-8 py-4 font-title text-base font-semibold text-primary-1 shadow-lg transition hover:bg-accent-2/90 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 sm:text-lg"
            >
                Register Now
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </Link>
        </div>
    </section>
</template>
