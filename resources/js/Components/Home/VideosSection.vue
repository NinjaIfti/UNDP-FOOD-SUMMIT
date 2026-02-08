<script setup>
import SectionReveal from './SectionReveal.vue';

defineProps({
    summitVideoUrl: { type: String, default: '' },
    hpmVideoUrl: { type: String, default: '' },
});
</script>

<template>
    <section class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">Videos</h2>
                <div class="mt-12 grid gap-8 md:grid-cols-2">
                    <div class="overflow-hidden rounded-xl bg-gray-100 shadow-lg transition hover:shadow-xl">
                        <div class="aspect-video bg-gray-200">
                            <iframe
                                v-if="summitVideoUrl"
                                :src="`${summitVideoUrl}?rel=0`"
                                title="Summit Video"
                                class="h-full w-full"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            />
                            <div v-else class="flex h-full items-center justify-center text-copy">Summit video coming soon</div>
                        </div>
                        <p class="bg-primary-1 p-4 font-title text-sm font-semibold text-white sm:text-base">Summit Video</p>
                    </div>
                    <div class="overflow-hidden rounded-xl bg-gray-100 shadow-lg transition hover:shadow-xl">
                        <div class="aspect-video bg-gray-200">
                            <iframe
                                v-if="hpmVideoUrl"
                                :src="`${hpmVideoUrl}?rel=0`"
                                title="HPM Video"
                                class="h-full w-full"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            />
                            <div v-else class="flex h-full items-center justify-center text-copy">HPM video coming soon</div>
                        </div>
                        <p class="bg-primary-1 p-4 font-title text-sm font-semibold text-white sm:text-base">HPM Video</p>
                    </div>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
