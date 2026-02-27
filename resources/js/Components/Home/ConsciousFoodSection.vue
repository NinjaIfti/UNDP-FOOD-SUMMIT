<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <section class="relative overflow-hidden bg-primary-2/10 py-16 sm:py-24">
        <div class="absolute inset-0 opacity-10">
            <img src="/brand/img41.jpg" alt="" class="h-full w-full object-cover" aria-hidden="true" />
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal :delay="100">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="mx-auto font-title text-2xl font-bold leading-tight text-primary-1 sm:text-3xl lg:text-4xl" style="text-wrap: balance; max-width: 100%;">
                        {{ settings.conscious_food_title }}
                    </h2>
                    <p class="mt-6 font-body text-lg leading-relaxed text-ink">
                        {{ settings.conscious_food_description }}
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-copy">
                        {{ settings.conscious_food_cofsa_text }}
                    </p>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
