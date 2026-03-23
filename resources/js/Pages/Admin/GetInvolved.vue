<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

const defaultCtaCards = [
    { href: 'https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form', title: 'Submit Your Application', subtitle: 'Complete the application form by 31st March', style: 'primary' },
    { href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', title: 'Full Submission Guidelines', subtitle: 'View details, eligibility criteria and guidelines', style: 'secondary' },
];

const form = useForm({
    hero_title: props.settings?.hero_title ?? 'Call for Submissions',
    call_submissions_title: props.settings?.call_submissions_title ?? 'Call for Submissions',
    hero_subtitle: props.settings?.hero_subtitle ?? 'We warmly invite you to co-create the agenda of the first-ever Global Conscious Food Systems Summit, taking place in Bhutan from 31 August – 4 September 2026.',
    hero_intro: props.settings?.hero_intro ?? 'CoFSA invites submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.',
    hero_image_file: null,
    hero_slides: props.settings?.hero_slides ?? [],
    hero_slide_1_file: null,
    hero_slide_2_file: null,
    hero_slide_3_file: null,
    spread_title: props.settings?.spread_title ?? 'Spread the word!',
    spread_body: props.settings?.spread_body ?? 'PENDING: Communication Toolkit will be provided by COFSA soon.',
    spread_pending: props.settings?.spread_pending ?? '',
    spread_pdf_url: props.settings?.spread_pdf_url ?? '',
    spread_pdf_file: null,
    spread_trello_url: props.settings?.spread_trello_url ?? '',
    partners_title: props.settings?.partners_title ?? 'Together with our partners',
    partners_body: props.settings?.partners_body ?? 'This Summit is made possible through strong partnerships.',
    partners_pending: props.settings?.partners_pending ?? 'PENDING: COFSA WILL GIVE YOU ALL THE PARTNER LOGOS',
    partners_logos: props.settings?.partners_logos ?? [],
    partner_logo_1_file: null,
    partner_logo_2_file: null,
    partner_logo_3_file: null,
    partner_logo_4_file: null,
    partner_logo_5_file: null,
    partner_logo_6_file: null,
    financial_title: props.settings?.financial_title ?? 'Financial support to expand Summit impact',
    financial_body: props.settings?.financial_body ?? '',
    cta_paragraph1: props.settings?.cta_paragraph1 ?? 'If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.',
    cta_paragraph2: props.settings?.cta_paragraph2 ?? 'All submissions should be experiential and participatory, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.',
    cta_paragraph3: props.settings?.cta_paragraph3 ?? 'Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.',
    engagement_title: props.settings?.engagement_title ?? 'Activating the Heart',
    engagement_body: props.settings?.engagement_body ?? '',
    visioning_title: props.settings?.visioning_title ?? 'Deep Visioning',
    visioning_body: props.settings?.visioning_body ?? '',
    cta_cards: (Array.isArray(props.settings?.cta_cards) && props.settings.cta_cards.length > 0 ? props.settings.cta_cards : defaultCtaCards).map(c => ({
        href: c.href ?? '',
        title: c.title ?? '',
        subtitle: c.subtitle ?? '',
        style: c.style ?? 'secondary',
    })),
});

function addCtaCard() {
    form.cta_cards.push({ href: '', title: '', subtitle: '', style: 'secondary' });
}

function removeCtaCard(index) {
    form.cta_cards.splice(index, 1);
}

function submit() {
    const data = { ...form.data() };

    if (form.hero_image_file) {
        data.hero_image = form.hero_image_file;
    }
    if (form.hero_slide_1_file) {
        data.hero_slide_1_file = form.hero_slide_1_file;
    }
    if (form.hero_slide_2_file) {
        data.hero_slide_2_file = form.hero_slide_2_file;
    }
    if (form.hero_slide_3_file) {
        data.hero_slide_3_file = form.hero_slide_3_file;
    }
    if (form.partner_logo_1_file) data.partner_logo_1_file = form.partner_logo_1_file;
    if (form.partner_logo_2_file) data.partner_logo_2_file = form.partner_logo_2_file;
    if (form.partner_logo_3_file) data.partner_logo_3_file = form.partner_logo_3_file;
    if (form.partner_logo_4_file) data.partner_logo_4_file = form.partner_logo_4_file;
    if (form.partner_logo_5_file) data.partner_logo_5_file = form.partner_logo_5_file;
    if (form.partner_logo_6_file) data.partner_logo_6_file = form.partner_logo_6_file;
    if (form.spread_pdf_file) data.spread_pdf_file = form.spread_pdf_file;

    form.transform(() => data).post(route('admin.get-involved.update'), {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Edit Get Involved" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <!-- Sticky Save button (always visible while scrolling) -->
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="fixed right-4 top-1/2 z-50 -translate-y-1/2 rounded-lg bg-primary-1 px-4 py-3 font-body text-sm font-semibold text-white shadow-lg transition hover:bg-primary-1/90 disabled:opacity-50"
            >
                {{ form.processing ? 'Saving...' : 'Save' }}
            </button>

            <div class="mb-4 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="font-title text-xl font-bold text-primary-1 sm:text-2xl">Edit Get Involved</h2>
                <a href="/get-involved" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-primary-1 px-4 py-2 font-body text-sm font-medium text-primary-1 transition hover:bg-primary-1/5 sm:w-auto">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Preview
                </a>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3 sm:mb-6 sm:p-4">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Hero Section</h3>
                    <div class="space-y-4">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Hero image & slider</h4>
                            <div class="space-y-4">
                                <div class="grid items-center gap-4 sm:grid-cols-[auto,1fr]">
                                    <div>
                                        <p class="mb-2 font-body text-xs font-medium text-ink">Current image</p>
                                        <img
                                            :src="props.settings?.hero_image || '/brand/img34.jpg'"
                                            alt="Current Get Involved hero image"
                                            class="h-24 w-40 rounded-lg border border-gray-200 object-cover"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block font-body text-sm font-medium text-ink">Upload main hero image</label>
                                        <input
                                            type="file"
                                            accept="image/*"
                                            @change="(e) => { const f = e.target?.files?.[0]; form.hero_image_file = f || null; }"
                                            class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                        />
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, WEBP, max 4MB.</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-body text-sm font-medium text-ink">Additional slider images (optional)</label>
                                    <p class="mt-1 text-xs text-gray-500">If more than one image exists (main + extras), the hero will fade between them.</p>

                                    <div v-if="form.hero_slides && form.hero_slides.length" class="mt-3 space-y-2">
                                        <p class="text-xs font-medium text-gray-700">Current additional slides</p>
                                        <div
                                            v-for="(slide, index) in form.hero_slides"
                                            :key="slide + index"
                                            class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-2"
                                        >
                                            <img
                                                :src="slide"
                                                alt="Slide image"
                                                class="h-14 w-20 rounded-md border border-gray-200 object-cover"
                                            />
                                            <div class="flex-1">
                                                <p class="truncate text-xs text-gray-600">{{ slide }}</p>
                                            </div>
                                            <button
                                                type="button"
                                                class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200"
                                                @click="form.hero_slides.splice(index, 1)"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-2">
                                        <p class="text-xs font-medium text-gray-700">Upload up to 3 additional images</p>
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                            @change="(e) => { const f = e.target?.files?.[0]; form.hero_slide_1_file = f || null; }"
                                        />
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                            @change="(e) => { const f = e.target?.files?.[0]; form.hero_slide_2_file = f || null; }"
                                        />
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                            @change="(e) => { const f = e.target?.files?.[0]; form.hero_slide_3_file = f || null; }"
                                        />
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, WEBP, max 4MB each.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Hero Title</label>
                            <input v-model="form.hero_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Subtitle</label>
                            <textarea v-model="form.hero_subtitle" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Intro Paragraph</label>
                            <textarea v-model="form.hero_intro" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>


                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Call to Action Section</h3>
                    <div class="mb-4 space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Call for Submissions Card Title</label>
                            <input v-model="form.call_submissions_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            <p class="mt-1 text-xs text-gray-500">This title is used in the first white card below the hero section.</p>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 1</label>
                            <textarea v-model="form.cta_paragraph1" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 2</label>
                            <textarea v-model="form.cta_paragraph2" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 3</label>
                            <textarea v-model="form.cta_paragraph3" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Card 2 Title (right top)</label>
                            <input v-model="form.engagement_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Card 2 Text (right top)</label>
                            <textarea v-model="form.engagement_body" rows="5" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Card 3 Title (left bottom)</label>
                            <input v-model="form.visioning_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Card 3 Text (left bottom)</label>
                            <textarea v-model="form.visioning_body" rows="5" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(card, index) in form.cta_cards" :key="index" class="rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="font-title text-sm font-bold text-primary-1">CTA Card {{ index + 1 }}</h4>
                                <button type="button" @click="removeCtaCard(index)" class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200">Delete</button>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Link URL</label>
                                    <input v-model="card.href" type="url" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" placeholder="https://" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Title</label>
                                    <input v-model="card.title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Subtitle</label>
                                    <input v-model="card.subtitle" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Style</label>
                                    <select v-model="card.style" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20">
                                        <option value="primary">Primary (blue background)</option>
                                        <option value="secondary">Secondary (outline)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="addCtaCard" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-body text-sm font-medium text-gray-600 transition hover:border-primary-1 hover:bg-primary-1/5 hover:text-primary-1">+ Add CTA card</button>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Spread the word</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.spread_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Text</label>
                            <textarea v-model="form.spread_body" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Pending note (optional)</label>
                            <input v-model="form.spread_pending" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <label class="block font-body text-sm font-medium text-ink">Upload PDF button file</label>
                            <input
                                type="file"
                                accept="application/pdf"
                                class="mt-2 block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                @change="(e) => { const f = e.target?.files?.[0]; form.spread_pdf_file = f || null; }"
                            />
                            <p class="mt-1 text-xs text-gray-500">PDF only, max 10MB.</p>
                            <p v-if="form.spread_pdf_url" class="mt-2 text-xs text-gray-600 break-all">
                                Current PDF: <a :href="form.spread_pdf_url" target="_blank" class="text-blue-600 hover:underline">{{ form.spread_pdf_url }}</a>
                            </p>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Trello button URL</label>
                            <input v-model="form.spread_trello_url" type="url" placeholder="https://trello.com/..." class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Together with our partners</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Sub-section title</label>
                            <input v-model="form.partners_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Text</label>
                            <textarea v-model="form.partners_body" rows="2" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Pending note (optional)</label>
                            <input v-model="form.partners_pending" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <h4 class="mb-3 font-title text-base font-bold text-primary-1">Partner logos</h4>

                            <div v-if="form.partners_logos && form.partners_logos.length" class="space-y-2">
                                <div
                                    v-for="(logo, index) in form.partners_logos"
                                    :key="logo + index"
                                    class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-2"
                                >
                                    <img :src="logo" alt="Partner logo" class="h-12 w-20 rounded-md border border-gray-200 object-contain bg-white" />
                                    <p class="flex-1 truncate text-xs text-gray-600">{{ logo }}</p>
                                    <button
                                        type="button"
                                        class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200"
                                        @click="form.partners_logos.splice(index, 1)"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3 space-y-2">
                                <p class="text-xs font-medium text-gray-700">Upload up to 6 logos</p>
                                <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" @change="(e) => { const f = e.target?.files?.[0]; form.partner_logo_1_file = f || null; }" />
                                <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" @change="(e) => { const f = e.target?.files?.[0]; form.partner_logo_2_file = f || null; }" />
                                <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" @change="(e) => { const f = e.target?.files?.[0]; form.partner_logo_3_file = f || null; }" />
                                <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" @change="(e) => { const f = e.target?.files?.[0]; form.partner_logo_4_file = f || null; }" />
                                <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" @change="(e) => { const f = e.target?.files?.[0]; form.partner_logo_5_file = f || null; }" />
                                <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" @change="(e) => { const f = e.target?.files?.[0]; form.partner_logo_6_file = f || null; }" />
                                <p class="mt-1 text-xs text-gray-500">PNG recommended for logos. Max 4MB each.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Financial support to expand Summit impact</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Sub-section title</label>
                            <input v-model="form.financial_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Text</label>
                            <textarea v-model="form.financial_body" rows="8" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                            <p class="mt-1 text-xs text-gray-500">New lines will show as separate paragraphs on the site.</p>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
