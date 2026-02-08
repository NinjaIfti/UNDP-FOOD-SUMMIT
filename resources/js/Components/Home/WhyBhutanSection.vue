<script setup>
import SectionReveal from './SectionReveal.vue';
</script>

<template>
    <section class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
                <SectionReveal>
                    <div class="overflow-hidden rounded-2xl shadow-xl">
                        <img src="/brand/img41.jpg" alt="Community in agriculture - Bhutan" class="h-64 w-full object-cover sm:h-80 lg:h-96" loading="lazy" />
                    </div>
                </SectionReveal>
                <SectionReveal :delay="150">
                    <h2 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">Bhutan: An Inspiration and Innovator in Sustainable Transformation</h2>
                    <p class="mt-6 font-body text-base leading-relaxed text-ink sm:text-lg">
                        Bhutan brings unique pathways grounded in Gross National Happiness, sustainability, and harmony with nature. The Summit showcases the country's leadership in national food systems transformation, offering a model for conscious, regenerative approaches to how we grow, share, and value food.
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                        The sacred landscapes of the Himalayas, deep-rooted cultural traditions, and a constitutional commitment to environmental preservation make Bhutan the ideal host for this global conversation.
                    </p>
                </SectionReveal>
            </div>
        </div>
    </section>
</template>
