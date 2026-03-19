<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    faq_section_title: props.settings?.faq_section_title ?? 'Frequently Asked Questions',
    faqs: (Array.isArray(props.settings?.faqs) && props.settings.faqs.length > 0
        ? props.settings.faqs
        : [
            { question: 'When and where is the Summit?', answer: 'The Global Conscious Food Systems Summit will take place from 31 August to 4 September 2026 in Thimphu, Bhutan.' },
            { question: 'Who can attend?', answer: 'The Summit convenes 350–500 national and global changemakers. Registration will open soon.' },
            { question: 'How can I contribute?', answer: 'You can contribute by responding to our Call for Submissions or helping spread the word using our Communication Kit.' },
        ]
    ).map(f => ({ question: f.question ?? '', answer: f.answer ?? '' })),
});

function addFaq() {
    form.faqs.push({ question: '', answer: '' });
}

function removeFaq(index) {
    form.faqs.splice(index, 1);
}

function insertFormatting(faqIndex, tag) {
    const textarea = document.getElementById(`answer-${faqIndex}`);
    if (!textarea) return;
    
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = form.faqs[faqIndex].answer.substring(start, end);
    const before = form.faqs[faqIndex].answer.substring(0, start);
    const after = form.faqs[faqIndex].answer.substring(end);
    
    let replacement = '';
    if (tag === 'bold') {
        replacement = selectedText ? `<strong>${selectedText}</strong>` : '<strong></strong>';
    } else if (tag === 'bullet') {
        replacement = selectedText ? `<ul><li>${selectedText}</li></ul>` : '<ul><li></li></ul>';
    } else if (tag === 'paragraph') {
        replacement = selectedText ? `<p>${selectedText}</p>` : '<p></p>';
    }
    
    form.faqs[faqIndex].answer = before + replacement + after;
    
    setTimeout(() => {
        textarea.focus();
        const newPos = tag === 'bold' ? start + (selectedText ? 8 : 8) : start + replacement.length;
        textarea.setSelectionRange(newPos, newPos);
    }, 10);
}

function addBulletPoint(faqIndex) {
    const textarea = document.getElementById(`answer-${faqIndex}`);
    if (!textarea) return;
    
    const start = textarea.selectionStart;
    const before = form.faqs[faqIndex].answer.substring(0, start);
    const after = form.faqs[faqIndex].answer.substring(start);
    
    form.faqs[faqIndex].answer = before + '<ul><li></li></ul>' + after;
    
    setTimeout(() => {
        textarea.focus();
        const newPos = start + 8;
        textarea.setSelectionRange(newPos, newPos);
    }, 10);
}

function submit() {
    form.post(route('admin.faq.update'), { preserveScroll: true });
}
</script>

<template>
    <Head title="Edit FAQs" />
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
                <h2 class="font-title text-xl font-bold text-primary-1 sm:text-2xl">Edit FAQs</h2>
                <a
                    href="/faq"
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
                    <div class="mb-4">
                        <label class="block font-body text-sm font-medium text-ink">Section Title</label>
                        <input v-model="form.faq_section_title" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(faq, index) in form.faqs"
                            :key="index"
                            class="rounded-lg border border-gray-200 p-4"
                        >
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="font-title text-sm font-bold text-primary-1">Question {{ index + 1 }}</h4>
                                <button type="button" @click="removeFaq(index)" class="rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200">
                                    Delete
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block font-body text-xs font-medium text-ink">Question</label>
                                    <input v-model="faq.question" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" placeholder="Enter the question" />
                                </div>
                                <div>
                                    <div class="mb-2 flex items-center justify-between">
                                        <label class="block font-body text-xs font-medium text-ink">Answer</label>
                                        <div class="flex gap-1">
                                            <button
                                                type="button"
                                                @click="insertFormatting(index, 'bold')"
                                                class="rounded border border-gray-300 bg-white px-2 py-1 text-xs font-semibold text-gray-700 transition hover:bg-gray-50"
                                                title="Bold"
                                            >
                                                <strong>B</strong>
                                            </button>
                                            <button
                                                type="button"
                                                @click="insertFormatting(index, 'bullet')"
                                                class="rounded border border-gray-300 bg-white px-2 py-1 text-xs text-gray-700 transition hover:bg-gray-50"
                                                title="Bullet List"
                                            >
                                                •
                                            </button>
                                            <button
                                                type="button"
                                                @click="insertFormatting(index, 'paragraph')"
                                                class="rounded border border-gray-300 bg-white px-2 py-1 text-xs text-gray-700 transition hover:bg-gray-50"
                                                title="Paragraph"
                                            >
                                                ¶
                                            </button>
                                        </div>
                                    </div>
                                    <textarea
                                        :id="`answer-${index}`"
                                        v-model="faq.answer"
                                        rows="6"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                                        placeholder="Enter the answer. Use formatting buttons or HTML tags like &lt;strong&gt;bold&lt;/strong&gt;, &lt;p&gt;paragraph&lt;/p&gt;, &lt;ul&gt;&lt;li&gt;bullet&lt;/li&gt;&lt;/ul&gt;"
                                    ></textarea>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Tip: You can use HTML tags for formatting. <code class="rounded bg-gray-100 px-1">&lt;strong&gt;text&lt;/strong&gt;</code> for bold, <code class="rounded bg-gray-100 px-1">&lt;p&gt;text&lt;/p&gt;</code> for paragraphs, <code class="rounded bg-gray-100 px-1">&lt;ul&gt;&lt;li&gt;item&lt;/li&gt;&lt;/ul&gt;</code> for bullet lists.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="addFaq" class="w-full rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 py-3 font-body text-sm font-medium text-gray-600 transition hover:border-primary-1 hover:bg-primary-1/5 hover:text-primary-1">
                            + Add FAQ
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
