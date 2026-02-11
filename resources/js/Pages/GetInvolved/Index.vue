<script setup>
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import SectionReveal from '@/Components/Home/SectionReveal.vue';

const submissionCategories = [
    {
        title: 'Whole Session',
        description: 'For applicants who propose to design and facilitate a full session during the Summit, workshops should be interactive and experiential, and focus on the themes illustrated below (see "Submission Themes"). The sessions will be 1.15 hrs length.',
        icon: 'presentation'
    },
    {
        title: 'Case Study',
        description: 'For individuals or organizations not yet ready to propose a full interactive session, there is the option to submit a case study, experience, or initiative for inclusion in a panel-style or collective session with other contributors. Case studies should illustrate practical experiences, lessons learned, or emerging insights related to conscious food systems. The CoFSA team will propose how to integrate this in a session.',
        icon: 'document'
    }
];
</script>

<template>
    <Head title="Get Involved" />
    <EventLayout>
        <section class="bg-gradient-to-b from-accent-1/10 to-white py-16 sm:py-24 lg:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl lg:text-5xl">
                            Call for Submissions
                        </h1>
                        <p class="mt-6 font-body text-lg leading-relaxed text-ink sm:text-xl">
                            We warmly invite you to co-create the agenda of the first-ever Global Conscious Food Systems Summit, taking place in Bhutan from <span class="font-semibold">31 August – 4 September 2026</span>.
                        </p>
                    </div>

                    <div class="mx-auto mt-12 max-w-4xl rounded-2xl bg-white p-8 shadow-sm sm:mt-16 sm:p-12">
                        <p class="font-body text-base leading-relaxed text-copy sm:text-lg">
                            CoFSA invites submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.
                        </p>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <section class="bg-white py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                            Submission Categories
                        </h2>
                        <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                            Applicants may submit one or more proposals under the following categories:
                        </p>
                    </div>

                    <div class="mx-auto mt-12 grid max-w-5xl gap-8 sm:mt-16 lg:grid-cols-2">
                        <div
                            v-for="(category, index) in submissionCategories"
                            :key="index"
                            class="flex flex-col rounded-2xl border border-primary-2/20 bg-white p-8 shadow-sm transition hover:shadow-md"
                        >
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-accent-2">
                                    <svg v-if="category.icon === 'presentation'" class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                    </svg>
                                    <svg v-else class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-title text-xl font-bold text-primary-1">{{ category.title }}</h3>
                                    <p class="mt-3 font-body text-base leading-relaxed text-copy">{{ category.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section>

        <section class="bg-primary-2/5 py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <p class="font-body text-lg leading-relaxed text-ink sm:text-xl">
                            If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.
                        </p>
                        <p class="mt-6 font-body text-base leading-relaxed text-copy sm:text-lg">
                            All submissions should be <span class="font-semibold">experiential and participatory</span>, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.
                        </p>
                        <p class="mt-6 font-body text-base leading-relaxed text-ink sm:text-lg font-semibold">
                            Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.
                        </p>
                    </div>

                    <div class="mx-auto mt-12 flex max-w-3xl flex-col gap-6 sm:mt-16">
                        <a 
                            href="https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="group flex items-center justify-between rounded-2xl bg-accent-1 p-6 shadow-lg transition hover:bg-accent-1/90 hover:shadow-xl sm:p-8"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-title text-lg font-bold text-white sm:text-xl">Submit Your Application</p>
                                    <p class="mt-1 font-body text-sm text-white/90">Complete the application form by 31st March</p>
                                </div>
                            </div>
                            <svg class="h-6 w-6 shrink-0 text-white transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>

                        <a 
                            href="https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="group flex items-center justify-between rounded-2xl border-2 border-primary-1 bg-white p-6 shadow-sm transition hover:bg-primary-1/5 hover:shadow-md sm:p-8"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-1">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-title text-lg font-bold text-primary-1 sm:text-xl">Full Submission Guidelines</p>
                                    <p class="mt-1 font-body text-sm text-copy">View details, eligibility criteria and guidelines</p>
                                </div>
                            </div>
                            <svg class="h-6 w-6 shrink-0 text-primary-1 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </SectionReveal>
            </div>
        </section>
    </EventLayout>
</template>
