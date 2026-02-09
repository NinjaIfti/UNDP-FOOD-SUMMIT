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
                        Food systems are at the heart of pressing global challenges: climate change, environmental pollution, biodiversity decline, and land degradation. The need for a bold and innovative path, harnessing the transformative power of consciousness to unlock food system transformation, has never been more urgent and critical. By deepening the connection to self, others, and Nature, and promoting human values, consciousness is a powerful lever to support the transformation of food systems with value-driven decision making for healthy diets, climate resilience, agro-ecology, policy and value chains through meaningful multi-stakeholder collaboration.
                    </p>
                    <p class="mt-4 font-body text-base leading-relaxed text-white/80">
                        The <a href="https://consciousfoodsystems.org/" target="_blank" rel="noopener noreferrer" class="font-semibold text-accent-2 underline decoration-accent-2/40 underline-offset-2 transition hover:decoration-accent-2">Conscious Food Systems Alliance (CoFSA)</a>, convened by UNDP, is a movement of food, agriculture, and consciousness practitioners united around a common goal: to support people from across food and agriculture systems to cultivate the inner capacities that activate systemic change and regeneration.
                    </p>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
