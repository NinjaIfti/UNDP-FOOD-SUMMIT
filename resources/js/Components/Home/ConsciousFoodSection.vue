<script setup>
import SectionReveal from './SectionReveal.vue';
</script>

<template>
    <section class="relative overflow-hidden bg-primary-1 py-16 sm:py-24">
        <div class="absolute inset-0 opacity-10">
            <img src="/brand/img41.jpg" alt="" class="h-full w-full object-cover" aria-hidden="true" />
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal :delay="100">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="font-title text-2xl font-bold leading-tight text-white sm:text-3xl lg:text-4xl">
                        Conscious Food Systems: Not just what we eat, but how we grow, share and value food.
                    </h2>
                    <p class="mt-6 font-body text-lg leading-relaxed text-white/90">
                        The Summit serves as a global platform to advance Bhutan's leadership in national food systems transformation, grounded in Gross National Happiness, sustainability, and harmony with nature.
                    </p>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
