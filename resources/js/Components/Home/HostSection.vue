<script setup>
import { ref } from 'vue';

const props = defineProps({
  settings: Object,
});

// Hosts data
const hosts = ref([
  {
    id: 1,
    name: "JO CONFINO",
    role: "Leadership coach, Spiritual mentor, Podcaster, Author & Journalist",
    image: "/brand/Host-2.jpeg",
    bio: "He works at the intersection of personal transformation and systems change; working with several organisations including Leaders' Quest and Gobal Optimism. His coaching and spiritual mentoring practice focuses on supporting leaders within the fields of climate, biodiversity and social justice. He works across sectors, including NGOs, foundations and business and coaches individuals as well as leadership teams within organisations.\n\nJo has worked closely for the past 18 years with Zen Master Thich Nhat Hanh and his buddhist monastic community in Plum Village.\n\nHe is co-founder and co-presenter of the acclaimed podcast series 'The Way Out Is In' and is the co-author, with the Plum Village abbot, of two Zen books 'Being With Busyness' and 'Calm in the Storm.' In 2026 he also published a photograhy and travelogue book 'Between Earth and Sky: 100 Days of Deep Looking in the Place of the Dead.'\n\nBesides facilitating events and conferences all over the world for the past 25 years, he also runs smaller workshops and roundtables.\n\nAs a journalist for more than 40 years, he was executive editor, Impact & Innovation and Editorial Director of What's Working at the HuffPost in New York. During his five years there, he developed long-term editorial projects based on social, environmental and economic justice and was also a member of the senior leadership team of Verizon Media.\n\nHe is a fellow of the Royal Society for the encouragement of Arts, Manufactures and Commerce and completed an MSc in Responsibility and Business Practice at the University of Bath."
  },
  {
    id: 2,
    name: "PEMA CHODEN TENZIN",
    role: "Chief Editor, Yeewong Magazine Bhutan & Creator, ZHIMMEY: Bhutan's Ultimate Food Guide",
    image: "/brand/Host-1.png",
    bio: "Pema Choden Tenzin is Bhutanese travel vlogger and Creator of Bhutan's only Women & Lifestyle Magazine which started in 2009. Online version: www.yeewongmagazine.com - now covering major fashion, entertainment and lifestyle features in Bhutan on print, online and video.\n\nAlso created www.zhimmey.com Bhutan's ultimate restaurant listing and food guide."
  }
]);

// Modal state
const selectedHost = ref(null);

function openHostModal(host) {
  selectedHost.value = host;
  if (typeof document !== 'undefined') {
    document.body.style.overflow = 'hidden';
  }
}

function closeHostModal() {
  selectedHost.value = null;
  if (typeof document !== 'undefined') {
    document.body.style.overflow = '';
  }
}

function onKeydown(e) {
  if (e.key === 'Escape' && selectedHost.value) {
    closeHostModal();
  }
}

// Lifecycle hooks
if (typeof window !== 'undefined') {
  window.addEventListener('keydown', onKeydown);
}
</script>

<template>
  <section id="hosts" class="scroll-mt-24 bg-[#F4F1EA] pt-8 pb-16 sm:pt-10 sm:pb-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="mb-12 text-center">
        <h2 class="mx-auto max-w-2xl font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
          MC / HOSTS
        </h2>
      <!--  <p class="mx-auto mt-4 max-w-2xl text-center font-body text-base leading-relaxed text-copy sm:text-lg">
          Meet the inspiring voices who will guide and facilitate our Summit experience
        </p> -->
      </div>

      <!-- Hosts Grid - 2 hosts centered -->
      <div class="flex justify-center">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 max-w-4xl">
          <div
            v-for="host in hosts"
            :key="host.id"
            role="button"
            tabindex="0"
            class="group flex h-full cursor-pointer flex-col items-center justify-center rounded-none border border-primary-1/15 bg-primary-1/5 px-8 py-8 text-center shadow-md transition hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-accent-1"
            @click="openHostModal(host)"
            @keydown.enter.prevent="openHostModal(host)"
            @keydown.space.prevent="openHostModal(host)"
          >
            <!-- Square photo, no orange ring -->
            <div class="relative mb-4">
              <div class="h-44 w-44 overflow-hidden rounded-full border border-primary-1/20 bg-primary-1/10 shadow-md">
                <img
                  :src="host.image"
                  :alt="host.name"
                  class="h-full w-full rounded-full object-cover"
                  loading="lazy"
                />
              </div>
            </div>

            <!-- Host Info -->
            <div class="w-full px-4">
              <h3 class="mx-auto max-w-xs font-title text-lg font-bold text-primary-1">
                {{ host.name }}
              </h3>
              <p class="mt-1 mx-auto w-full max-w-xs text-center font-body text-sm font-semibold text-primary-2">
                {{ host.role }}
              </p>
              <!-- <p class="mt-2 mx-auto max-w-xs text-center font-body text-sm leading-snug text-copy sm:text-base">
                {{ host.bio.split('\n\n')[0] }}
              </p> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Host detail popup -->
  <Teleport to="body">
    <div
      v-if="selectedHost"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4"
      role="dialog"
      aria-modal="true"
      aria-labelledby="host-modal-title"
      @click.self="closeHostModal"
    >
      <div
        class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-none border border-primary-1/20 bg-[#F4F1EA] shadow-2xl"
        @click.stop
      >
        <button
          type="button"
          class="absolute right-3 top-3 z-10 rounded-none border border-primary-1/30 bg-primary-1/10 p-2 text-primary-1 transition hover:bg-primary-1/20"
          aria-label="Close"
          @click="closeHostModal"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <div class="flex flex-col sm:flex-row p-6 sm:p-8 gap-6">
          <div class="flex-shrink-0 flex justify-center">
            <div class="h-32 w-32 sm:h-40 sm:w-40 overflow-hidden rounded-full border border-primary-1/20 bg-primary-1/10 shadow-md">
              <img
                :src="selectedHost.image"
                :alt="selectedHost.name"
                class="h-full w-full rounded-full object-cover"
                loading="lazy"
              />
            </div>
          </div>
          <div class="flex-1">
            <h3 id="host-modal-title" class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">
              {{ selectedHost.name }}
            </h3>
            <p v-if="selectedHost.role" class="mt-2 font-body text-base font-semibold text-primary-2 text-left">
              {{ selectedHost.role }}
            </p>
          </div>
        </div>
        <div class="px-6 pb-6 sm:px-8 sm:pb-8">
          <p v-if="selectedHost.bio" class="whitespace-pre-line font-body text-base leading-relaxed text-ink">
            {{ selectedHost.bio }}
          </p>
        </div>
      </div>
    </div>
  </Teleport>
</template>
