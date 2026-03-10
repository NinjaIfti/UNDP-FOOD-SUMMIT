<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

const speakers = computed(() => props.settings?.speakers || [
    { name: 'Speaker One', role: 'Policy & Governance', bio: 'Leading expert in food policy and governance with over 20 years of experience.', image: '/brand/img22.jpg' },
    { name: 'Speaker Two', role: 'Agroecology', bio: 'Pioneer in agroecological practices and sustainable farming systems.', image: '/brand/img29.jpg' },
    { name: 'Speaker Three', role: 'Indigenous Knowledge', bio: 'Advocate for indigenous food systems and traditional agricultural wisdom.', image: '/brand/img30.jpg' },
    { name: 'Speaker Four', role: 'Youth & Movement Building', bio: 'Youth leader driving food systems transformation across communities.', image: '/brand/img31.jpg' },
    { name: 'Speaker Five', role: 'Food Systems', bio: 'Researcher focused on conscious and regenerative food systems.', image: '/brand/img32.jpg' },
    { name: 'Speaker Six', role: 'Sustainability', bio: 'Environmental strategist working on sustainable food supply chains.', image: '/brand/img33.jpg' },
]);

const currentIndex = ref(0);
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024);
const autoSlideInterval = ref(null);
const touchStartX = ref(0);

const speakersPerView = computed(() => {
    if (windowWidth.value < 640) return 1;
    if (windowWidth.value < 1024) return 2;
    return 4;
});

const visibleSpeakers = computed(() => {
    const start = currentIndex.value;
    const end = start + speakersPerView.value;
    return speakers.value.slice(start, end);
});

const canGoPrev = computed(() => currentIndex.value > 0);
const canGoNext = computed(() => currentIndex.value + speakersPerView.value < speakers.value.length);

function onResize() {
    windowWidth.value = window.innerWidth;
    const maxStart = Math.max(0, speakers.value.length - speakersPerView.value);
    if (currentIndex.value > maxStart) currentIndex.value = maxStart;
}

function startAutoSlide() {
    if (autoSlideInterval.value) return;
    autoSlideInterval.value = setInterval(() => {
        // Only auto-slide when there is something to slide
        if (speakers.value.length > speakersPerView.value) {
            if (canGoNext.value) {
                currentIndex.value++;
            } else {
                currentIndex.value = 0;
            }
        }
    }, 6000);
}

function stopAutoSlide() {
    if (autoSlideInterval.value) {
        clearInterval(autoSlideInterval.value);
        autoSlideInterval.value = null;
    }
}

function onTouchStart(event) {
    if (!event.touches || !event.touches.length) return;
    touchStartX.value = event.touches[0].clientX;
}

function onTouchEnd(event) {
    if (!event.changedTouches || !event.changedTouches.length) return;
    const deltaX = event.changedTouches[0].clientX - touchStartX.value;
    const threshold = 40;
    if (deltaX > threshold) {
        goPrev();
    } else if (deltaX < -threshold) {
        goNext();
    }
}

const selectedSpeaker = ref(null);

function openSpeakerModal(speaker) {
    selectedSpeaker.value = speaker;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = 'hidden';
    }
}

function closeSpeakerModal() {
    selectedSpeaker.value = null;
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
}

function onKeydown(e) {
    if (e.key === 'Escape' && selectedSpeaker.value) {
        closeSpeakerModal();
    }
}

onMounted(() => {
    window.addEventListener('resize', onResize);
    window.addEventListener('keydown', onKeydown);
    startAutoSlide();
});
onUnmounted(() => {
    window.removeEventListener('resize', onResize);
    window.removeEventListener('keydown', onKeydown);
    stopAutoSlide();
    document.body.style.overflow = '';
});

const goNext = () => {
    if (canGoNext.value) {
        currentIndex.value++;
    }
};

const goPrev = () => {
    if (canGoPrev.value) {
        currentIndex.value--;
    }
};
</script>

<template>
    <section id="speakers" class="scroll-mt-24 bg-[#F4F1EA] py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <!-- Title -->
                <div class="mb-12 text-center">
                    <h2 class="mx-auto max-w-2xl font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
                        {{ settings?.speakers_section_title || 'EVENT SPEAKERS' }}
                    </h2>
                    <p
                        v-if="settings?.speakers_section_subtitle"
                        class="mx-auto mt-4 max-w-2xl text-center font-body text-base leading-relaxed text-copy sm:text-lg"
                    >
                        {{ settings.speakers_section_subtitle }}
                    </p>
                </div>

                <!-- Speakers Carousel -->
                <div
                    class="relative"
                    @touchstart.passive="onTouchStart"
                    @touchend.passive="onTouchEnd"
                >
                    <!-- Previous Arrow (desktop only) -->
                    <button
                        @click="goPrev"
                        :disabled="!canGoPrev"
                        :class="{ 'opacity-30 cursor-not-allowed': !canGoPrev }"
                        class="absolute left-0 top-1/2 z-10 hidden -translate-y-1/2 rounded-full bg-primary-1/20 p-3 shadow-lg transition hover:bg-primary-1/30 disabled:hover:bg-primary-1/20 lg:flex"
                    >
                        <svg class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Speakers Grid -->
                    <div class="mx-6 grid grid-cols-1 gap-8 sm:mx-8 sm:grid-cols-2 lg:mx-12 lg:grid-cols-4">
                        <div
                            v-for="(speaker, cardIndex) in visibleSpeakers"
                            :key="(speaker.name || '') + '-' + currentIndex + '-' + cardIndex"
                            role="button"
                            tabindex="0"
                            class="group flex h-full cursor-pointer flex-col items-center justify-center rounded-none border border-primary-1/15 bg-primary-1/5 px-8 py-8 text-center shadow-md transition hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-accent-1"
                            @click="openSpeakerModal(speaker)"
                            @keydown.enter.prevent="openSpeakerModal(speaker)"
                            @keydown.space.prevent="openSpeakerModal(speaker)"
                        >
                            <!-- Square photo, no orange ring -->
                            <div class="relative mb-4">
                                <div class="h-44 w-44 overflow-hidden rounded-full border border-primary-1/20 bg-primary-1/10 shadow-md">
                                    <img
                                        :src="speaker.image"
                                        :alt="speaker.name"
                                        class="h-full w-full rounded-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                            </div>

                            <!-- Speaker Info -->
                            <div class="w-full px-4">
                                <h3 class="mx-auto max-w-xs font-title text-lg font-bold text-primary-1">
                                    {{ speaker.name }}
                                </h3>
                                <p class="mt-1 mx-auto max-w-xs text-center font-body text-sm font-semibold text-primary-2">
                                    {{ speaker.role }}
                                </p>
                                <p class="mt-2 mx-auto max-w-xs text-center font-body text-sm leading-snug text-copy sm:text-base">
                                    {{ speaker.bio }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Arrow (desktop only) -->
                    <button
                        @click="goNext"
                        :disabled="!canGoNext"
                        :class="{ 'opacity-30 cursor-not-allowed': !canGoNext }"
                        class="absolute right-0 top-1/2 z-10 hidden -translate-y-1/2 rounded-full bg-primary-1/20 p-3 shadow-lg transition hover:bg-primary-1/30 disabled:hover:bg-primary-1/20 lg:flex"
                    >
                        <svg class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Speaker detail popup -->
                <Teleport to="body">
                    <div
                        v-if="selectedSpeaker"
                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="speaker-modal-title"
                        @click.self="closeSpeakerModal"
                    >
                        <div
                            class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-none border border-primary-1/20 bg-[#F4F1EA] shadow-2xl"
                            @click.stop
                        >
                            <button
                                type="button"
                                class="absolute right-3 top-3 rounded-none border border-primary-1/30 bg-primary-1/10 p-2 text-primary-1 transition hover:bg-primary-1/20"
                                aria-label="Close"
                                @click="closeSpeakerModal"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div v-if="selectedSpeaker.image" class="aspect-[4/3] w-full overflow-hidden bg-primary-1/10">
                                <img
                                    :src="selectedSpeaker.image"
                                    :alt="selectedSpeaker.name"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                />
                            </div>
                            <div class="p-6 sm:p-8">
                                <h3 id="speaker-modal-title" class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">
                                    {{ selectedSpeaker.name }}
                                </h3>
                                <p v-if="selectedSpeaker.role" class="mt-2 font-body text-base font-semibold text-primary-2">
                                    {{ selectedSpeaker.role }}
                                </p>
                                <p v-if="selectedSpeaker.bio" class="mt-4 whitespace-pre-line font-body text-base leading-relaxed text-ink">
                                    {{ selectedSpeaker.bio }}
                                </p>
                                <div v-if="selectedSpeaker.details" class="mt-4 border-t border-primary-1/15 pt-4">
                                    <p class="whitespace-pre-line font-body text-base leading-relaxed text-copy">
                                        {{ selectedSpeaker.details }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </Teleport>
            </SectionReveal>
        </div>
    </section>
</template>
