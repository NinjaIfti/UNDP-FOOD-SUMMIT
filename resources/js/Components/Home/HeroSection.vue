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
const slideOrientation = ref({});

function onSlideLoad(i, e) {
    const img = e.target;
    if (!img.naturalWidth || !img.naturalHeight) return;
    slideOrientation.value = {
        ...slideOrientation.value,
        [i]: img.naturalHeight >= img.naturalWidth ? 'portrait' : 'landscape',
    };
}

function slideImgClass(i) {
    const o = slideOrientation.value[i];
    if (o === 'portrait') {
        return 'hero-slide-img hero-slide-img-portrait';
    }
    if (o === 'landscape') {
        return 'hero-slide-img hero-slide-img-landscape';
    }
    return 'hero-slide-img hero-slide-img-landscape';
}

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
    <section class="relative -mt-20 min-h-screen overflow-hidden bg-primary-1 sm:-mt-24 lg:-mt-28 pt-20 sm:pt-24 lg:pt-28">

        <!-- Slides: full-bleed layer. Landscape = cover fills section (no grey bars). Portrait = cover fills full width (zoom/crop top-bottom only). -->
        <div class="absolute -top-20 bottom-0 left-0 right-0 z-0 min-h-screen w-full overflow-hidden bg-primary-1 sm:-top-24 lg:-top-28">
            <div
                v-for="(slide, i) in slides"
                :key="slide + i"
                class="absolute inset-0 overflow-hidden bg-primary-1"
                :class="i === currentIndex ? 'hero-slide-active' : 'hero-slide-inactive'"
            >
                <img
                    :src="slide"
                    alt=""
                    :class="slideImgClass(i)"
                    :loading="i === 0 ? 'eager' : 'lazy'"
                    decoding="async"
                    @load="onSlideLoad(i, $event)"
                />
            </div>
        </div>

        <!-- Content -->
        <div class="relative z-20 mx-auto flex min-h-[90vh] max-w-7xl flex-col items-center justify-center px-6 py-24 sm:px-10 lg:px-16">
            <div class="max-w-3xl text-center">

                <h1 class="font-title text-4xl font-bold leading-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
                    {{ settings.hero_title }}
                </h1>

                <p class="mt-3 w-full text-center font-body text-2xl font-semibold text-white sm:text-3xl">
                    {{ settings.hero_tagline || 'Activating the heart' }}
                </p>

                <p class="mt-5 w-full font-body text-xl font-semibold text-white/90 sm:text-2xl text-center">
                    {{ settings.hero_date }} · {{ settings.hero_location }}
                </p>

                <p
                    v-if="settings.hero_description"
                    class="mt-4 max-w-3xl mx-auto font-body text-xl text-white/90 sm:text-2xl text-center"
                >
                    {{ settings.hero_description }}
                </p>

                <!-- Countdown -->
                <div class="mt-8 flex flex-wrap justify-center gap-3 sm:gap-4">
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.days }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Days</span>
                    </div>
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.hours }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Hours</span>
                    </div>
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.minutes }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Minutes</span>
                    </div>
                    <div class="flex flex-col items-center rounded-lg bg-black/40 px-4 py-3 sm:px-6 sm:py-4">
                        <span class="font-body text-2xl font-bold text-white sm:text-3xl">{{ countdown.seconds }}</span>
                        <span class="font-body text-xs text-white/70 sm:text-sm">Seconds</span>
                    </div>
                </div>

                <!-- Register button -->
                <Link
                    href="/register-summit"
                    class="mt-8 inline-flex items-center gap-2 rounded-full bg-accent-2 px-8 py-4 font-body text-base font-semibold text-white shadow-lg transition hover:bg-accent-2/90 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 sm:text-lg mx-auto"
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
/* Landscape: cover entire slide so no grey bands at sides or below */
.hero-slide-img-landscape {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

/*
 * Portrait only: scale by width so the photo always spans full screen width;
 * taller-than-wide images get top/bottom cropped (zoom), no side grey.
 */
.hero-slide-img-portrait {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    display: block;
}

.hero-slide-active {
    opacity: 1;
    transition: opacity 1.5s ease-in-out;
}

.hero-slide-inactive {
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
}
</style>
