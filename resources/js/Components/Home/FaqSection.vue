<script setup>
import { ref, computed } from 'vue';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

const openIndex = ref(null);

const faqTitle = computed(() => props.settings?.faq_section_title || 'Frequently Asked Questions');

const faqs = computed(() => {
    const list = props.settings?.faqs;
    if (Array.isArray(list) && list.length) return list.filter(f => f && (f.question || f.answer));
    return [
        { question: 'When and where is the Summit?', answer: 'The Global Conscious Food Systems Summit will take place from 31 August to 4 September 2026 in Thimphu, Bhutan.' },
        { question: 'Who can attend?', answer: 'The Summit convenes 350–500 national and global changemakers. Registration will open soon.' },
        { question: 'How can I contribute?', answer: 'You can contribute by responding to our Call for Submissions or helping spread the word using our Communication Kit.' },
    ];
});

function toggle(index) {
    openIndex.value = openIndex.value === index ? null : index;
}
</script>

<template>
    <section class="bg-primary-1/10 py-16 sm:py-24 lg:py-32">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                    {{ faqTitle }}
                </h2>
                <div class="mt-12 space-y-3">
                    <div
                        v-for="(faq, index) in faqs"
                        :key="index"
                        class="overflow-hidden rounded-xl border border-primary-1/20 bg-primary-1/5 shadow-sm transition hover:shadow-md"
                    >
                        <button
                            type="button"
                            @click="toggle(index)"
                            class="flex w-full items-center justify-between gap-4 px-6 py-4 text-left font-body transition hover:bg-primary-1/10"
                        >
                            <span class="font-body text-base font-semibold text-primary-1 sm:text-lg">{{ faq.question }}</span>
                            <svg
                                class="h-5 w-5 shrink-0 text-primary-1 transition-transform"
                                :class="openIndex === index ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            v-show="openIndex === index"
                            class="border-t border-primary-1/10 px-6 py-4"
                        >
                            <div class="faq-answer font-body text-base leading-relaxed text-ink" v-html="faq.answer"></div>
                        </div>
                    </div>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>

<style scoped>
.faq-answer :deep(p) {
    margin-bottom: 1rem;
}

.faq-answer :deep(p:last-child) {
    margin-bottom: 0;
}

.faq-answer :deep(strong) {
    font-weight: 600;
    color: inherit;
}

.faq-answer :deep(ul) {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
    margin-top: 0.5rem;
}

.faq-answer :deep(ul:last-child) {
    margin-bottom: 0;
}

.faq-answer :deep(li) {
    margin-bottom: 0.5rem;
}

.faq-answer :deep(li:last-child) {
    margin-bottom: 0;
}
</style>
