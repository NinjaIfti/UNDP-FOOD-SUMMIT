<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const defaultTheme = () => ({
    number: 1,
    title: '',
    description: '',
    image: '/brand/themes/1.jpg',
    contact: '',
    lead: '',
    imageFile: null,
    deleteImage: false,
});

const defaultThemes = [
    { number: 1, title: "Farmers' wellbeing and the inner dimension of agroecology", description: "This area focuses on the wellbeing of farmers and producers.", image: '/brand/themes/1.jpg', contact: 'jimena.gomez@undp.org', lead: 'Jimena Gomez' },
    { number: 2, title: "Conscious food policy and governance", description: "This workstream examines how values, awareness, mindsets and inner capacities can shape food related policies.", image: '/brand/themes/2.jpg', contact: 'charlotte.dufour@undp.org', lead: 'Charlotte Dufour' },
    { number: 3, title: "Conscious consumption", description: "This theme explores the relationship between consumers and food.", image: '/brand/themes/3.jpg', contact: 'charlotte.dufour@undp.org', lead: 'Charlotte Dufour' },
];

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

const props = defineProps({
    settings: Object,
});

const form = useForm({
    // Section 1 – A Transformative Summit Experience
    transform_title: props.settings?.transform_title ?? 'A Transformative Summit Experience',
    transform_p1: props.settings?.transform_p1 ?? 'The Summit is a call to imagine beyond what is—towards redesigning food systems through creativity, courage, and a shared higher vision. This Summit also creates a space to pause, reflect, and reconnect with what truly matters.',
    transform_p2: props.settings?.transform_p2 ?? 'Together, we will explore how food systems can become sources of regeneration, justice, and wellbeing—for people, communities, and the planet.',
    transform_p3: props.settings?.transform_p3 ?? 'We invite you to bring your whole self: your lived experience, innovative practices, ancestral wisdom, research, policy insights, entrepreneurial ideas, and inner knowing.',
    transform_p4: props.settings?.transform_p4 ?? 'The Summit will combine case studies with interactive workshops involving learning, co-creation and deepening consciousness-based practices, fostering both personal transformation and systemic change.',

    // Section 1a – Call for Submissions
    cfs_title: props.settings?.cfs_title ?? 'Call for Submissions',
    cfs_body: props.settings?.cfs_body ?? 'The organizers welcome submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.',
    cfs_apply: props.settings?.cfs_apply ?? 'Apply by 31st of March!',
    cfs_button: props.settings?.cfs_button ?? 'Help shape the Summit! Respond to our Call for Submissions',
    cfsImageFile: null,

    // Section 2 – Core Themes intro
    core_title: props.settings?.core_title ?? 'Core Themes',
    core_intro: props.settings?.core_intro ?? 'The Summit will weave together the following core themes. Rather than isolated tracks, they are intended to intersect and inform one another, reflecting the interconnected nature of conscious food systems.',

    // Section 3 – Building a Global Movement
    global_title: props.settings?.global_title ?? 'Building a Global Movement',
    global_p1: props.settings?.global_p1 ?? 'Policymakers, farmers, Indigenous leaders, researchers, businesses, youth, philanthropies, academia, civil society actors, and consciousness practitioners will assemble in one circle for a deeply transformative experience.',
    global_p2: props.settings?.global_p2 ?? 'High-level dialogues will include the participation of the Prime Minister of Bhutan, as well as heads of state and ministers from other countries.',
    global_p3: props.settings?.global_p3 ?? 'Participants will also have the opportunity to take part in immersive site visits to local initiatives and conscious food projects.',
    global_p4: props.settings?.global_p4 ?? 'Together, these exchanges will help catalyse connections and contribute to a growing global movement for conscious food systems.',
    global_quote: props.settings?.global_quote ?? "The Summit will advance Bhutan's National Food Systems Transformation Pathways by cultivating a conscious inner mindset, while highlighting Bhutan's leadership in driving conscious food systems transformation globally.",
    globalImageFile: null,

    global_cards: (Array.isArray(props.settings?.global_cards) && props.settings.global_cards.length > 0
        ? props.settings.global_cards
        : defaultGlobalCards
    ).map(card => ({
        title: card.title ?? '',
        body: card.body ?? '',
    })),

    // Core Theme cards
    themes: (Array.isArray(props.settings?.themes) && props.settings.themes.length > 0
        ? props.settings.themes
        : defaultThemes
    ).map(t => ({
        number: t.number ?? 0,
        title: t.title ?? '',
        description: t.description ?? '',
        image: t.image ?? '/brand/themes/1.jpg',
        contact: t.contact ?? '',
        lead: t.lead ?? '',
        imageFile: null,
        deleteImage: false,
    })),
});

function addTheme() {
    const maxNum = form.themes.length ? Math.max(...form.themes.map(t => t.number)) : 0;
    form.themes.push({
        ...defaultTheme(),
        number: maxNum + 1,
    });
}

function removeTheme(index) {
    form.themes.splice(index, 1);
}

function onThemeImageChange(index, event) {
    const file = event.target?.files?.[0];
    if (file) {
        form.themes[index].imageFile = file;
        form.themes[index].deleteImage = false;
    }
}

function clearThemeImage(index) {
    form.themes[index].imageFile = null;
    form.themes[index].deleteImage = false;
}

function onCfsImageChange(event) {
    const file = event.target?.files?.[0];
    if (file) {
        form.cfsImageFile = file;
    }
}

function onGlobalImageChange(event) {
    const file = event.target?.files?.[0];
    if (file) {
        form.globalImageFile = file;
    }
}

function submit() {
    const formData = { ...form.data() };
    formData.themes = form.themes.map(({ imageFile, deleteImage, ...t }) => ({ ...t }));
    if (form.cfsImageFile) {
        formData.cfs_image = form.cfsImageFile;
    }
    if (form.globalImageFile) {
        formData.global_image = form.globalImageFile;
    }

    form.themes.forEach((theme, index) => {
        if (theme.imageFile) {
            formData[`theme_${index}_image`] = theme.imageFile;
        }
        if (theme.deleteImage) {
            formData[`theme_${index}_delete_image`] = true;
        }
    });
    form.transform(() => formData).post(route('admin.programme.update'), {
        forceFormData: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Edit Programme & Experience" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-4 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="font-title text-xl font-bold text-primary-1 sm:text-2xl">Edit Programme & Experience</h2>
                <a
                    href="/programme"
                    target="_blank"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-primary-1 px-4 py-2 font-body text-sm font-medium text-primary-1 transition hover:bg-primary-1/5 sm:w-auto"
                >
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
                <!-- Section 1 – Transformative Summit Experience -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Section 1 – A Transformative Summit Experience</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.transform_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 1</label>
                            <textarea v-model="form.transform_p1" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 2</label>
                            <textarea v-model="form.transform_p2" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 3</label>
                            <textarea v-model="form.transform_p3" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 4</label>
                            <textarea v-model="form.transform_p4" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 1a – Call for Submissions -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Section 1a – Call for Submissions</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Current image</label>
                            <div class="mt-2 flex items-center gap-4">
                                <img
                                    :src="props.settings?.cfs_image || '/brand/img34.jpg'"
                                    alt="Current Call for Submissions image"
                                    class="h-24 w-32 rounded-lg border border-gray-200 object-cover"
                                />
                                <div class="flex-1">
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="onCfsImageChange"
                                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">Upload a new image to replace this one (JPG, PNG, WEBP, max 4MB).</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.cfs_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Body text</label>
                            <textarea v-model="form.cfs_body" rows="4" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Apply line</label>
                            <input v-model="form.cfs_apply" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Button text</label>
                            <input v-model="form.cfs_button" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                    </div>
                </div>

                <!-- Section 2 – Core Themes intro -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Section 2 – Core Themes Intro</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.core_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Intro paragraph</label>
                            <textarea v-model="form.core_intro" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3 – Building a Global Movement -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Section 3 – Building a Global Movement</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Current image</label>
                            <div class="mt-2 flex items-center gap-4">
                                <img
                                    :src="props.settings?.global_image || '/brand/img30.jpg'"
                                    alt="Current Building a Global Movement image"
                                    class="h-24 w-32 rounded-lg border border-gray-200 object-cover"
                                />
                                <div class="flex-1">
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="onGlobalImageChange"
                                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">Upload a new image to replace this one (JPG, PNG, WEBP, max 4MB).</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title</label>
                            <input v-model="form.global_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 1</label>
                            <textarea v-model="form.global_p1" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 2</label>
                            <textarea v-model="form.global_p2" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 3</label>
                            <textarea v-model="form.global_p3" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Paragraph 4</label>
                            <textarea v-model="form.global_p4" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Bottom quote</label>
                            <textarea v-model="form.global_quote" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="mb-3 font-title text-sm font-bold text-primary-1">Section 3 – Feature cards</h4>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div
                                    v-for="(card, index) in form.global_cards"
                                    :key="index"
                                    class="rounded-lg border border-gray-200 p-3"
                                >
                                    <p class="mb-2 font-body text-xs font-medium text-ink">Card {{ index + 1 }}</p>
                                    <label class="block font-body text-xs font-medium text-ink">Title</label>
                                    <input
                                        v-model="card.title"
                                        type="text"
                                        class="mt-1 mb-2 block w-full rounded-lg border border-gray-300 px-2 py-1.5 text-xs font-body focus:border-accent-1 focus:outline-none focus:ring-1 focus:ring-accent-1/20"
                                    />
                                    <label class="block font-body text-xs font-medium text-ink">Text</label>
                                    <textarea
                                        v-model="card.body"
                                        rows="4"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-2 py-1.5 text-xs font-body focus:border-accent-1 focus:outline-none focus:ring-1 focus:ring-accent-1/20"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Core Theme cards -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Core Theme Cards</h3>
                    <div class="space-y-6">
                        <div
                            v-for="(theme, index) in form.themes"
                            :key="index"
                            class="rounded-lg border border-gray-200 p-4"
                        >
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="font-title text-sm font-bold text-primary-1">Card {{ index + 1 }}</h4>
                                <button type="button" @click="removeTheme(index)" class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200">
                                    Delete card
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Card number</label>
                                        <input v-model.number="theme.number" type="number" min="1" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    </div>
                                    <div>
                                        <label class="block font-body text-xs font-medium text-ink">Team lead</label>
                                        <input v-model="theme.lead" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Title</label>
                                    <input v-model="theme.title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Description</label>
                                    <textarea v-model="theme.description" rows="4" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Contact email</label>
                                    <input v-model="theme.contact" type="email" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                </div>
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Card image</label>
                                    <div class="mt-1 space-y-2">
                                        <div v-if="theme.image && !theme.deleteImage" class="flex items-center gap-3">
                                            <img :src="theme.image" :alt="theme.title" class="h-20 w-28 rounded-lg border border-gray-200 object-cover" />
                                            <div class="flex-1 space-y-2">
                                                <p class="text-xs text-gray-600">Current image</p>
                                                <button type="button" @click="theme.deleteImage = true; theme.imageFile = null" class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200">Remove</button>
                                            </div>
                                        </div>
                                        <div v-if="theme.deleteImage" class="rounded-lg bg-red-50 p-3">
                                            <p class="text-xs text-red-700">Image will be removed on save</p>
                                            <button type="button" @click="theme.deleteImage = false" class="mt-2 text-xs font-medium text-red-600 underline hover:no-underline">Undo</button>
                                        </div>
                                        <div v-if="theme.imageFile" class="rounded-lg bg-green-50 p-3">
                                            <p class="text-xs text-green-700">New image: {{ theme.imageFile.name }}</p>
                                            <button type="button" @click="clearThemeImage(index)" class="mt-2 text-xs font-medium text-green-600 underline hover:no-underline">Clear</button>
                                        </div>
                                        <input type="file" accept="image/*" @change="onThemeImageChange(index, $event)" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="addTheme" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-body text-sm font-medium text-gray-600 transition hover:border-primary-1 hover:bg-primary-1/5 hover:text-primary-1">
                            + Add card
                        </button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-primary-1 px-6 py-3 font-body text-sm font-semibold text-white transition hover:bg-primary-1/90 disabled:opacity-50">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
