<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import SectionReveal from '@/Components/Home/SectionReveal.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const heroTitle = computed(() => props.settings?.hero_title ?? 'Call for Submissions');
const callSubmissionsTitle = computed(() => props.settings?.call_submissions_title ?? 'Call for Submissions');
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

const ctaParagraph1 = computed(() => props.settings?.cta_paragraph1 ?? 'If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.');
const ctaParagraph2 = computed(() => props.settings?.cta_paragraph2 ?? 'All submissions should be experiential and participatory, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.');
const ctaParagraph3 = computed(() => props.settings?.cta_paragraph3 ?? 'Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.');
const engagementTitle = computed(() => props.settings?.engagement_title ?? 'Activating the Heart');
const engagementBody = computed(() => props.settings?.engagement_body ?? '');
const visioningTitle = computed(() => props.settings?.visioning_title ?? 'Deep Visioning');
const visioningBody = computed(() => props.settings?.visioning_body ?? '');
const ctaCards = computed(() => {
    const list = props.settings?.cta_cards;
    if (Array.isArray(list) && list.length) return list.filter(c => c && c.href);
    return [
        { href: 'https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form', title: 'Submit Your Application', subtitle: 'Complete the application form by 31st March', style: 'primary' },
        { href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', title: 'Full Submission Guidelines', subtitle: 'View details, eligibility criteria and guidelines', style: 'secondary' },
    ];
});

const spreadTitle = computed(() => props.settings?.spread_title || 'Spread the word!');
const spreadBody = computed(() => props.settings?.spread_body || 'PENDING: Communication Toolkit will be provided by COFSA soon.');
const spreadPending = computed(() => props.settings?.spread_pending || '');
const spreadPdfUrl = computed(() => props.settings?.spread_pdf_url || '');
const spreadTrelloUrl = computed(() => props.settings?.spread_trello_url || '');

const spreadBodyParagraphs = computed(() => {
    const raw = spreadBody.value || '';
    return String(raw)
        .split(/\r?\n|\\n/)
        .map(p => p.trim())
        .filter(Boolean);
});

const partnersTitle = computed(() => props.settings?.partners_title || 'Together with our partners');
const partnersBody = computed(() => props.settings?.partners_body || 'This Summit is made possible through strong partnerships');
const partnersPending = computed(() => props.settings?.partners_pending || 'PENDING: COFSA WILL GIVE YOU ALL THE PARTNER LOGOS');
const partnerLogos = computed(() => (Array.isArray(props.settings?.partners_logos) ? props.settings.partners_logos.filter(v => v && v.trim()) : []));

const financialTitle = computed(() => props.settings?.financial_title || 'Financial support to expand Summit impact');
const financialBody = computed(() => props.settings?.financial_body || '');
</script>

<template>
    <Head title="Get Involved" />
    <EventLayout>
        <!-- 1) Call for Submissions -->
        <section class="relative bg-[#F4F1EA] py-0">
            <div class="w-full">
                <div class="overflow-hidden rounded-none">
                    <div class="relative w-full min-h-[28vh] sm:min-h-[32vh] lg:min-h-[36vh]">
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
                                <p class="mx-auto mt-4 text-center font-body text-lg font-semibold leading-relaxed text-white sm:text-xl">
                                    {{ heroSubtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Four cards section -->
        <section class="bg-[#F4F1EA] pt-8 pb-10 sm:pt-10 sm:pb-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto mb-6 max-w-5xl text-left sm:mb-8">
                    <p class="font-body text-base font-medium leading-relaxed text-copy sm:text-lg">
                        {{ ctaParagraph1 }}
                    </p>
                </div>
                <SectionReveal>
                    <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-2">
                        <!-- Call for submissions card -->
                        <div class="rounded-none border border-primary-1/10 bg-white p-6 shadow-sm sm:p-8">
                            <h2 class="font-title text-xl font-bold uppercase tracking-wide text-primary-1 sm:text-2xl">
                            {{ callSubmissionsTitle }}
                            </h2>
                            <p class="mt-3 font-body text-base font-medium leading-relaxed text-copy sm:text-lg">
                                {{ heroIntro }}
                            </p>

                            <div class="mt-6 flex flex-col gap-3 border-t border-primary-1/10 pt-6">
                                <a
                                    v-for="(card, index) in ctaCards"
                                    :key="index"
                                    :href="card.href"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="group flex items-center justify-between rounded-none px-5 py-4 shadow-sm transition sm:px-6"
                                    :class="card.style === 'primary'
                                        ? 'bg-accent-1 text-white hover:bg-accent-1/90 hover:shadow-md'
                                        : 'border-2 border-primary-1 bg-white text-primary-1 hover:bg-primary-1/5 hover:shadow-md'"
                                >
                                    <div class="text-left">
                                        <p class="font-body text-base font-bold sm:text-lg">
                                            {{ card.title }}
                                        </p>
                                        <p
                                            class="mt-1 font-body text-base"
                                            :class="card.style === 'primary' ? 'text-white/90' : 'text-copy'"
                                        >
                                            {{ card.subtitle }}
                                        </p>
                                    </div>
                                    <svg class="h-5 w-5 shrink-0 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="rounded-none border border-[#D7B241] bg-[#E5A000] p-6 shadow-sm sm:p-8">
                            <h2 class="font-title text-xl font-bold uppercase tracking-wide text-white sm:text-2xl">
                                {{ engagementTitle }}
                            </h2>
                            <div
                                class="mt-3 font-body text-base font-semibold leading-relaxed text-white sm:text-lg [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mb-1 [&_strong]:font-bold"
                                v-html="engagementBody || ctaParagraph2"
                            />
                        </div>

                        <div class="rounded-none border border-[#D7B241] bg-[#E5A000] p-6 shadow-sm sm:p-8">
                            <h2 class="font-title text-xl font-bold uppercase tracking-wide text-white sm:text-2xl">
                                {{ visioningTitle }}
                            </h2>
                            <p class="mt-3 font-body text-base font-semibold leading-relaxed text-white sm:text-lg whitespace-pre-line">
                                {{ visioningBody || ctaParagraph3 }}
                            </p>
                        </div>

                        <!-- Communication toolkit card -->
                        <div class="rounded-none border border-primary-1/10 bg-white p-6 shadow-sm sm:p-8">
                            <h2 class="font-title text-xl font-bold uppercase tracking-wide text-primary-1 sm:text-2xl">
                                {{ spreadTitle }}
                            </h2>
                            <div class="mt-3 space-y-3">
                                <p
                                    v-for="(para, index) in spreadBodyParagraphs"
                                    :key="index"
                                    class="font-body text-base font-medium leading-relaxed text-copy sm:text-lg"
                                >
                                    {{ para }}
                                </p>
                            </div>
                            <p v-if="spreadPending" class="mt-3 font-body text-base font-semibold text-primary-1">
                                {{ spreadPending }}
                            </p>
                            <div v-if="spreadPdfUrl || spreadTrelloUrl" class="mt-5 flex flex-wrap gap-3">
                                <a
                                    v-if="spreadPdfUrl"
                                    :href="spreadPdfUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex min-w-32 items-center justify-center rounded-none bg-accent-1 px-6 py-2.5 font-body text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-accent-1/90"
                                >
                                    PDF
                                </a>
                                <a
                                    v-if="spreadTrelloUrl"
                                    :href="spreadTrelloUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex min-w-32 items-center justify-center rounded-none bg-accent-1 px-6 py-2.5 font-body text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-accent-1/90"
                                >
                                    Trello
                                </a>
                            </div>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <!-- 3) Together with our partners -->
        <section class="bg-[#F4F1EA] py-8 sm:py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                            {{ partnersTitle }}
                        </h2>
                        <p class="mx-auto mt-3 max-w-3xl text-center font-body text-base leading-relaxed text-copy sm:text-lg">
                            {{ partnersBody }}
                        </p>
                        <p v-if="partnersPending" class="mx-auto mt-3 max-w-3xl text-center font-body text-base font-semibold text-primary-1">
                            {{ partnersPending }}
                        </p>
                    </div>

                    <div v-if="partnerLogos.length" class="mx-auto mt-8 max-w-6xl">
                        <div class="flex gap-3 overflow-x-auto pb-2 justify-center">
                            <div
                                v-for="(logo, index) in partnerLogos"
                                :key="logo + index"
                                class="flex h-16 w-24 shrink-0 items-center justify-center rounded-none border border-primary-1/10 bg-white p-2 shadow-sm"
                            >
                                <img :src="logo" alt="Partner logo" class="h-full w-full object-contain" loading="lazy" />
                            </div>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <!-- 4) Financial support -->
        <section class="bg-[#F4F1EA] py-8 sm:py-10 lg:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl rounded-none border border-primary-1/10 bg-white p-6 shadow-sm sm:p-8 text-left">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                            {{ financialTitle }}
                        </h2>
                        <div class="mt-4 space-y-4">
                            <p
                                v-for="(para, index) in (financialBody || '').split('\\n').map(p => p.trim()).filter(Boolean)"
                            :key="index"
                                class="text-left font-body text-base leading-relaxed text-copy sm:text-lg"
                            >
                                {{ para }}
                            </p>
                            </div>
                    </div>
                </SectionReveal>
            </div>
        </section>
    </EventLayout>
</template>
