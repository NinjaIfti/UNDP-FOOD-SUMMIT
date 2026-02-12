<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <section class="bg-white py-16 sm:py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <div class="grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
                    <div class="order-2 lg:order-1">
                        <img src="/brand/img55.jpg" alt="Community in agriculture" class="w-full rounded-2xl object-cover shadow-xl" loading="lazy" />
                    </div>
                    <div class="order-1 mx-auto max-w-3xl text-center lg:order-2 lg:text-left">
                    <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl lg:text-4xl">
                        {{ settings.summit_intro_title }}
                    </h2>
                    <p class="mt-6 font-body text-lg leading-relaxed text-ink sm:text-xl">
                        {{ settings.summit_intro_text1 }}
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                        {{ settings.summit_intro_text2 }}
                    </p>
                    </div>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
