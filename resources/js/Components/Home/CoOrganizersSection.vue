<script setup>
import { computed } from 'vue';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

// Co-organizers
const organizers = computed(() => {
    const base = [
        {
            name: 'Conscious Food Systems Alliance',
            logo: props.settings.cofsa_logo || '/brand/cofsa.png',
            url: 'https://consciousfoodsystems.org',
        },
        {
            name: 'Royal Government of Bhutan',
            logo: props.settings.govt_logo || '/brand/govt.png',
            url: 'https://www.gov.bt',
        },
        {
            name: 'United Nations Development Programme',
            logo: props.settings.undp_logo || '/brand/undp.png',
            url: 'https://www.undp.org/bhutan',
        },
    ];

    const extras = [];

    // Optional extra co-organizer
    if (props.settings.coorganizer_logo_5) {
        extras.push({
            name: 'Co-organizer',
            logo: props.settings.coorganizer_logo_5,
            url: null,
        });
    }

    return [...base, ...extras];
});
</script>

<template>
    <section
        class="relative overflow-hidden border-b border-primary-1/20 bg-[#F4F1EA] pt-8 pb-16 sm:pt-10 sm:pb-20 lg:pt-12 lg:pb-24"
    >
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <!-- Heading -->
                <h2
                    class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl"
                >
                    Co-Organizers
                </h2>

                <p
                    v-if="settings.coorganizers_subtitle"
                    class="mx-auto mt-3 max-w-3xl text-center font-body text-base leading-relaxed text-copy sm:text-lg"
                >
                    {{ settings.coorganizers_subtitle }}
                </p>

                <!-- Co-organizer Logos -->
                <div
                    class="mt-8 flex flex-wrap items-center justify-center gap-3 sm:gap-4 lg:gap-5"
                >
                    <component
                        :is="org.url ? 'a' : 'div'"
                        v-for="(org, index) in organizers"
                        :key="org.name + '-' + index"
                        v-bind="
                            org.url
                                ? {
                                      href: org.url,
                                      target: '_blank',
                                      rel: 'noopener noreferrer',
                                  }
                                : {}
                        "
                        class="group flex items-center justify-center"
                    >
                        <div
                            class="flex items-center justify-center"
                            :class="
                                index === 0
                                    ? 'h-14 max-w-[280px] sm:h-[4.5rem] lg:h-20'
                                    : 'h-16 max-w-[350px] sm:h-20 lg:h-24'
                            "
                        >
                            <img
                                :src="org.logo"
                                :alt="org.name"
                                class="max-h-full w-auto object-contain transition duration-300 group-hover:scale-105"
                            />
                        </div>
                    </component>
                </div>

                <!-- Travel Partner -->
                <div
                    v-if="settings.coorganizer_logo_4"
                    class="mt-14 flex flex-col items-center"
                >
                    <h3
                        class="mb-5 font-title text-lg font-semibold uppercase tracking-wider text-primary-1 sm:text-xl"
                    >
                        Travel Partner
                    </h3>

                    <img
                        :src="settings.coorganizer_logo_4"
                        alt="Travel Partner"
                        class="h-16 sm:h-20 lg:h-24 w-auto object-contain transition duration-300 hover:scale-105"
                    />
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
