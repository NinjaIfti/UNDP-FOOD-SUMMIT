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
                        Bhutan offers a uniquely inspiring setting for this summit. Guided by the holistic philosophy of Gross National Happiness, and through forward-looking initiatives, such as the Gelephu Mindfulness City, the G-ZERO Forum, and the Bhutan Innovation Lab (BIL), Bhutan has demonstrated global leadership in climate action and sustainable development, and offers an example of mainly organic and Nature-friendly food systems, highlighting the role of culture and spirituality for food systems sustainability.
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                        The Summit will advance Bhutan’s National Food Systems Transformation Pathways by cultivating a conscious inner mindset, while also highlighting Bhutan’s leadership in driving conscious food systems transformation.
                    </p>
                </SectionReveal>
            </div>
        </div>
    </section>
</template>
