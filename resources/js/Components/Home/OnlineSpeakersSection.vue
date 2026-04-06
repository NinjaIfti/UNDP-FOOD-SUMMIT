<script setup>
import { ref } from 'vue';

const props = defineProps({
  settings: Object,
});

// Speaker data
const speakers = ref([
  {
    id: 1,
    name: "THOMAS HÜBL",
    role: "Teacher, Author & International Facilitator",
    image: "/brand/Onlinespeaker-1.jpg",
    bio: "Thomas Hübl, PhD, is a renowned teacher, author, and international facilitator who works within the complexity of systems and cultural change, integrating the core insights of the great wisdom traditions and mysticism with the discoveries of science. Since the early 2000s, he has led large-scale events and courses on the healing of collective trauma.\n\nHe is the author of Attuned: Practicing Interdependence to Heal Our Trauma—and Our World, and Healing Collective Trauma: A Process for Integrating Our Intergenerational and Cultural Wounds. Hübl has served as an advisor and guest faculty for universities and organizations, as a coach for CEOs and organizational leaders, and is currently a visiting scholar at the Wyss Institute at Harvard University."
  },
  {
    id: 2,
    name: "CHRISTIANA FIGUERES",
    role: "Co-founder, Global Optimism & former Executive Secretary, United Nations Framework Convention on Climate Change",
    image: "/brand/Onlinespeaker-2.png",
    bio: "Christiana Figueres is a Costa Rican citizen and was the Executive Secretary of the United Nations Framework Convention on Climate Change 2010 - 2016.\n\nDuring her tenure, Ms. Figueres brought together national and sub-national governments, corporations and activists, women's groups, scientists and spiritual communities, financial institutions and NGOs to jointly deliver the historic 2015 Paris Agreement on Climate Change. Under this Agreement, 195 sovereign governments decided on a collaborative path to limit future global warming to well below 2°C, and strive for 1.5°C in order to protect the most vulnerable in every nation. For this achievement Ms. Figueres has been credited with forging a new brand of collaborative diplomacy and received multiple awards.\n\nSince then Ms. Figueres has continued to foster rapid action on climate change. She is the co-author of The Future We Choose, The Stubborn Optimist's Guide to the Climate Crisis (Penguin Random House 2019) and co-host of the popular podcast Outrage and Optimism.\n\nShe is a graduate of Swarthmore College and the London School of Economics and has been awarded multiple honorary doctorate degrees from academic institutions such as Yale, University of Massachusetts, Georgetown and University of Edinburgh. After decades of living and working abroad, she has moved back to her beloved Costa Rica. She has two fantastic daughters, Naima and Yihana, fountains of joy."
  },
  {
    id: 3,
    name: "OTTO SCHARMER",
    role: "Senior Lecturer, MIT & Founding Chair, Presencing Institute",
    image: "/brand/Onlinespeaker-3.jpg",
    bio: "Otto Scharmer, a Senior Lecturer at MIT and Founding Chair of the Presencing Institute, has dedicated the past 20 years to helping leaders embrace cross-sector systems transformation.\n\nThrough his bestselling books Theory U and Presence (the latter co-authored with Peter Senge and others), Otto introduced the groundbreaking concept of \"presencing\" — learning from the emerging future. He also co-authored Leading from the Emerging Future, which outlines eight acupuncture points for transforming our economy from egocentric to ecocentric. His most recent book The Essentials of Theory U (2018) summarizes the core principles and applications of awareness-based systems change.\n\nHe co-founded the MITx u-lab, which has activated a vibrant worldwide ecosystem of transformational change involving more than 260,000 users from 194 countries. In collaboration with colleagues, he co-created global Action Learning Labs for UN agencies and SDG Leadership Labs for UN Country Teams in 26 countries, which support cross-sector initiatives for addressing urgent humanitarian crises."
  }
]);

// Modal state
const selectedSpeaker = ref(null);

function openSpeakerModal(speaker) {
  selectedSpeaker.value = speaker;
  if (typeof document !== 'undefined') {
    document.body.style.overflow = 'hidden';
  }
}

function closeSpeakerModal() {
  selectedSpeaker.value = null;
  if (typeof document !== 'undefined') {
    document.body.style.overflow = '';
  }
}

function onKeydown(e) {
  if (e.key === 'Escape' && selectedSpeaker.value) {
    closeSpeakerModal();
  }
}

// Lifecycle hooks
if (typeof window !== 'undefined') {
  window.addEventListener('keydown', onKeydown);
}
</script>

<template>
  <section id="speakers" class="scroll-mt-24 bg-[#F4F1EA] pt-8 pb-16 sm:pt-10 sm:pb-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="mb-12 text-center">
        <h2 class="mx-auto max-w-2xl font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
          ONLINE SPEAKERS
        </h2>
      </div>

      <!-- Speakers Grid - 3 speakers centered -->
      <div class="flex justify-center">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 max-w-5xl">
          <div
            v-for="speaker in speakers"
            :key="speaker.id"
            role="button"
            tabindex="0"
            class="group flex h-full cursor-pointer flex-col items-center justify-center rounded-none border border-primary-1/15 bg-primary-1/5 px-8 py-8 text-center shadow-md transition hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-accent-1"
            @click="openSpeakerModal(speaker)"
            @keydown.enter.prevent="openSpeakerModal(speaker)"
            @keydown.space.prevent="openSpeakerModal(speaker)"
          >
            <!-- Square photo, no orange ring -->
            <div class="relative mb-4">
              <div class="h-44 w-44 overflow-hidden rounded-full border border-primary-1/20 bg-primary-1/10 shadow-md">
                <img
                  :src="speaker.image"
                  :alt="speaker.name"
                  class="h-full w-full rounded-full object-cover"
                  loading="lazy"
                />
              </div>
            </div>

            <!-- Speaker Info -->
            <div class="w-full px-4">
              <h3 class="mx-auto max-w-xs font-title text-lg font-bold text-primary-1">
                {{ speaker.name }}
              </h3>
              <p class="mt-1 mx-auto w-full max-w-xs text-center font-body text-sm font-semibold text-primary-2">
                {{ speaker.role }}
              </p>
              <!-- <p class="mt-2 mx-auto max-w-xs text-center font-body text-sm leading-snug text-copy sm:text-base">
                {{ speaker.bio.split('\n\n')[0] }}
              </p> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Speaker detail popup -->
  <Teleport to="body">
    <div
      v-if="selectedSpeaker"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4"
      role="dialog"
      aria-modal="true"
      aria-labelledby="speaker-modal-title"
      @click.self="closeSpeakerModal"
    >
      <div
        class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-none border border-primary-1/20 bg-[#F4F1EA] shadow-2xl"
        @click.stop
      >
        <button
          type="button"
          class="absolute right-3 top-3 rounded-none border border-primary-1/30 bg-primary-1/10 p-2 text-primary-1 transition hover:bg-primary-1/20"
          aria-label="Close"
          @click="closeSpeakerModal"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <div v-if="selectedSpeaker.image" class="aspect-[4/3] w-full overflow-hidden bg-primary-1/10">
          <img
            :src="selectedSpeaker.image"
            :alt="selectedSpeaker.name"
            class="h-full w-full object-cover"
            loading="lazy"
          />
        </div>
        <div class="p-6 sm:p-8">
          <h3 id="speaker-modal-title" class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">
            {{ selectedSpeaker.name }}
          </h3>
          <p v-if="selectedSpeaker.role" class="mt-2 font-body text-base font-semibold text-primary-2">
            {{ selectedSpeaker.role }}
          </p>
          <p v-if="selectedSpeaker.bio" class="mt-4 whitespace-pre-line font-body text-base leading-relaxed text-ink">
            {{ selectedSpeaker.bio }}
          </p>
        </div>
      </div>
    </div>
  </Teleport>
</template>

