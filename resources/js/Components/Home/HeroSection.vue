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

const defaultSlides = ['/brand/img55.jpg', '/brand/img34.jpg', '/brand/img32.jpg', '/brand/img30.jpg', '/brand/img22.jpg'];
const slides = computed(() => {
    if (props.settings?.hero_slides && Array.isArray(props.settings.hero_slides) && props.settings.hero_slides.length > 0) {
        return props.settings.hero_slides.filter(slide => slide && slide.trim());
    }
    return defaultSlides;
});
const currentIndex = ref(0);

function nextSlide() {
    if (slides.value.length > 0) {
        currentIndex.value = (currentIndex.value + 1) % slides.value.length;
    }
}

onMounted(() => {
    timer = setInterval(() => { now.value = new Date(); }, 1000);
    slideInterval = setInterval(nextSlide, 6000);
});
onUnmounted(() => {
    clearInterval(timer);
    clearInterval(slideInterval);
});
</script>

<template>
    <section class="relative -mt-20 min-h-[calc(90vh+5rem)] overflow-hidden bg-primary-1 sm:-mt-24 sm:min-h-[calc(92vh+6rem)] lg:-mt-28 lg:min-h-[calc(92vh+7rem)] pt-20 sm:pt-24 lg:pt-28">

        <!-- Slides with Ken Burns soft zoom -->
        <div class="absolute inset-0">
            <div
                v-for="(slide, i) in slides"
                :key="slide + i"
                class="absolute inset-0 bg-cover bg-center"
                :class="i === currentIndex ? 'hero-slide-active' : 'hero-slide-inactive'"
                :style="{ backgroundImage: `url(${slide})` }"
            />
        </div>

        <!-- Dark gradient overlay — heavier on left so text is readable -->
        <div class="absolute inset-0 z-10 bg-gradient-to-r from-primary-1/85 via-primary-1/50 to-primary-1/10" />
        <div class="absolute inset-0 z-10 bg-gradient-to-t from-primary-1/60 via-transparent to-primary-1/30" />

        <!-- Content — left aligned -->
        <div class="relative z-20 mx-auto flex min-h-[90vh] max-w-7xl flex-col justify-center px-6 py-24 sm:px-10 lg:px-16">
            <div class="max-w-2xl">

                <h1 class="font-title text-4xl font-bold leading-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                    {{ settings.hero_title }}
                </h1>

                <p class="mt-5 font-body text-lg font-medium text-white/80 sm:text-xl">
                    {{ settings.hero_date }} · {{ settings.hero_location }}
                </p>

                <!-- Countdown -->
                <div class="mt-8 flex flex-wrap gap-3 sm:gap-4">
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.days }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Days</span>
                    </div>
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.hours }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Hours</span>
                    </div>
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.minutes }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Minutes</span>
                    </div>
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 backdrop-blur-sm sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.seconds }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Seconds</span>
                    </div>
                </div>

                <!-- Register button -->
                <Link
                    href="/register-summit"
                    class="mt-8 inline-flex items-center gap-2 rounded-full bg-accent-2 px-8 py-4 font-body text-base font-semibold text-primary-1 shadow-lg transition hover:bg-accent-2/90 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 sm:text-lg"
                >
                    Register Now
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero-slide-active {
    opacity: 1;
    animation: kenBurns 8s ease-in-out forwards;
    transition: opacity 1.5s ease-in-out;
}

.hero-slide-inactive {
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
}

@keyframes kenBurns {
    0%   { transform: scale(1.0) translateX(0); }
    100% { transform: scale(1.08) translateX(-1%); }
}
</style>
