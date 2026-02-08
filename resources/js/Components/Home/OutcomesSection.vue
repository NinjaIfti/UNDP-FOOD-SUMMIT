<script setup>
import SectionReveal from './SectionReveal.vue';

const outcomes = [
    'High-level dialogue on conscious food systems',
    'Knowledge exchange and innovation showcase',
    'Partnership-building across sectors',
    'Bhutan leadership platform for transformation',
    'Collective action and investment mobilization',
];
</script>

<template>
    <section id="outcomes" class="scroll-mt-24 bg-white py-16 sm:py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">Outcomes</h2>
                <ul class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <li v-for="(outcome, i) in outcomes" :key="i" class="group flex items-start gap-3 rounded-xl border border-primary-2/20 bg-primary-2/5 p-6 transition hover:border-accent-2/30 hover:shadow-lg">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent-2 font-title text-sm font-bold text-primary-1">{{ i + 1 }}</span>
                        <span class="font-body text-base leading-relaxed text-ink sm:text-lg">{{ outcome }}</span>
                    </li>
                </ul>
            </SectionReveal>
        </div>
    </section>
</template>
