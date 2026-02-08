<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    events: {
        type: Array,
        default: () => [
            { date: '31 Aug', title: 'Opening & Welcome', desc: 'Official opening ceremony' },
            { date: '1 Sep', title: 'Plenary Sessions', desc: 'High-level dialogue on food systems' },
            { date: '2 Sep', title: 'Workshops & Field Visits', desc: 'Hands-on sessions and site visits' },
            { date: '3 Sep', title: 'Partnership Building', desc: 'Networking and collaboration' },
            { date: '4 Sep', title: 'Closing & Commitments', desc: 'Summary and next steps' },
        ],
    },
});
</script>

<template>
    <section id="agenda" class="scroll-mt-24 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">Events Plan</h2>
                <div class="mt-12 space-y-4">
                    <div
                        v-for="(event, i) in events"
                        :key="i"
                        class="flex flex-col gap-4 rounded-xl border border-primary-2/20 bg-primary-2/5 p-6 sm:flex-row sm:items-center sm:gap-8"
                    >
                        <span class="font-title shrink-0 text-lg font-bold text-accent-2 sm:w-24">{{ event.date }}</span>
                        <div class="flex-1">
                            <h3 class="font-title font-semibold text-primary-1">{{ event.title }}</h3>
                            <p class="mt-1 font-body text-copy">{{ event.desc }}</p>
                        </div>
                    </div>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
