<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <section class="bg-[#F4F1EA] py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <div class="text-center">
                    <h2
                        class="font-title text-3xl font-bold uppercase leading-tight tracking-wide text-primary-1 sm:text-4xl lg:text-5xl"
                        style="text-wrap: balance;"
                    >
                        {{ settings.summit_intro_title }}
                    </h2>
                    <!-- whitespace-pre-line so admin textarea newlines show as line breaks -->
                    <p class="mt-6 whitespace-pre-line font-body text-xl leading-relaxed text-ink sm:text-2xl">
                        {{ settings.summit_intro_text1 }}
                    </p>
                    <p class="mt-4 whitespace-pre-line font-body text-xl leading-relaxed text-ink sm:text-2xl">
                        {{ settings.summit_intro_text2 }}
                    </p>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
