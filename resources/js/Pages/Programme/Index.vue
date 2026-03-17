<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import SectionReveal from '@/Components/Home/SectionReveal.vue';
// import EventsPlanSection from '@/Components/Home/EventsPlanSection.vue';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
});

const themeIndex = ref(0);
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024);

const sectionTitle = computed(() => props.settings?.transform_title || 'A Transformative Summit Experience');

const transformSlides = computed(() => {
    const base = props.settings?.transform_image || '/brand/img34.jpg';
    const extras = Array.isArray(props.settings?.transform_slides)
        ? props.settings.transform_slides.filter(src => src && typeof src === 'string' && src.trim().length > 0)
        : [];
    const all = [base, ...extras];
    // Remove duplicates while preserving order
    return all.filter((src, index) => all.indexOf(src) === index);
});

const activeTransformSlide = ref(0);

let transformInterval;

onMounted(() => {
    transformInterval = setInterval(() => {
        if (transformSlides.value.length > 1) {
            activeTransformSlide.value = (activeTransformSlide.value + 1) % transformSlides.value.length;
        }
    }, 7000);
});

onUnmounted(() => {
    if (transformInterval) {
        clearInterval(transformInterval);
    }
});

const themesPerView = computed(() => {
    if (windowWidth.value < 640) return 1;
    if (windowWidth.value < 1024) return 2;
    return 3;
});

const themes = computed(() => {
    const list = props.settings?.themes;
    if (Array.isArray(list) && list.length) return list.filter(t => t && (t.title || t.description));
    return defaultThemes;
});

const defaultGlobalCards = [
    {
        title: 'Connect & Collaborate',
        body: 'Bring together 350–500 changemakers — governments, farmers, youth, civil society, researchers, and private sector — in meaningful dialogue and co-creation.',
    },
    {
        title: 'Amplify Impact',
        body: 'Share knowledge, innovations, and stories that inspire systemic change — bridging local wisdom with global action to advance conscious food systems worldwide.',
    },
    {
        title: 'Drive Transformation',
        body: 'Forge commitments, partnerships, and pathways that outlast the Summit — embedding conscious values into food policy, practice, and culture at every level.',
    },
];

const globalCards = computed(() => {
    const list = props.settings?.global_cards;
    if (Array.isArray(list) && list.length) {
        return list
            .filter(card => card && (card.title || card.body))
            .map(card => ({
                title: card.title || '',
                body: card.body || '',
            }));
    }
    return defaultGlobalCards;
});

const highlightsSubtitle = computed(() => props.settings?.programme_highlights_subtitle || 'Programme Highlights: Exploring the Summit Day by Day');

const highlightsTitle = computed(() => {
    const raw = highlightsSubtitle.value || '';
    const idx = raw.indexOf(':');
    if (idx === -1) return raw;
    return raw.slice(0, idx).trim();
});

const highlightsDescription = computed(() => {
    const raw = highlightsSubtitle.value || '';
    const idx = raw.indexOf(':');
    if (idx === -1) return '';
    return raw.slice(idx + 1).trim();
});

function splitHighlightTitle(fullTitle) {
    const t = (fullTitle || '').trim();
    const idx = t.indexOf('.');
    if (idx === -1) {
        return { date: t, subtitle: '' };
    }
    return {
        date: t.slice(0, idx).trim(),
        subtitle: t.slice(idx + 1).trim(),
    };
}

const defaultHighlights = [
    {
        title: 'Monday, 31 August 2026. Stepping into Bhutan through immersive experiences (optional)',
        body: 'Take advantage of this day to connect with the Bhutanese nature, people, spirituality and the philosophy of Gross National Happiness through immersive experiences exploring conscious food systems. Meet farmers, learn how food, culture, and ecology are deeply interwoven in everyday life, or visit the iconic Tiger’s Nest Monastery.',
    },
    {
        title: 'Tuesday, 01 September 2026. Igniting Consciousness for Systemic Transformation (High level segment)',
        body: 'Kick off the summit with visionary leaders and changemakers exploring how consciousness can catalyze systemic transformation. Through the opening ceremony, inspiring dialogues and experiential activities, participants will reflect on conscious leadership and connect with the deeper purpose and values driving the global conscious food systems movement.',
    },
    {
        title: 'Wednesday, 02 September 2026. Exploring Conscious Food Systems in practice',
        body: 'Celebrate the release of our collective vision for conscious food systems and travel across continents through pioneering stories, models and practices. In interactive sessions and sharing circles, realize the power of healing for food systems transformation and the role of inner capacities in the agroecological transition.',
    },
    {
        title: 'Thursday, 03 September, 2026. Bhutan: A Living Laboratory for Conscious Food Systems',
        body: 'Immerse yourself in Bhutan’s unique approach to food systems, where culture, spirituality, and the philosophy of Gross National Happiness guide nature-friendly agriculture. Experience local practices and products firsthand and explore how indigenous knowledge, faith-based perspectives and consciousness practices can foster regenerative food systems and conscious consumption across the world.',
    },
    {
        title: 'Friday, 04 September, 2026. From Inspiration to Collective Action',
        body: 'Conclude the summit by exploring pathways to scale conscious food systems through enabling policy, effective governance, and collaborative strategies that turn ideas into action. Share and listen to personal insights and commitments in circles and co-create collective recommendations before celebrating achievements with a vibrant cultural show and festive closing party.',
    },
];

const programmeHighlights = computed(() => {
    const list = props.settings?.programme_highlights;
    if (Array.isArray(list) && list.length) {
        const cleaned = list
            .filter(item => item && (item.title || item.body))
            .map(item => ({
                title: item.title || '',
                body: item.body || '',
            }));
        if (cleaned.length) return cleaned;
    }
    return defaultHighlights;
});

const totalPages = computed(() => Math.ceil(themes.value.length / themesPerView.value));
const canPrevTheme = computed(() => themeIndex.value > 0);
const canNextTheme = computed(() => themeIndex.value < totalPages.value - 1);
function prevThemes() { if (canPrevTheme.value) themeIndex.value--; }
function nextThemes() {
    if (canNextTheme.value) themeIndex.value++;
    else themeIndex.value = 0;
}
const autoSlidePaused = ref(false);
let autoSlideInterval;

function onResize() {
    windowWidth.value = window.innerWidth;
    const maxIndex = Math.max(0, totalPages.value - 1);
    if (themeIndex.value > maxIndex) themeIndex.value = maxIndex;
}

onMounted(() => {
    window.addEventListener('resize', onResize);
    autoSlideInterval = setInterval(() => {
        if (!autoSlidePaused.value) nextThemes();
    }, 5000);
});
onUnmounted(() => {
    window.removeEventListener('resize', onResize);
    clearInterval(autoSlideInterval);
});

const cardSectionIds = ['call-for-submissions', 'core-themes', 'global-movement'];

function scrollToCardSection(index) {
    const id = cardSectionIds[index];
    if (!id) return;
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

const defaultThemes = [
    {
        number: 1,
        title: "Farmers' wellbeing and the inner dimension of agroecology",
        description: "This area focuses on the wellbeing of farmers and producers (including foresters, livestock herders and fisherworkers) and the inner dimensions of agroecology. It explores how awareness, care, dignity, and connection to Nature influence sustainable farming practices, resilience, decision making, and regenerative land stewardship.",
        image: '/brand/themes/1.jpg',
        contact: 'jimena.gomez@undp.org',
        lead: 'Jimena Gomez'
    },
    {
        number: 2,
        title: "Conscious food policy and governance",
        description: "This workstream examines how values, awareness, mindsets and inner capacities can shape food related policies, governance mechanisms, and investment flows. It includes reflections on equitable and ethical decision-making, participatory governance, long-term thinking, and aligning public and private investments with regenerative and sustainable food systems.",
        image: '/brand/themes/2.jpg',
        contact: 'charlotte.dufour@undp.org',
        lead: 'Charlotte Dufour'
    },
    {
        number: 3,
        title: "Conscious consumption",
        description: "This theme explores the relationship between consumers and food, emphasizing responsibility, awareness, and connection. It includes themes such as farmer–consumer relationships, farmers' markets and short food chains, public procurement and institutional food systems, conscious food choices and food citizenship, school-based approaches to conscious food, the role of chefs and catering.",
        image: '/brand/themes/3.jpg',
        contact: 'charlotte.dufour@undp.org',
        lead: 'Charlotte Dufour'
    },
    {
        number: 4,
        title: "Farmers-consumers relations & mindful markets",
        description: "This sub-theme focuses on strengthening direct, transparent, and values-based relationships between farmers and consumers, including intermediaries in the relationship. It explores mindful markets, short food chains, fair pricing, trust-building, and community-supported models that support farmer livelihoods while fostering consumer awareness, responsibility, and connection to food systems.",
        image: '/brand/themes/4.jpg',
        contact: 'jimena.gomez@undp.org',
        lead: 'Jimena Gomez'
    },
    {
        number: 5,
        title: "Traditional wisdom for conscious food systems",
        description: "This area highlights traditional and Indigenous wisdom, ancestral practices, and traditional food cultures that embody deep relationships with land, community, and spirit. It explores how Indigenous and traditional wisdom informs sustainable, ethical, and regenerative food systems today.",
        image: '/brand/themes/5.jpg',
        contact: 'luz.navarro@undp.org',
        lead: 'Luz Elena Navarro'
    },
    {
        number: 6,
        title: "Regenerative local cultures",
        description: "This workstream focuses on place-based food systems and the cultural, social, and relational dimensions that sustain them. It includes community-led initiatives, local food cultures, collective care, and cultural regeneration as foundations for resilient food systems.",
        image: '/brand/themes/6.jpg',
        contact: 'luz.navarro@undp.org',
        lead: 'Luz Elena Navarro'
    },
    {
        number: 7,
        title: "Conscious organisational culture and leadership",
        description: "This area explores how inner capacities and leadership practices—such as self-awareness, empathy, trust, and purpose—can transform organizations working in food systems. It includes conscious leadership, organizational wellbeing, collaborative cultures, and values-driven governance.",
        image: '/brand/themes/7.jpg',
        contact: 'thomas.legrand@undp.org',
        lead: 'Thomas Legrand'
    },
    {
        number: 8,
        title: "Youth engagement in conscious food systems",
        description: "This theme centers on young people as agents of transformation, exploring how youth cultivate purpose, agency, leadership, and inner resilience within food systems. Submissions may highlight youth-led initiatives, intergenerational learning, education, and movement-building.",
        image: '/brand/themes/8.jpg',
        contact: 'noemi.altobelli@undp.org',
        lead: 'Noemi Altobelli'
    },
    {
        number: 9,
        title: "Conscious food systems in research and higher education",
        description: "This workstream examines how universities, research institutions, and learning spaces can integrate inner development, ethics, reflection, and transdisciplinary approaches into food systems research and education.",
        image: '/brand/themes/9.jpg',
        contact: 'abigail.stein@undp.org',
        lead: 'Abigail Stein'
    },
    {
        number: 10,
        title: "Faith-based approaches to conscious food systems",
        description: "This area explores how faith traditions, spiritual practices, and moral frameworks inform food ethics, stewardship, care for creation, and social justice, contributing to conscious and regenerative food systems.",
        image: '/brand/themes/10.jpg',
        contact: 'thomas.legrand@undp.org',
        lead: 'Thomas Legrand'
    },
    {
        number: 11,
        title: "Private sector engagement",
        description: "This theme focuses on the role of business, social enterprises, impact investors, and corporations in advancing conscious food systems. It includes values-driven entrepreneurship, corporate transformation, ethical investment, and aligning profit with purpose.",
        image: '/brand/themes/11.jpg',
        contact: 'thomas.legrand@undp.org',
        lead: 'Thomas Legrand'
    },
    {
        number: 12,
        title: "Women's leadership in conscious food systems",
        description: "This workstream highlights the leadership, wisdom, and lived experiences of women across food systems, emphasizing the unique contributions of feminine energy. Submissions may explore care-centered leadership, embodiment practices, rituals, womb wisdom, body listening, intuition, and empowerment, as well as women's roles in shaping sustainable, regenerative and conscious food futures that honor both people and the planet.",
        image: '/brand/themes/12.jpg',
        contact: 'noemi.altobelli@undp.org',
        lead: 'Noemi Altobelli'
    },
    {
        number: 13,
        title: "Conscious food systems in crisis situations",
        description: "This area explores how consciousness-based approaches can inform food systems responses in times of crisis, including conflict, climate shocks, displacement, and humanitarian contexts. It emphasizes dignity, care, trauma awareness, and long-term regeneration beyond emergency responses and how food systems intervention can be leveraged for peace and reconciliation.",
        image: '/brand/themes/13.jpg',
        contact: 'charlotte.dufour@undp.org',
        lead: 'Charlotte Dufour'
    },
    {
        number: 14,
        title: "Planetary health / One health",
        description: "This workstream focuses on the interconnected health of humans, animals, and the planet, exploring how regenerative, equitable and sustainable food systems can support ecological balance, biodiversity, and livelihoods.",
        image: '/brand/themes/14.jpg',
        contact: 'charlotte.dufour@undp.org',
        lead: 'Charlotte Dufour'
    },
    {
        number: 15,
        title: "Consciousness practices for food systems transformation",
        description: "This workstream focuses on inner and collective practices that deepen awareness, connection, and transformation in relation to food systems and the living planet. Examples of practices include: rituals and ceremonies, art (music, theater, painting…), contemplative and embodied practices, non violent communication, conscious facilitation and space holding, community healing, and approaches that foster meaningful relationships between humans and nature.",
        image: '/brand/themes/15.jpg',
        contact: 'noemi.altobelli@undp.org',
        lead: 'Noemi Altobelli'
    }
];
</script>

<template>
    <Head title="Programme & Experience" />
    <EventLayout>

        <!-- ① A Transformative Summit Experience -->
        <section id="transformative-experience" class="relative scroll-mt-24 bg-[#F4F1EA] py-0">
            <div class="w-full">
                <SectionReveal>
                    <!-- Full-width image slider with overlayed content -->
                    <div class="overflow-hidden rounded-none">
                        <div
                            class="relative w-full min-h-[35vh] sm:min-h-[40vh] lg:min-h-[45vh]"
                        >
                            <div
                                v-for="(slide, index) in transformSlides"
                                :key="slide + index"
                                class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                                :class="index === activeTransformSlide ? 'opacity-100' : 'opacity-0'"
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
                                <div class="max-w-5xl text-center">
                                    <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl lg:text-5xl">
                                        {{ sectionTitle }}
                                    </h1>
                                    <p class="mt-4 font-body text-lg font-semibold leading-relaxed text-white sm:text-xl">
                                        {{ settings.transform_p1 || 'The Summit is a call to imagine beyond what is—towards redesigning food systems through creativity, courage, and a shared higher vision. This Summit also creates a space to pause, reflect, and reconnect with what truly matters.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mx-auto max-w-6xl px-4 pb-10 pt-10 text-center sm:px-6 lg:px-8">
                        <p class="font-body text-base leading-relaxed text-ink sm:text-lg">
                            {{ settings.transform_p2 || 'Together, we will explore how food systems can become sources of regeneration, justice, and wellbeing—for people, communities, and the planet.' }}
                        </p>
                        <p class="mt-4 font-body text-base leading-relaxed text-ink sm:text-lg">
                            {{ settings.transform_p3 || 'We invite you to bring your whole self: your lived experience, innovative practices, ancestral wisdom, research, policy insights, entrepreneurial ideas, and inner knowing.' }}
                        </p>
                        <p class="mt-4 font-body text-base leading-relaxed text-ink sm:text-lg">
                            {{ settings.transform_p4 || 'The Summit will combine case studies with interactive workshops involving learning, co-creation and deepening consciousness-based practices, fostering both personal transformation and systemic change.' }}
                        </p>
                    </div>

                    <!-- Programme highlights -->
                    <div class="mx-auto mt-12 max-w-7xl px-4 sm:mt-14 sm:px-6 lg:px-8">
                        <div class="mx-auto max-w-4xl text-center">
                            <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                                {{ highlightsTitle }}
                            </h2>
                            <p
                                v-if="highlightsDescription"
                                class="mx-auto mt-3 max-w-3xl text-center font-body text-base leading-relaxed text-copy sm:text-lg"
                            >
                                {{ highlightsDescription }}
                            </p>
                        </div>

                        <div class="mx-auto mt-8 grid max-w-6xl gap-6 sm:mt-10 lg:grid-cols-2">
                            <div
                                v-for="(item, index) in programmeHighlights"
                                :key="index"
                                class="rounded-none border border-primary-1/10 bg-primary-1/5 p-6 shadow-sm transition hover:shadow-md"
                                :class="index === programmeHighlights.length - 1 ? 'lg:col-span-2' : ''"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-none bg-accent-2">
                                        <svg v-if="index === 0" class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7v14h18V7M7 3v4m10-4v4" />
                                        </svg>
                                        <svg v-else-if="index === 1" class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 3a9 9 0 1 0 9 9c0-2.5-1-4.8-2.6-6.4M12 6a9 9 0 0 1 9 9" />
                                        </svg>
                                        <svg v-else-if="index === 2" class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8zm0 0V4m0 11v5m-7-7h3m10 0h3" />
                                        </svg>
                                        <svg v-else-if="index === 3" class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z" />
                                        </svg>
                                        <svg v-else class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-title text-lg font-bold text-accent-2 sm:text-xl">
                                            {{ splitHighlightTitle(item.title).date }}
                                        </p>
                                        <p
                                            v-if="splitHighlightTitle(item.title).subtitle"
                                            class="mt-1 font-body text-sm font-semibold text-primary-1 sm:text-base"
                                        >
                                            {{ splitHighlightTitle(item.title).subtitle }}
                                        </p>
                                        <p class="mt-3 whitespace-pre-line font-body text-base leading-relaxed text-copy sm:text-lg">
                                            {{ item.body }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Global movement feature cards (same content as admin Section 3 cards) -->
                    <div class="mx-auto mt-12 grid max-w-7xl gap-6 sm:mt-16 sm:grid-cols-2 lg:grid-cols-3 px-4 sm:px-6 lg:px-8">
                        <div
                            v-for="(card, index) in globalCards"
                            :key="index"
                            class="flex flex-col items-center rounded-none bg-primary-1/5 p-8 text-center shadow-md transition sm:col-span-1"
                            :class="[
                                index === 2 ? 'sm:col-span-2 lg:col-span-1' : '',
                                index < 3
                                    ? 'cursor-pointer hover:shadow-xl hover:ring-2 hover:ring-accent-1/40 focus:outline-none focus:ring-2 focus:ring-accent-1'
                                    : 'hover:shadow-xl',
                            ]"
                            :role="index < 3 ? 'button' : undefined"
                            :tabindex="index < 3 ? 0 : undefined"
                            @click="index < 3 && scrollToCardSection(index)"
                            @keydown.enter.prevent="index < 3 && scrollToCardSection(index)"
                            @keydown.space.prevent="index < 3 && scrollToCardSection(index)"
                        >
                            <div class="flex h-12 w-full items-center justify-center">
                                <svg
                                    v-if="index === 0"
                                    class="h-8 w-8 text-primary-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg
                                    v-else-if="index === 1"
                                    class="h-8 w-8 text-primary-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 0 1 2 2v1a2 2 0 0 0 2 2 2 2 0 0 1 2 2v2.945M8 3.935V5.5A2.5 2.5 0 0 0 10.5 8h.5a2 2 0 0 1 2 2 2 2 0 1 0 4 0 2 2 0 0 1 2-2h1.064M15 20.488V18a2 2 0 0 1 2-2h3.064M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                </svg>
                                <svg
                                    v-else
                                    class="h-8 w-8 text-primary-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 w-full font-title text-xl font-bold text-primary-1">
                                {{ card.title }}
                            </h3>
                            <p class="mt-3 font-body text-base leading-relaxed text-copy">
                                {{ card.body }}
                            </p>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <!-- ①a Call for Submissions -->
        <section id="call-for-submissions" class="scroll-mt-24 bg-[#F4F1EA] py-10 sm:py-14 lg:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <!-- Title -->
                    <div class="mb-12 text-center">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl lg:text-4xl">
                            {{ settings.cfs_title || 'Call for Submissions' }}
                        </h2>
                    </div>

                    <!-- Two-column: photo left, text right -->
                    <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">

                        <!-- Photo -->
                        <div class="overflow-hidden rounded-none shadow-xl">
                            <img
                                :src="settings.cfs_image || '/brand/img34.jpg'"
                                alt="Call for Submissions"
                                class="h-72 w-full object-cover sm:h-96"
                                loading="lazy"
                            />
                        </div>

                        <!-- Text -->
                        <div>
                            <p class="font-body text-lg leading-relaxed text-ink sm:text-xl">
                                {{ settings.cfs_body || 'The organizers welcome submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.' }}
                            </p>
                            <p class="mt-4 font-body text-lg font-bold leading-relaxed text-primary-1 sm:text-xl">
                                {{ settings.cfs_apply || 'Apply by 31st of March!' }}
                            </p>
                            <div class="mt-6">
                                <a
                                    href="https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 rounded-full bg-primary-1 px-6 py-3 font-body text-base font-semibold text-white shadow-md transition hover:bg-primary-1/90 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent-1 focus:ring-offset-2 focus:ring-offset-[#F4F1EA] sm:px-8 sm:py-3.5 sm:text-lg"
                                >
                                    <span>Help shape the Summit</span>
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <!-- ② Core Themes -->
        <section id="core-themes" class="scroll-mt-24 bg-[#F4F1EA] py-10 sm:py-14 lg:py-18">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
                            {{ settings.core_title || 'Core Themes' }}
                        </h2>
                        <p class="mt-6 font-body text-base leading-relaxed text-copy sm:text-lg">
                            {{ settings.core_intro || 'The Summit will weave together the following core themes. Rather than isolated tracks, they are intended to intersect and inform one another, reflecting the interconnected nature of conscious food systems.' }}
                        </p>
                    </div>

                    <div
                        class="relative mt-8 sm:mt-10"
                        @mouseenter="autoSlidePaused = true"
                        @mouseleave="autoSlidePaused = false"
                    >
                        <button
                            type="button"
                            :disabled="!canPrevTheme"
                            :class="{ 'opacity-30 cursor-not-allowed': !canPrevTheme }"
                            class="absolute left-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-primary-1/20 p-3 shadow-lg transition hover:bg-primary-1/30 disabled:hover:bg-primary-1/20"
                            @click="prevThemes"
                        >
                            <svg class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div class="overflow-hidden lg:mx-12">
                            <div
                                class="flex transition-transform duration-500 ease-in-out"
                                :style="{ width: `${totalPages * 100}%`, transform: `translateX(-${themeIndex * (100 / totalPages)}%)` }"
                            >
                                <div
                                    v-for="(theme, index) in themes"
                                    :key="index"
                                    class="flex shrink-0 px-2"
                                    :style="{ width: `${100 / totalPages / themesPerView}%` }"
                                >
                                    <div class="group flex h-full flex-col overflow-hidden rounded-none bg-primary-1/5 shadow-md transition hover:shadow-xl">
                                        <div class="relative aspect-[4/3] overflow-hidden">
                                            <img
                                                :src="theme.image || '/brand/themes/1.jpg'"
                                                :alt="theme.title || 'Theme'"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                loading="lazy"
                                            />
                                            <div class="absolute top-4 left-4 flex h-10 w-10 items-center justify-center rounded-none bg-accent-2">
                                                <span class="font-title text-lg font-bold text-primary-1">{{ theme.number }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col p-6">
                                            <h3 class="font-title text-lg font-bold text-primary-1">{{ theme.title }}</h3>
                                            <p class="mt-3 flex-1 font-body text-base leading-relaxed text-copy line-clamp-4 sm:text-lg">{{ theme.description }}</p>
                                            <div class="mt-4 pt-4">
                                                <p class="font-body text-sm text-copy">
                                                    <span class="font-semibold">Team Lead:</span> {{ theme.lead }}
                                                </p>
                                                <a :href="`mailto:${theme.contact}`" class="mt-1 inline-block font-body text-sm text-accent-1 hover:underline">
                                                    {{ theme.contact }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button
                            type="button"
                            :disabled="!canNextTheme"
                            :class="{ 'opacity-30 cursor-not-allowed': !canNextTheme }"
                            class="absolute right-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-primary-1/20 p-3 shadow-lg transition hover:bg-primary-1/30 disabled:hover:bg-primary-1/20"
                            @click="nextThemes"
                        >
                            <svg class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div class="mt-6 flex justify-center gap-2">
                            <span class="font-body text-sm text-copy">
                                {{ themeIndex + 1 }} / {{ totalPages }}
                            </span>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <!-- ③ Building a Global Movement -->
        <section id="global-movement" class="scroll-mt-24 bg-[#F4F1EA] py-10 sm:py-14 lg:py-18">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <!-- Title -->
                    <div class="mb-12 text-center">
                        <h2 class="font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
                            {{ settings.global_title || 'Building a Global Movement' }}
                        </h2>
                    </div>

                    <!-- Two-column: text left, photo right -->
                    <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">

                        <!-- Text -->
                        <div>
                            <p class="font-body text-base leading-relaxed text-ink sm:text-lg">
                                {{ settings.global_p1 || 'Policymakers, farmers, Indigenous leaders, researchers, businesses, youth, philanthropies, academia, civil society actors, and consciousness practitioners will assemble in one circle for a deeply transformative experience.' }}
                            </p>
                            <p class="mt-4 font-body text-base leading-relaxed text-ink sm:text-lg">
                                {{ settings.global_p2 || 'High-level dialogues will include the participation of the Prime Minister of Bhutan, as well as heads of state and ministers from other countries.' }}
                            </p>
                            <p class="mt-4 font-body text-base leading-relaxed text-ink sm:text-lg">
                                {{ settings.global_p3 || 'Participants will also have the opportunity to take part in immersive site visits to local initiatives and conscious food projects.' }}
                            </p>
                            <p class="mt-4 font-body text-base leading-relaxed text-ink sm:text-lg">
                                {{ settings.global_p4 || 'Together, these exchanges will help catalyse connections and contribute to a growing global movement for conscious food systems.' }}
                            </p>
                        </div>

                        <!-- Photo -->
                        <div class="overflow-hidden rounded-none shadow-xl">
                            <img
                                :src="settings.global_image || '/brand/img30.jpg'"
                                alt="Building a Global Movement"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            />
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <!-- Events Plan -->
        <!-- <EventsPlanSection :settings="settings" /> -->

    </EventLayout>
</template>
