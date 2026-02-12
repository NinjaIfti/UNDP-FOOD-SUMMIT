<script setup>
import { computed } from 'vue';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

const organizers = computed(() => [
    { 
        name: 'Royal Government of Bhutan', 
        logo: props.settings.govt_logo || '/brand/govt.png', 
        url: 'https://www.gov.bt' 
    },
    { 
        name: 'United Nations Development Programme', 
        logo: props.settings.undp_logo || '/brand/undp.png', 
        url: 'https://www.undp.org/bhutan' 
    },
    { 
        name: 'Conscious Food Systems Alliance', 
        logo: props.settings.cofsa_logo || '/brand/cofsa.png', 
        url: 'https://consciousfoodsystems.org' 
    }
]);
</script>

<template>
    <section class="relative overflow-hidden border-y border-primary-2/20 bg-white py-16 sm:py-20 lg:py-24">
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                    {{ settings.coorganizers_title }}
                </h2>
                
                <div class="mt-12 grid grid-cols-3 gap-6 sm:mt-16 sm:gap-8 lg:gap-16">
                    <a
                        v-for="org in organizers"
                        :key="org.name"
                        :href="org.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex flex-col items-center text-center"
                    >
                        <div class="flex h-32 items-center justify-center sm:h-36 lg:h-40">
                            <img 
                                :src="org.logo" 
                                :alt="org.name" 
                                class="max-h-full w-auto object-contain transition duration-300 group-hover:scale-105"
                            />
                        </div>
                        <h3 class="mt-6 font-title text-lg font-semibold text-primary-1 transition group-hover:text-accent-1 sm:text-xl">
                            {{ org.name }}
                        </h3>
                    </a>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
