<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <section class="relative overflow-hidden bg-primary-1/10 py-16 sm:py-24">
        <div class="absolute inset-0">
            <img
                :src="settings.conscious_food_image || '/brand/img41.jpg'"
                alt=""
                class="h-full w-full object-cover opacity-90"
                aria-hidden="true"
            />
        </div>
        <div class="absolute inset-0 z-[1] bg-gradient-to-b from-primary-1/15 via-primary-1/5 to-primary-1/15" />
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal :delay="100">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="mx-auto font-title text-2xl font-bold leading-tight text-white sm:text-3xl lg:text-4xl" style="text-wrap: balance; max-width: 100%;">
                        {{ settings.conscious_food_title }}
                    </h2>
                    <p class="mt-6 font-body text-lg leading-relaxed text-white/90">
                        {{ settings.conscious_food_description }}
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-white/80">
                        {{ settings.conscious_food_cofsa_text }}
                    </p>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
