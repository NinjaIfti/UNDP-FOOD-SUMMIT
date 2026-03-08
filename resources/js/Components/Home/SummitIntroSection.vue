<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <section class="bg-gradient-to-b from-primary-1/25 to-primary-1/10 py-16 sm:py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <div class="text-center">
                    <h2
                        class="font-title text-2xl font-bold uppercase leading-tight tracking-wide text-primary-1 sm:text-3xl lg:text-4xl"
                        style="text-wrap: balance;"
                    >
                        {{ settings.summit_intro_title }}
                    </h2>
                    <p class="mt-6 font-body text-lg leading-relaxed text-ink sm:text-xl">
                        {{ settings.summit_intro_text1 }}
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                        {{ settings.summit_intro_text2 }}
                    </p>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
