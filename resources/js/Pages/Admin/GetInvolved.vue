<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

const defaultCategories = [
    { title: 'Whole Session', description: 'For applicants who propose to design and facilitate a full session during the Summit...', icon: 'presentation' },
    { title: 'Case Study', description: 'For individuals or organizations not yet ready to propose a full interactive session...', icon: 'document' },
];

const defaultCtaCards = [
    { href: 'https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form', title: 'Submit Your Application', subtitle: 'Complete the application form by 31st March', style: 'primary' },
    { href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', title: 'Full Submission Guidelines', subtitle: 'View details, eligibility criteria and guidelines', style: 'secondary' },
];

const form = useForm({
    hero_title: props.settings?.hero_title ?? 'Call for Submissions',
    hero_subtitle: props.settings?.hero_subtitle ?? 'We warmly invite you to co-create the agenda of the first-ever Global Conscious Food Systems Summit, taking place in Bhutan from 31 August – 4 September 2026.',
    hero_intro: props.settings?.hero_intro ?? 'CoFSA invites submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.',
    hero_image_file: null,
    hero_slides: props.settings?.hero_slides ?? [],
    hero_slide_1_file: null,
    hero_slide_2_file: null,
    hero_slide_3_file: null,
    categories_section_title: props.settings?.categories_section_title ?? 'Submission Categories',
    categories_section_subtitle: props.settings?.categories_section_subtitle ?? 'Applicants may submit one or more proposals under the following categories:',
    categories: (Array.isArray(props.settings?.categories) && props.settings.categories.length > 0 ? props.settings.categories : defaultCategories).map(c => ({
        title: c.title ?? '',
        description: c.description ?? '',
        icon: c.icon ?? 'presentation',
    })),
    cta_paragraph1: props.settings?.cta_paragraph1 ?? 'If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.',
    cta_paragraph2: props.settings?.cta_paragraph2 ?? 'All submissions should be experiential and participatory, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.',
    cta_paragraph3: props.settings?.cta_paragraph3 ?? 'Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.',
    cta_cards: (Array.isArray(props.settings?.cta_cards) && props.settings.cta_cards.length > 0 ? props.settings.cta_cards : defaultCtaCards).map(c => ({
        href: c.href ?? '',
        title: c.title ?? '',
        subtitle: c.subtitle ?? '',
        style: c.style ?? 'secondary',
    })),
});

function addCategory() {
    form.categories.push({ title: '', description: '', icon: 'presentation' });
}

function removeCategory(index) {
    form.categories.splice(index, 1);
}

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
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
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
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Submission Categories</h3>
                    <div class="mb-4 space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.categories_section_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Subtitle</label>
                            <input v-model="form.categories_section_subtitle" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(cat, index) in form.categories" :key="index" class="rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="font-title text-sm font-bold text-primary-1">Category {{ index + 1 }}</h4>
                                <button type="button" @click="removeCategory(index)" class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200">Delete</button>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Title</label>
                                    <input v-model="cat.title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Description</label>
                                    <textarea v-model="cat.description" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Icon</label>
                                    <select v-model="cat.icon" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20">
                                        <option value="presentation">Presentation</option>
                                        <option value="document">Document</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="addCategory" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-body text-sm font-medium text-gray-600 transition hover:border-primary-1 hover:bg-primary-1/5 hover:text-primary-1">+ Add category</button>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Call to Action Section</h3>
                    <div class="mb-4 space-y-4">
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

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-primary-1 px-6 py-3 font-body text-sm font-semibold text-white transition hover:bg-primary-1/90 disabled:opacity-50">Save changes</button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
