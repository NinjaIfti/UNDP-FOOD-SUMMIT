<script setup>
import { computed, ref } from 'vue';
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
const speakersPerView = 4;

const visibleSpeakers = computed(() => {
    const start = currentIndex.value;
    const end = start + speakersPerView;
    return speakers.value.slice(start, end);
});

const canGoPrev = computed(() => currentIndex.value > 0);
const canGoNext = computed(() => currentIndex.value + speakersPerView < speakers.value.length);

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
    <section id="speakers" class="scroll-mt-24 bg-gray-50 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <!-- Title -->
                <div class="mb-12 text-center">
                    <h2 class="mx-auto font-title text-3xl font-bold uppercase tracking-wide text-gray-900 sm:text-4xl">
                        {{ settings?.speakers_section_title || 'EVENT SPEAKERS' }}
                    </h2>
                </div>

                <!-- Speakers Carousel -->
                <div class="relative">
                    <!-- Previous Arrow -->
                    <button
                        @click="goPrev"
                        :disabled="!canGoPrev"
                        :class="{ 'opacity-30 cursor-not-allowed': !canGoPrev }"
                        class="absolute left-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white p-3 shadow-lg transition hover:bg-gray-50 disabled:hover:bg-white"
                    >
                        <svg class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Speakers Grid -->
                    <div class="mx-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                        <div
                            v-for="speaker in visibleSpeakers"
                            :key="speaker.name"
                            class="group flex flex-col items-center text-center"
                        >
                            <!-- Circular Image with Border -->
                            <div class="relative mb-4">
                                <div class="h-48 w-48 overflow-hidden rounded-full border-4 border-gray-900 bg-white p-1">
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
                                <h3 class="font-title text-base font-bold uppercase tracking-wide text-gray-900">
                                    {{ speaker.name }}
                                </h3>
                                <p class="mt-1 font-body text-sm font-semibold uppercase tracking-wide text-red-600">
                                    {{ speaker.role }}
                                </p>
                                <p class="mt-3 font-body text-base leading-relaxed text-gray-600 sm:text-lg">
                                    {{ speaker.bio }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Arrow -->
                    <button
                        @click="goNext"
                        :disabled="!canGoNext"
                        :class="{ 'opacity-30 cursor-not-allowed': !canGoNext }"
                        class="absolute right-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white p-3 shadow-lg transition hover:bg-gray-50 disabled:hover:bg-white"
                    >
                        <svg class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                
               
            </SectionReveal>
        </div>
    </section>
</template>
