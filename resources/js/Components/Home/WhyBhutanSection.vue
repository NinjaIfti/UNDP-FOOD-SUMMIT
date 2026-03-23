<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    settings: Object,
});
</script>

<template>
    <section class="bg-[#F4F1EA] pt-16 pb-8 sm:pt-24 sm:pb-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-16">
                <SectionReveal>
                    <div class="overflow-hidden rounded-none shadow-xl">
                        <img
                            :src="settings.bhutan_image || '/brand/img41.jpg'"
                            alt="Community in agriculture - Bhutan"
                            class="h-64 w-full rounded-none object-cover sm:h-80 lg:h-96"
                            loading="lazy"
                        />
                    </div>
                </SectionReveal>
                <SectionReveal :delay="150">
                    <h2 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">{{ settings.bhutan_title }}</h2>
                    <p class="mt-6 font-body text-base leading-relaxed text-ink sm:text-lg">
                        {{ settings.bhutan_text1 }}
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-copy sm:text-lg">
                        {{ settings.bhutan_text2 }}
                    </p>
                </SectionReveal>
            </div>
        </div>
    </section>
</template>
