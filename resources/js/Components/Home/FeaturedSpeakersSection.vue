<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    speakers: {
        type: Array,
        default: () => [
            { name: 'Speaker One', role: 'Policy & Governance', bio: 'Leading expert in food policy and governance with over 20 years of experience.', image: '/brand/img22.jpg' },
            { name: 'Speaker Two', role: 'Agroecology', bio: 'Pioneer in agroecological practices and sustainable farming systems.', image: '/brand/img29.jpg' },
            { name: 'Speaker Three', role: 'Indigenous Knowledge', bio: 'Advocate for indigenous food systems and traditional agricultural wisdom.', image: '/brand/img30.jpg' },
            { name: 'Speaker Four', role: 'Youth & Movement Building', bio: 'Youth leader driving food systems transformation across communities.', image: '/brand/img31.jpg' },
            { name: 'Speaker Five', role: 'Food Systems', bio: 'Researcher focused on conscious and regenerative food systems.', image: '/brand/img32.jpg' },
            { name: 'Speaker Six', role: 'Sustainability', bio: 'Environmental strategist working on sustainable food supply chains.', image: '/brand/img33.jpg' },
        ],
    },
});
</script>

<template>
    <section id="speakers" class="scroll-mt-24 bg-primary-2/5 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                    Featured Speakers
                </h2>
                <p class="mt-4 text-center font-body text-base text-copy sm:text-lg">
                    Leading voices in conscious food systems transformation
                </p>
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="speaker in speakers"
                        :key="speaker.name"
                        class="group"
                    >
                        <div class="overflow-hidden rounded-xl bg-white shadow-md transition duration-300 hover:shadow-xl">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img
                                    :src="speaker.image"
                                    :alt="speaker.name"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                />
                            </div>
                            <div class="p-6">
                                <h3 class="font-title text-lg font-semibold text-primary-1">{{ speaker.name }}</h3>
                                <p class="mt-1 font-body text-sm font-medium text-accent-1">{{ speaker.role }}</p>
                                <p class="mt-2 line-clamp-2 font-body text-sm text-copy">{{ speaker.bio }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
