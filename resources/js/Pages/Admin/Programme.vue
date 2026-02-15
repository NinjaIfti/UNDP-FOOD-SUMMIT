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

const props = defineProps({
    settings: Object,
});

const form = useForm({
    expect_section_title: props.settings?.expect_section_title ?? 'WHAT TO EXPECT',
    expect_subtitle: props.settings?.expect_subtitle ?? 'A Transformative Experience',
    expectations: Array.isArray(props.settings?.expectations)
        ? [...props.settings.expectations]
        : [
            'High-level dialogues, including the participation of the Prime Minister of Bhutan, heads of state and ministers from other countries',
            'Participatory and transformative knowledge exchanges, including through dialogue, storytelling and creative or embodied methods.',
            'Strategic and interactive discussions and space for personal connections to foster collaboration',
            'Experiential practices such as meditation, nature connection and mindful food rituals, engaging hearts, minds, and multiple senses',
            'Immersive site visits to local initiatives and conscious food projects',
            'A journey of personal and collective transformation and inspiration',
        ],
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

function addExpectation() {
    form.expectations.push('');
}

function removeExpectation(index) {
    form.expectations.splice(index, 1);
}

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

function submit() {
    const formData = { ...form.data() };
    formData.themes = form.themes.map(({ imageFile, deleteImage, ...t }) => ({ ...t }));
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
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 font-title text-lg font-bold text-primary-1">What to Expect</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                            <input v-model="form.expect_section_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Subtitle</label>
                            <input v-model="form.expect_subtitle" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="block font-body text-sm font-medium text-ink">Expectations (list items)</label>
                                <button type="button" @click="addExpectation" class="rounded-lg border border-primary-1 px-3 py-1.5 font-body text-sm font-medium text-primary-1 transition hover:bg-primary-1/5">Add item</button>
                            </div>
                            <div class="mt-2 space-y-3">
                                <div v-for="(item, index) in form.expectations" :key="index" class="flex gap-2">
                                    <input v-model="form.expectations[index]" type="text" class="block flex-1 rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                                    <button type="button" @click="removeExpectation(index)" class="shrink-0 rounded-lg p-2 text-red-600 hover:bg-red-50" :disabled="form.expectations.length <= 1">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
