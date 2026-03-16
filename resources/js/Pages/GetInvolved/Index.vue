<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import SectionReveal from '@/Components/Home/SectionReveal.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const heroTitle = computed(() => props.settings?.hero_title ?? 'Call for Submissions');
const heroSubtitle = computed(() => props.settings?.hero_subtitle ?? 'We warmly invite you to co-create the agenda of the first-ever Global Conscious Food Systems Summit, taking place in Bhutan from 31 August – 4 September 2026.');
const heroIntro = computed(() => props.settings?.hero_intro ?? 'CoFSA invites submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.');

const heroSlides = computed(() => {
    const base = props.settings?.hero_image || '/brand/img34.jpg';
    const extras = Array.isArray(props.settings?.hero_slides)
        ? props.settings.hero_slides.filter(src => src && typeof src === 'string' && src.trim().length > 0)
        : [];
    const all = [base, ...extras];
    return all.filter((src, index) => all.indexOf(src) === index);
});

const activeHeroSlide = ref(0);

let heroInterval;

onMounted(() => {
    heroInterval = setInterval(() => {
        if (heroSlides.value.length > 1) {
            activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.value.length;
        }
    }, 7000);
});

onUnmounted(() => {
    if (heroInterval) {
        clearInterval(heroInterval);
    }
});

const categoriesSectionTitle = computed(() => props.settings?.categories_section_title ?? 'Submission Categories');
const categoriesSectionSubtitle = computed(() => props.settings?.categories_section_subtitle ?? 'Applicants may submit one or more proposals under the following categories:');
const submissionCategories = computed(() => {
    const list = props.settings?.categories;
    if (Array.isArray(list) && list.length) return list.filter(c => c && (c.title || c.description));
    return [
        { title: 'Whole Session', description: 'For applicants who propose to design and facilitate a full session during the Summit...', icon: 'presentation' },
        { title: 'Case Study', description: 'For individuals or organizations not yet ready to propose a full interactive session...', icon: 'document' },
    ];
});

const ctaParagraph1 = computed(() => props.settings?.cta_paragraph1 ?? 'If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.');
const ctaParagraph2 = computed(() => props.settings?.cta_paragraph2 ?? 'All submissions should be experiential and participatory, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.');
const ctaParagraph3 = computed(() => props.settings?.cta_paragraph3 ?? 'Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.');
const ctaCards = computed(() => {
    const list = props.settings?.cta_cards;
    if (Array.isArray(list) && list.length) return list.filter(c => c && c.href);
    return [
        { href: 'https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form', title: 'Submit Your Application', subtitle: 'Complete the application form by 31st March', style: 'primary' },
        { href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', title: 'Full Submission Guidelines', subtitle: 'View details, eligibility criteria and guidelines', style: 'secondary' },
    ];
});
</script>

<template>
    <Head title="Get Involved" />
    <EventLayout>
        <!-- Hero header with image & text inside -->
        <section class="relative bg-[#F4F1EA] py-0">
            <div class="w-full">
                <div class="overflow-hidden rounded-none">
                    <div class="relative w-full min-h-[35vh] sm:min-h-[40vh] lg:min-h-[45vh]">
                        <div
                            v-for="(slide, index) in heroSlides"
                            :key="slide + index"
                            class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                            :class="index === activeHeroSlide ? 'opacity-100' : 'opacity-0'"
                        >
                            <img
                                :src="slide"
                                alt=""
                                class="h-full w-full object-cover"
                                loading="lazy"
                            />
                        </div>
                        <div class="absolute inset-0 bg-black/45" />
                        <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-8">
                            <div class="max-w-4xl text-center">
                                <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl lg:text-5xl">
                                    {{ heroTitle }}
                                </h1>
                                <p class="mt-4 font-body text-lg font-semibold leading-relaxed text-white sm:text-xl">
                                    {{ heroSubtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-[#F4F1EA] pt-8 pb-10 sm:pt-10 sm:pb-14 lg:pb-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl rounded-none bg-white p-6 shadow-sm sm:p-8">
                        <p class="font-body text-base leading-relaxed text-copy sm:text-lg">{{ heroIntro }}</p>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <section class="bg-[#F4F1EA] pt-6 pb-10 sm:pt-8 sm:pb-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">{{ categoriesSectionTitle }}</h2>
                        <p class="mx-auto mt-3 max-w-3xl text-center font-body text-base leading-relaxed text-copy sm:text-lg">{{ categoriesSectionSubtitle }}</p>
                    </div>

                    <div class="mx-auto mt-8 grid max-w-5xl gap-6 sm:mt-10 lg:grid-cols-2">
                        <div
                            v-for="(category, index) in submissionCategories"
                            :key="index"
                            class="flex flex-col rounded-none border border-primary-2/20 bg-white p-6 shadow-sm transition hover:shadow-md"
                        >
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-none bg-accent-2">
                                    <svg v-if="category.icon === 'presentation'" class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                    </svg>
                                    <svg v-else class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-title text-xl font-bold text-primary-1">{{ category.title }}</h3>
                                    <p class="mt-2 font-body text-base leading-relaxed text-copy">{{ category.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <section class="bg-[#F4F1EA] py-10 sm:py-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <p class="font-body text-lg leading-relaxed text-ink sm:text-xl">{{ ctaParagraph1 }}</p>
                        <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">{{ ctaParagraph2 }}</p>
                        <p class="mt-4 font-body text-base leading-relaxed text-ink sm:text-lg font-semibold">{{ ctaParagraph3 }}</p>
                    </div>

                    <div class="mx-auto mt-8 flex max-w-3xl flex-col gap-4 sm:mt-10">
                        <a
                            v-for="(card, index) in ctaCards"
                            :key="index"
                            :href="card.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            :class="(card.style === 'primary' ? 'group flex items-center justify-between rounded-none bg-accent-1 p-5 shadow-lg transition hover:bg-accent-1/90 hover:shadow-xl sm:p-6' : 'group flex items-center justify-between rounded-none border-2 border-primary-1 bg-white p-5 shadow-sm transition hover:bg-primary-1/5 hover:shadow-md sm:p-6')"
                        >
                            <div class="flex items-center gap-4">
                                <div :class="card.style === 'primary' ? 'flex h-12 w-12 shrink-0 items-center justify-center rounded-none bg-white/20' : 'flex h-12 w-12 shrink-0 items-center justify-center rounded-none bg-primary-1'">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p :class="card.style === 'primary' ? 'font-body text-lg font-bold text-white sm:text-xl' : 'font-body text-lg font-bold text-primary-1 sm:text-xl'">{{ card.title }}</p>
                                    <p class="mt-1 font-body text-sm" :class="card.style === 'primary' ? 'text-white/90' : 'text-copy'">{{ card.subtitle }}</p>
                                </div>
                            </div>
                            <svg class="h-6 w-6 shrink-0 transition group-hover:translate-x-1" :class="card.style === 'primary' ? 'text-white' : 'text-primary-1'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </SectionReveal>
            </div>
        </section>
    </EventLayout>
</template>
