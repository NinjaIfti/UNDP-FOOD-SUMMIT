<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import SectionReveal from '@/Components/Home/SectionReveal.vue';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
});

const themeIndex = ref(0);
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1024);

const sectionTitle = computed(() => props.settings?.expect_section_title || 'What to Expect');
const subtitle = computed(() => props.settings?.expect_subtitle || 'A Transformative Experience');
const expectations = computed(() => {
    const list = props.settings?.expectations;
    if (Array.isArray(list) && list.length) return list.filter(Boolean);
    return [
        'High-level dialogues, including the participation of the Prime Minister of Bhutan, heads of state and ministers from other countries',
        'Participatory and transformative knowledge exchanges, including through dialogue, storytelling and creative or embodied methods.',
        'Strategic and interactive discussions and space for personal connections to foster collaboration',
        'Experiential practices such as meditation, nature connection and mindful food rituals, engaging hearts, minds, and multiple senses',
        'Immersive site visits to local initiatives and conscious food projects',
        'A journey of personal and collective transformation and inspiration',
    ];
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
        <section class="bg-gradient-to-b from-primary-2/10 to-white py-16 sm:py-24 lg:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl lg:text-5xl">
                            {{ sectionTitle }}
                        </h1>
                        <p class="mt-6 font-body text-xl leading-relaxed text-primary-1 sm:text-2xl lg:text-3xl font-semibold">
                            {{ subtitle }}
                        </p>
                    </div>

                    <div class="mx-auto mt-12 max-w-4xl space-y-6 sm:mt-16">
                        <div
                            v-for="(item, index) in expectations"
                            :key="index"
                            class="flex gap-4 rounded-xl bg-white p-6 shadow-sm transition hover:shadow-md"
                        >
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent-2">
                                <span class="font-title text-sm font-bold text-primary-1">{{ index + 1 }}</span>
                            </div>
                            <p class="font-body text-base leading-relaxed text-ink sm:text-lg">
                                {{ item }}
                            </p>
                        </div>
                    </div>

                    <div class="mx-auto mt-12 max-w-3xl rounded-2xl bg-primary-1 p-8 text-center sm:mt-16 sm:p-12">
                        <p class="font-body text-lg leading-relaxed text-white sm:text-xl">
                            This is an invitation to bring your <span class="font-semibold">whole self</span>: your lived experience, innovative practices, ancestral wisdom, research, policy insights, entrepreneurial ideas, and inner knowing.
                        </p>
                    </div>

                    <div class="mx-auto mt-8 text-center sm:mt-12">
                        <a 
                            href="https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-full bg-accent-1 px-8 py-4 font-title text-base font-semibold text-white shadow-lg transition hover:bg-accent-1/90 hover:shadow-xl sm:text-lg"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Call for Submissions</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <p class="mt-4 font-body text-sm text-copy sm:text-base">
                            Contribute your experience — respond to our Call for Submissions and help shape the Summit.
                        </p>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <section id="core-themes" class="bg-white pt-0 pb-16 sm:pt-2 sm:pb-24 lg:pt-4 lg:pb-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
                            Core Themes
                        </h2>
                        <p class="mt-6 font-body text-base leading-relaxed text-copy sm:text-lg">
                            The following themes have been identified to guide the Summit preparation process. Proposals may align with one or more of the themes. Please note these will not be isolated tracks and submissions demonstrating interconnections between these themes are welcome. We are also open to proposals on other topics as long as their relevance to conscious food systems is demonstrated.
                        </p>
                    </div>

                    <div
                        class="relative mt-12 sm:mt-16"
                        @mouseenter="autoSlidePaused = true"
                        @mouseleave="autoSlidePaused = false"
                    >
                        <button
                            type="button"
                            :disabled="!canPrevTheme"
                            :class="{ 'opacity-30 cursor-not-allowed': !canPrevTheme }"
                            class="absolute left-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white p-3 shadow-lg transition hover:bg-gray-50 disabled:hover:bg-white"
                            @click="prevThemes"
                        >
                            <svg class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <div class="group flex h-full flex-col overflow-hidden rounded-2xl border border-primary-2/20 bg-white shadow-sm transition hover:shadow-xl">
                                        <div class="relative aspect-[4/3] overflow-hidden">
                                            <img 
                                                :src="theme.image || '/brand/themes/1.jpg'" 
                                                :alt="theme.title || 'Theme'" 
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                loading="lazy"
                                            />
                                            <div class="absolute top-4 left-4 flex h-10 w-10 items-center justify-center rounded-full bg-accent-2">
                                                <span class="font-title text-lg font-bold text-primary-1">{{ theme.number }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col p-6">
                                            <h3 class="font-title text-lg font-bold text-primary-1">{{ theme.title }}</h3>
                                            <p class="mt-3 flex-1 font-body text-base leading-relaxed text-copy line-clamp-4 sm:text-lg">{{ theme.description }}</p>
                                            <div class="mt-4 border-t border-primary-2/10 pt-4">
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
                            class="absolute right-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white p-3 shadow-lg transition hover:bg-gray-50 disabled:hover:bg-white"
                            @click="nextThemes"
                        >
                            <svg class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </EventLayout>
</template>
