<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import OnlineSpeakersSection from '@/Components/Home/OnlineSpeakersSection.vue';
import HostSection from '@/Components/Home/HostSection.vue';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
});

const speakers = computed(() => props.settings?.speakers || []);
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
    window.addEventListener('keydown', onKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', onKeydown);
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
});
</script>

<template>
    <Head title="Speakers" />
    <EventLayout>
        <section class="bg-[#F4F1EA] py-16 sm:py-24 lg:py-1 mb-10 mt-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl lg:text-5xl">
                        {{ settings.speakers_section_title || 'Speakers' }}
                    </h1>
                    <p
                        v-if="settings.speakers_section_subtitle"
                        class="mx-auto mt-4 max-w-2xl text-center font-body text-base leading-relaxed text-copy sm:text-lg lg:text-xl"
                    >
                        {{ settings.speakers_section_subtitle }}
                    </p>
                </div>

                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div
                        v-for="(speaker, index) in speakers"
                        :key="(speaker.name || '') + '-' + index"
                        role="button"
                        tabindex="0"
                        class="flex h-full flex-col items-center rounded-none border border-primary-1/15 bg-primary-1/5 px-6 py-8 text-center shadow-md cursor-pointer transition hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-accent-1"
                        @click="openSpeakerModal(speaker)"
                        @keydown.enter.prevent="openSpeakerModal(speaker)"
                        @keydown.space.prevent="openSpeakerModal(speaker)"
                    >
                        <div class="mb-4">
                            <div class="h-40 w-40 overflow-hidden rounded-full border border-primary-1/20 bg-primary-1/10 shadow-md">
                                <img
                                    :src="speaker.image"
                                    :alt="speaker.name"
                                    class="h-full w-full rounded-full object-cover"
                                    loading="lazy"
                                />
                            </div>
                        </div>

                        <h2 class="mx-auto max-w-xs font-title text-lg font-bold text-primary-1">
                            {{ speaker.name }}
                        </h2>
                        <p v-if="speaker.role" class="mt-1 mx-auto w-full max-w-xs text-center font-body text-sm font-semibold text-primary-2">
                            {{ speaker.role }}
                        </p>
                        <p v-if="speaker.bio" class="mt-2 mx-auto max-w-xs font-body text-sm leading-snug text-copy sm:text-base">
                            {{ speaker.bio }}
                        </p>
                    </div>
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
                                <h2 id="speaker-modal-title" class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">
                                    {{ selectedSpeaker.name }}
                                </h2>
                                <p v-if="selectedSpeaker.role" class="mt-2 font-body text-base font-semibold text-primary-2">
                                    {{ selectedSpeaker.role }}
                                </p>
                                <p v-if="selectedSpeaker.bio" class="mt-4 whitespace-pre-line font-body text-base leading-relaxed text-ink">
                                    {{ selectedSpeaker.bio }}
                                </p>
                                <div v-if="selectedSpeaker.details" class="mt-4 border-top border-primary-1/15 pt-4">
                                    <p class="whitespace-pre-line font-body text-base leading-relaxed text-copy">
                                        {{ selectedSpeaker.details }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </Teleport>
            </div>
        </section>
		<OnlineSpeakersSection :settings="settings" />
	        <HostSection :settings="settings" />
    </EventLayout>
</template>

