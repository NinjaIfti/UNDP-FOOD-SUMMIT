<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    templates: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            sent_today: 0,
            opened: 0,
            clicked: 0,
        }),
    },
});

const selectedTemplate = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);
const showPreview = ref(false);

const typeColors = {
    approval: 'bg-primary-2 text-white',
    reminder: 'bg-accent-2 text-primary-1',
    confirmation: 'bg-green-600 text-white',
    custom: 'bg-ink text-white',
};

const templateForm = useForm({
    name: '',
    type: 'custom',
    subject: '',
    body: '',
    attachmentPdfFiles: [],
    deleteAttachmentPdfPaths: [],
});

const handleEditTemplate = (template) => {
    selectedTemplate.value = template;
    templateForm.name = template.name;
    templateForm.type = template.type;
    templateForm.subject = template.subject;
    templateForm.body = template.body;
    templateForm.attachmentPdfFiles = [];
    templateForm.deleteAttachmentPdfPaths = [];
    isEditing.value = true;
};

const handleSaveTemplate = () => {
    if (isCreating.value) {
        templateForm.transform((data) => {
            const payload = {
                name: data.name,
                type: data.type,
                subject: data.subject,
                body: data.body,
            };
            (data.attachmentPdfFiles || []).forEach((f, i) => {
                payload[`attachment_pdf_${i}`] = f;
            });
            return payload;
        }).post(route('admin.communications.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                templateForm.reset();
                isCreating.value = false;
                selectedTemplate.value = null;
            },
        });
    } else if (isEditing.value && selectedTemplate.value?.id) {
        templateForm.transform((data) => {
            const payload = {
                _method: 'put',
                name: data.name,
                type: data.type,
                subject: data.subject,
                body: data.body,
                delete_attachment_pdf_paths: data.deleteAttachmentPdfPaths?.length ? data.deleteAttachmentPdfPaths : undefined,
            };
            (data.attachmentPdfFiles || []).forEach((f, i) => {
                payload[`attachment_pdf_${i}`] = f;
            });
            return payload;
        }).post(route('admin.communications.update', selectedTemplate.value.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                isEditing.value = false;
                selectedTemplate.value = null;
                templateForm.reset();
            },
        });
    }
};

const handlePreview = (template) => {
    selectedTemplate.value = template;
    showPreview.value = true;
};

const duplicateTemplate = (template) => {
    router.post(route('admin.communications.duplicate', template.id), {}, {
        preserveScroll: true,
    });
};

const deleteTemplate = (id) => {
    if (confirm('Are you sure you want to delete this template?')) {
        router.delete(route('admin.communications.destroy', id), {
            preserveScroll: true,
        });
    }
};

const handleCreateTemplate = () => {
    templateForm.name = 'New Template';
    templateForm.type = 'custom';
    templateForm.subject = 'New Email Subject';
    templateForm.body = 'New Email Body';
    templateForm.attachmentPdfFiles = [];
    templateForm.deleteAttachmentPdfPaths = [];
    selectedTemplate.value = {
        id: null,
        name: templateForm.name,
        type: templateForm.type,
        subject: templateForm.subject,
        body: templateForm.body,
        attachment_pdfs: [],
    };
    isCreating.value = true;
};

const closeModal = () => {
    isEditing.value = false;
    isCreating.value = false;
    showPreview.value = false;
    selectedTemplate.value = null;
    templateForm.reset();
};

const placeholders = [
    '{First Name}',
    '{Last Name}',
    '{Email}',
    '{Login Link}',
    '{Event Dates}',
    '{Price Tier}',
    '{Organization}',
];

const insertPlaceholder = (placeholder) => {
    const textarea = document.querySelector('textarea[name="body"]');
    if (textarea) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = templateForm.body;
        templateForm.body = text.substring(0, start) + placeholder + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + placeholder.length, start + placeholder.length);
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const replacePlaceholders = (text) => {
    return text
        .replace(/{First Name}/g, 'John')
        .replace(/{Last Name}/g, 'Smith')
        .replace(/{Email}/g, 'john.smith@example.com')
        .replace(/{Login Link}/g, 'https://ticketing.gcfss2026.org/login')
        .replace(/{Event Dates}/g, 'August 31 - September 4, 2026')
        .replace(/{Price Tier}/g, 'Tier 2 - Standard')
        .replace(/{Organization}/g, 'Global Food Initiative');
};
</script>

<template>
    <Head title="Email Communications" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="mb-2 font-title text-2xl font-bold text-primary-1">
                        Email Communications
                    </h1>
                    <p class="font-body text-sm text-copy">
                        Manage email templates and automated communications
                    </p>
                </div>
                <button
                    type="button"
                    @click="handleCreateTemplate"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors hover:bg-primary-2/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Template
                </button>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <svg class="h-5 w-5 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="mb-1 font-body text-sm text-copy">Sent Today</p>
                    <p class="font-title text-2xl font-bold text-primary-1">{{ stats?.sent_today ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <p class="mb-1 font-body text-sm text-copy">Opened</p>
                    <p class="font-title text-2xl font-bold text-primary-1">{{ stats?.opened ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <svg class="h-5 w-5 text-accent-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    <p class="mb-1 font-body text-sm text-copy">Clicked</p>
                    <p class="font-title text-2xl font-bold text-primary-1">{{ stats?.clicked ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <svg class="h-5 w-5 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="mb-1 font-body text-sm text-copy">Templates</p>
                    <p class="font-title text-2xl font-bold text-primary-1">{{ templates.length }}</p>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="font-title text-lg font-bold text-primary-1">Email Templates</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div
                            v-for="template in templates"
                            :key="template.id"
                            class="rounded-lg border border-gray-200 p-4 transition-all hover:shadow-md"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="mb-2 flex items-center gap-3">
                                        <h3 class="font-title font-bold text-primary-1">
                                            {{ template.name }}
                                        </h3>
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                            :class="typeColors[template.type] || typeColors.custom"
                                        >
                                            {{ template.type.charAt(0).toUpperCase() + template.type.slice(1) }}
                                        </span>
                                    </div>
                                    <p class="mb-2 font-body text-sm text-copy">
                                        <strong>Subject:</strong> {{ template.subject }}
                                    </p>
                                    <p class="font-body text-xs text-copy">
                                        Last modified: {{ formatDate(template.last_modified) }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        @click="handlePreview(template)"
                                        class="rounded p-2 text-green-600 transition-colors hover:bg-green-50"
                                        title="Preview"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        @click="handleEditTemplate(template)"
                                        class="rounded p-2 text-primary-2 transition-colors hover:bg-primary-2/10"
                                        title="Edit"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        @click="duplicateTemplate(template)"
                                        class="rounded p-2 text-accent-2 transition-colors hover:bg-accent-2/10"
                                        title="Duplicate"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        @click="deleteTemplate(template.id)"
                                        class="rounded p-2 text-red-600 transition-colors hover:bg-red-50"
                                        title="Delete"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="!templates.length" class="py-8 text-center">
                            <p class="font-body text-copy">No email templates yet. Create your first template to get started.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Template Editor Modal -->
            <div
                v-if="isEditing || isCreating"
                class="fixed inset-0 z-50 overflow-y-auto bg-black/50"
            >
                <div class="flex min-h-screen items-start justify-center p-4 py-8">
                    <div class="w-full max-w-5xl rounded-xl bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-gray-200 bg-gradient-to-r from-primary-2 to-primary-2/80 p-6">
                            <h2 class="font-title text-xl font-bold text-white">
                                {{ isCreating ? 'Create Email Template' : `Edit Email Template: ${selectedTemplate?.name || ''}` }}
                            </h2>
                            <button
                                type="button"
                                @click="closeModal"
                                class="rounded-lg p-2 text-white transition-colors hover:bg-white/10"
                            >
                                <span class="text-2xl leading-none">&times;</span>
                            </button>
                        </div>

                        <div class="space-y-6 p-6">
                            <div class="rounded-lg border border-accent-2/30 bg-accent-2/5 p-4">
                                <p class="mb-2 font-body text-sm font-medium text-primary-1">
                                    Available Placeholders:
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-for="placeholder in placeholders"
                                        :key="placeholder"
                                        type="button"
                                        @click="insertPlaceholder(placeholder)"
                                        class="cursor-pointer rounded border border-primary-2/20 bg-white px-2 py-1 font-mono text-xs text-primary-2 transition-colors hover:bg-primary-2/10"
                                        :title="`Click to insert ${placeholder}`"
                                    >
                                        {{ placeholder }}
                                    </button>
                                </div>
                                <p class="mt-2 font-body text-xs text-copy">
                                    <strong>Note:</strong> {Login Link} will be replaced with the actual URL to the 3rd party ticketing platform.
                                </p>
                            </div>

                            <div>
                                <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                    Template Name
                                </label>
                                <input
                                    v-model="templateForm.name"
                                    type="text"
                                    placeholder="Enter template name..."
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                                />
                                <div v-if="templateForm.errors.name" class="mt-1 text-xs text-red-600">
                                    {{ templateForm.errors.name }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                    Template Type
                                </label>
                                <select
                                    v-model="templateForm.type"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                                >
                                    <option value="approval">Approval</option>
                                    <option value="reminder">Reminder</option>
                                    <option value="confirmation">Confirmation</option>
                                    <option value="custom">Custom</option>
                                </select>
                                <div v-if="templateForm.errors.type" class="mt-1 text-xs text-red-600">
                                    {{ templateForm.errors.type }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                    Subject Line
                                </label>
                                <input
                                    v-model="templateForm.subject"
                                    type="text"
                                    placeholder="Enter email subject..."
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                                />
                                <div v-if="templateForm.errors.subject" class="mt-1 text-xs text-red-600">
                                    {{ templateForm.errors.subject }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                    Email Body
                                </label>
                                <textarea
                                    v-model="templateForm.body"
                                    name="body"
                                    rows="12"
                                    placeholder="Enter email body (HTML supported)..."
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-mono text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                                />
                                <p class="mt-2 font-body text-xs text-copy">
                                    Note: HTML formatting is supported. Use placeholders like {First Name} in your content.
                                </p>
                                <div v-if="templateForm.errors.body" class="mt-1 text-xs text-red-600">
                                    {{ templateForm.errors.body }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                    PDF Attachments (optional)
                                </label>

                                <div
                                    v-if="(selectedTemplate?.attachment_pdfs?.length || selectedTemplate?.attachment_pdf)"
                                    class="mb-3 rounded-lg border border-gray-200 bg-gray-50 p-3"
                                >
                                    <p class="mb-2 font-body text-xs font-medium text-primary-1">Current PDFs</p>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(pdf, idx) in (selectedTemplate?.attachment_pdfs?.length ? selectedTemplate.attachment_pdfs : (selectedTemplate?.attachment_pdf ? [selectedTemplate.attachment_pdf] : []))"
                                            :key="pdf + idx"
                                            class="flex items-center justify-between gap-3 rounded border border-gray-200 bg-white p-2"
                                        >
                                            <a :href="pdf" target="_blank" class="truncate font-body text-sm font-medium text-primary-2 underline hover:no-underline">
                                                View PDF {{ idx + 1 }}
                                            </a>
                                            <button
                                                type="button"
                                                class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-200"
                                                @click="templateForm.deleteAttachmentPdfPaths = [...(templateForm.deleteAttachmentPdfPaths || []), pdf]"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                    <div v-if="templateForm.deleteAttachmentPdfPaths?.length" class="mt-3 rounded-lg bg-red-50 p-3">
                                        <p class="font-body text-xs font-medium text-red-700">Some PDFs will be removed on save</p>
                                        <button type="button" class="mt-1 font-body text-xs font-medium text-red-600 underline hover:no-underline" @click="templateForm.deleteAttachmentPdfPaths = []">
                                            Undo all removals
                                        </button>
                                    </div>
                                </div>

                                <div v-if="templateForm.attachmentPdfFiles?.length" class="mb-3 rounded-lg bg-green-50 p-3">
                                    <p class="mb-1 font-body text-xs font-medium text-green-800">New PDFs to upload:</p>
                                    <ul class="list-disc pl-5 font-body text-xs text-green-800">
                                        <li v-for="(f, i) in templateForm.attachmentPdfFiles" :key="f.name + i">{{ f.name }}</li>
                                    </ul>
                                    <button type="button" class="mt-2 font-body text-xs font-medium text-green-700 underline hover:no-underline" @click="templateForm.attachmentPdfFiles = []">
                                        Clear
                                    </button>
                                </div>

                                <input
                                    type="file"
                                    accept="application/pdf"
                                    multiple
                                    class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-2 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-2/90"
                                    @change="(e) => { const files = Array.from(e.target?.files || []); templateForm.attachmentPdfFiles = files; }"
                                />
                                <p class="mt-1 font-body text-xs text-copy">PDF only. Max 10MB each.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 p-6">
                            <button
                                type="button"
                                @click="closeModal"
                                class="rounded-lg border border-gray-300 px-6 py-2 font-body text-sm transition-colors hover:bg-gray-100"
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                @click="handlePreview({ ...selectedTemplate, subject: templateForm.subject, body: templateForm.body })"
                                class="flex items-center gap-2 rounded-lg border border-gray-300 px-6 py-2 font-body text-sm transition-colors hover:bg-gray-100"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview
                            </button>
                            <button
                                type="button"
                                @click="handleSaveTemplate"
                                class="rounded-lg bg-primary-2 px-6 py-2 font-body text-sm font-medium text-white transition-colors hover:bg-primary-2/90"
                            >
                                Save Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Preview Modal -->
            <div
                v-if="showPreview && selectedTemplate"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div class="w-full max-w-3xl rounded-xl bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b border-gray-200 p-6">
                        <h2 class="font-title text-xl font-bold text-primary-1">Email Preview</h2>
                        <button
                            type="button"
                            @click="closeModal"
                            class="rounded-lg p-2 transition-colors hover:bg-gray-100"
                        >
                            <span class="text-2xl leading-none">&times;</span>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="mb-2 font-body text-xs text-copy">
                                Preview with sample data:
                            </p>
                            <div class="space-y-1 font-body text-sm">
                                <p>
                                    <strong>To:</strong> john.smith@example.com
                                </p>
                                <p>
                                    <strong>Subject:</strong> {{ selectedTemplate.subject }}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-inner">
                            <div v-html="replacePlaceholders(selectedTemplate.body)" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 p-6">
                        <button
                            type="button"
                            @click="closeModal"
                            class="rounded-lg border border-gray-300 px-6 py-2 font-body text-sm transition-colors hover:bg-gray-100"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-lg bg-primary-2 px-6 py-2 font-body text-sm text-white transition-colors hover:bg-primary-2/90"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Send Test Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
