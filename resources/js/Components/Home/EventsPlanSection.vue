<script setup>
import { ref } from 'vue';
import SectionReveal from './SectionReveal.vue';

const activeDay = ref(0);

const days = [
    {
        date: '31 Aug',
        label: 'Day 1',
        title: 'Opening & Welcome',
        schedule: [
            { time: '08:00 – 09:00', title: 'Registration & Welcome Coffee', type: 'break', speaker: '' },
            { time: '09:00 – 09:30', title: 'Opening Ceremony', type: 'event', speaker: 'H.E. Prime Minister of Bhutan' },
            { time: '09:30 – 10:30', title: 'Keynote: Rethinking Global Food Systems', type: 'speaker', speaker: 'Dr. Agnes Kalibata' },
            { time: '10:30 – 11:00', title: 'Tea Break', type: 'break', speaker: '' },
            { time: '11:00 – 12:30', title: 'Panel: Conscious Food – From Vision to Reality', type: 'speaker', speaker: 'Dr. Vandana Shiva, Prof. Parviz Koohafkan, Dr. Cherrie Atilano' },
            { time: '12:30 – 14:00', title: 'Lunch Break & Networking', type: 'break', speaker: '' },
            { time: '14:00 – 15:30', title: 'Plenary: Indigenous Wisdom in Modern Agriculture', type: 'speaker', speaker: 'Dasho Kinzang Dorji' },
            { time: '15:30 – 16:00', title: 'Coffee Break', type: 'break', speaker: '' },
            { time: '16:00 – 17:30', title: 'Fireside Chat: Youth & the Future of Food', type: 'speaker', speaker: 'Karma Yangzom, Tenzin Lhamo' },
            { time: '18:30 – 20:00', title: 'Welcome Dinner – Traditional Bhutanese Cuisine', type: 'break', speaker: '' },
        ],
    },
    {
        date: '1 Sep',
        label: 'Day 2',
        title: 'Plenary Sessions',
        schedule: [
            { time: '08:30 – 09:00', title: 'Morning Mindfulness & Wellness', type: 'break', speaker: '' },
            { time: '09:00 – 10:30', title: 'Keynote: Gross National Happiness & Food Sovereignty', type: 'speaker', speaker: 'Dasho Karma Ura' },
            { time: '10:30 – 11:00', title: 'Tea Break', type: 'break', speaker: '' },
            { time: '11:00 – 12:30', title: 'Panel: Climate-Resilient Food Production', type: 'speaker', speaker: 'Dr. Rattan Lal, Dr. Sonam Wangmo' },
            { time: '12:30 – 14:00', title: 'Lunch Break', type: 'break', speaker: '' },
            { time: '14:00 – 15:30', title: 'Workshop: Regenerative Farming Practices', type: 'event', speaker: 'Multiple Facilitators' },
            { time: '15:30 – 16:00', title: 'Coffee Break', type: 'break', speaker: '' },
            { time: '16:00 – 17:30', title: 'Panel: Technology & Traditional Knowledge', type: 'speaker', speaker: 'Dr. Tshering Penjor, Sarah Chen' },
        ],
    },
    {
        date: '2 Sep',
        label: 'Day 3',
        title: 'Workshops & Field Visits',
        schedule: [
            { time: '07:00 – 08:00', title: 'Breakfast', type: 'break', speaker: '' },
            { time: '08:00 – 12:00', title: 'Field Visit: Organic Farms in Paro Valley', type: 'event', speaker: 'Guided by Local Farmers' },
            { time: '12:00 – 13:30', title: 'Farm-to-Table Lunch Experience', type: 'break', speaker: '' },
            { time: '13:30 – 15:00', title: 'Workshop: Bhutan\'s 100% Organic Vision', type: 'event', speaker: 'Ministry of Agriculture Officials' },
            { time: '15:00 – 15:30', title: 'Tea Break', type: 'break', speaker: '' },
            { time: '15:30 – 17:00', title: 'Breakout Sessions: Regional Food System Strategies', type: 'event', speaker: 'Regional Leaders' },
            { time: '19:00 – 21:00', title: 'Cultural Evening & Traditional Performances', type: 'break', speaker: '' },
        ],
    },
    {
        date: '3 Sep',
        label: 'Day 4',
        title: 'Partnership Building',
        schedule: [
            { time: '08:30 – 09:00', title: 'Morning Reflection', type: 'break', speaker: '' },
            { time: '09:00 – 10:30', title: 'Keynote: Public-Private Partnerships for Food Security', type: 'speaker', speaker: 'UNDP Resident Representative' },
            { time: '10:30 – 11:00', title: 'Tea Break', type: 'break', speaker: '' },
            { time: '11:00 – 12:30', title: 'Panel: Financing Sustainable Food Systems', type: 'speaker', speaker: 'World Bank, FAO Representatives' },
            { time: '12:30 – 14:00', title: 'Networking Lunch', type: 'break', speaker: '' },
            { time: '14:00 – 15:30', title: 'MoU Signing Ceremony & Partnership Announcements', type: 'event', speaker: 'Various Delegations' },
            { time: '15:30 – 16:00', title: 'Coffee Break', type: 'break', speaker: '' },
            { time: '16:00 – 17:30', title: 'Roundtable: South-South Cooperation in Agriculture', type: 'speaker', speaker: 'Country Representatives' },
        ],
    },
    {
        date: '4 Sep',
        label: 'Day 5',
        title: 'Closing & Commitments',
        schedule: [
            { time: '08:30 – 09:00', title: 'Morning Mindfulness', type: 'break', speaker: '' },
            { time: '09:00 – 10:30', title: 'Synthesis: Key Outcomes & Recommendations', type: 'event', speaker: 'Summit Rapporteur' },
            { time: '10:30 – 11:00', title: 'Tea Break', type: 'break', speaker: '' },
            { time: '11:00 – 12:00', title: 'Bhutan Declaration on Conscious Food Systems', type: 'event', speaker: 'Co-Chairs' },
            { time: '12:00 – 13:00', title: 'Closing Ceremony & Remarks', type: 'event', speaker: 'H.E. Prime Minister, UNDP Administrator' },
            { time: '13:00 – 14:30', title: 'Farewell Lunch', type: 'break', speaker: '' },
        ],
    },
];
</script>

<template>
    <section id="agenda" class="scroll-mt-24 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <SectionReveal>
                <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">Events Plan</h2>
                <p class="mx-auto mt-3 max-w-2xl text-center font-body text-copy">31 August – 4 September 2026 · Thimphu, Bhutan</p>

                <!-- Day Tabs -->
                <div class="mt-10 flex flex-wrap justify-center gap-2">
                    <button
                        v-for="(day, i) in days"
                        :key="i"
                        @click="activeDay = i"
                        class="rounded-full px-5 py-2.5 font-title text-sm font-semibold transition sm:px-6"
                        :class="activeDay === i
                            ? 'bg-accent-2 text-primary-1 shadow-md'
                            : 'bg-primary-2/10 text-primary-1 hover:bg-primary-2/20'"
                    >
                        <span class="hidden sm:inline">{{ day.date }} · </span>{{ day.label }}
                    </button>
                </div>

                <!-- Day Title -->
                <div class="mt-8 text-center">
                    <h3 class="font-title text-xl font-bold text-primary-1 sm:text-2xl">{{ days[activeDay].title }}</h3>
                    <p class="mt-1 font-title text-sm font-medium text-accent-2">{{ days[activeDay].date }}, 2026</p>
                </div>

                <!-- Schedule Timeline -->
                <div class="mt-8">
                    <div class="relative mx-auto max-w-3xl">
                        <!-- Timeline line -->
                        <div class="absolute left-[7px] top-3 bottom-3 w-0.5 bg-primary-2/20 sm:left-[99px]" />

                        <div
                            v-for="(item, i) in days[activeDay].schedule"
                            :key="i"
                            class="relative flex gap-4 py-3 sm:gap-6"
                        >
                            <!-- Time -->
                            <div class="hidden w-20 shrink-0 pt-0.5 text-right sm:block">
                                <span class="font-title text-sm font-medium text-copy">{{ item.time }}</span>
                            </div>

                            <!-- Dot -->
                            <div class="relative z-10 mt-1.5 flex shrink-0">
                                <span
                                    class="h-4 w-4 rounded-full border-2"
                                    :class="item.type === 'break'
                                        ? 'border-gray-300 bg-gray-100'
                                        : item.type === 'speaker'
                                            ? 'border-accent-2 bg-accent-2'
                                            : 'border-primary-2 bg-primary-2'"
                                />
                            </div>

                            <!-- Content Card -->
                            <div
                                class="flex-1 rounded-xl border px-5 py-4 transition"
                                :class="item.type === 'break'
                                    ? 'border-gray-200 bg-gray-50'
                                    : 'border-primary-2/20 bg-primary-2/5 hover:shadow-md'"
                            >
                                <p class="font-title text-xs font-medium text-accent-2 sm:hidden">{{ item.time }}</p>
                                <h4
                                    class="font-title font-semibold"
                                    :class="item.type === 'break' ? 'text-sm text-copy' : 'text-base text-primary-1'"
                                >
                                    {{ item.title }}
                                </h4>
                                <p v-if="item.speaker" class="mt-1 font-body text-sm text-copy">
                                    <svg class="mr-1 inline h-3.5 w-3.5 text-accent-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ item.speaker }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </SectionReveal>
        </div>
    </section>
</template>
