<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';
import SectionReveal from '@/Components/Home/SectionReveal.vue';

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
});

const heroTitle = computed(() => props.settings?.hero_title ?? 'Call for Submissions');
const callSubmissionsTitle = computed(() => props.settings?.call_submissions_title ?? 'Call for Submissions');
const heroSubtitle = computed(() => props.settings?.hero_subtitle ?? 'We warmly invite you to co-create the agenda of the first-ever Global Conscious Food Systems Summit, taking place in Bhutan from 31 August – 4 September 2026.');
const heroIntro = computed(() => props.settings?.hero_intro ?? 'CoFSA invites submissions from individuals, organizations and governments wishing to actively contribute to the Bhutan Summit 2026 programme by sharing experiences, ideas, practices, and approaches that demonstrate or explore conscious food systems in practice.');

const heroSlides = computed(() => {
    const base = props.settings?.hero_image || '/brand/img34.jpg';
    const extras = Array.isArray(props.settings?.hero_slides)
        ? props.settings.hero_slides.filter(src => src && typeof src === 'string' && src.trim().length > 0)
        : [];
    const all = [base, ...extras];
    return all.filter((src, index) => all.indexOf(src) === index);
});

const activeHeroSlide = ref(0);

let heroInterval;

onMounted(() => {
    heroInterval = setInterval(() => {
        if (heroSlides.value.length > 1) {
            activeHeroSlide.value = (activeHeroSlide.value + 1) % heroSlides.value.length;
        }
    }, 7000);
});

onUnmounted(() => {
    if (heroInterval) {
        clearInterval(heroInterval);
    }
});

const ctaParagraph1 = computed(() => props.settings?.cta_paragraph1 ?? 'If you feel inspired to contribute to the Bhutan Summit 2026 and share your experiences, practices, or ideas for conscious food systems, we warmly invite you to submit a proposal.');
const ctaParagraph2 = computed(() => props.settings?.cta_paragraph2 ?? 'All submissions should be experiential and participatory, integrating consciousness-based practices, storytelling, dialogue, or creative methods. You can apply as an individual or organization, and you are welcome to submit multiple proposals on distinct topics.');
const ctaParagraph3 = computed(() => props.settings?.cta_paragraph3 ?? 'Submit your proposal now to be part of this global conversation and help shape a new paradigm for food systems transformation.');
const engagementTitle = computed(() => props.settings?.engagement_title ?? 'Activating the Heart');
const engagementBody = computed(() => props.settings?.engagement_body ?? '');
const visioningTitle = computed(() => props.settings?.visioning_title ?? 'Deep Visioning');
const visioningBody = computed(() => props.settings?.visioning_body ?? '');
const ctaCards = computed(() => {
    const list = props.settings?.cta_cards;
    if (Array.isArray(list) && list.length) return list.filter(c => c && c.href);
    return [
        { href: 'https://airtable.com/appRiKywRTWHpim3e/pagi2lYYhFAAhlqhE/form', title: 'Submit Your Application', subtitle: 'Complete the application form by 31st March', style: 'primary' },
        { href: 'https://consciousfoodsystems.org/wp-content/uploads/2026/02/CoFSA_Bhutan_Call-for-Submissions.pdf', title: 'Full Submission Guidelines', subtitle: 'View details, eligibility criteria and guidelines', style: 'secondary' },
    ];
});

const spreadTitle = computed(() => props.settings?.spread_title || 'Spread the word!');
const spreadBody = computed(() => props.settings?.spread_body || 'PENDING: Communication Toolkit will be provided by COFSA soon.');
const spreadPending = computed(() => props.settings?.spread_pending || '');
const spreadPdfUrl = computed(() => props.settings?.spread_pdf_url || '');
const spreadTrelloUrl = computed(() => props.settings?.spread_trello_url || '');

const spreadBodyParagraphs = computed(() => {
    const raw = spreadBody.value || '';
    return String(raw)
        .split(/\r?\n|\\n/)
        .map(p => p.trim())
        .filter(Boolean);
});

const partnersTitle = computed(() => props.settings?.partners_title || 'Together with our partners');
const partnersBody = computed(() => props.settings?.partners_body || 'This Summit is made possible through strong partnerships');
const partnersPending = computed(() => props.settings?.partners_pending || 'PENDING: COFSA WILL GIVE YOU ALL THE PARTNER LOGOS');
const partnerLogos = computed(() => (Array.isArray(props.settings?.partners_logos) ? props.settings.partners_logos.filter(v => v && v.trim()) : []));

const financialTitle = computed(() => props.settings?.financial_title || 'Financial support to expand Summit impact');
const financialBody = computed(() => props.settings?.financial_body || '');
</script>

<template>
    <Head title="Get Involved" />
    <EventLayout>
        <!-- 1) Call for Submissions -->
        <section class="mb-10 relative bg-[#F4F1EA] py-0">
            <div class="w-full">
                <div class="overflow-hidden rounded-none">
                    <div class="relative w-full min-h-[28vh] sm:min-h-[32vh] lg:min-h-[36vh]">
                        <div
                            v-for="(slide, index) in heroSlides"
                            :key="slide + index"
                            class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                            :class="index === activeHeroSlide ? 'opacity-100' : 'opacity-0'"
                        >
                            <img
                                :src="slide"
                                alt=""
                                class="h-full w-full object-cover"
                                loading="lazy"
                            />
                        </div>
                        <div class="absolute inset-0 bg-black/45" />
                        <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-8">
                            <div class="max-w-4xl text-center">
                                <h1 class="font-title text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl lg:text-5xl">
                                    {{ heroTitle }}
                                </h1>
                                <p class="mx-auto mt-4 text-center font-body text-lg font-semibold leading-relaxed text-white sm:text-xl">
                                    {{ heroSubtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	<!-- ③ Building a Global Movement -->
    <section id="global-movement" class="mt-15 scroll-mt-24 bg-[#F4F1EA] pt-4 pb-10 sm:pt-6 sm:pb-14 lg:pt-8 lg:pb-18">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <SectionReveal>
          

          <!-- Two-column: text left, photo right -->
          <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
		
            <!-- Text -->

            <div>
<h2 class="mb-5 font-title text-3xl font-bold uppercase tracking-wide text-primary-1 sm:text-4xl">
                SUMMIT LIVESTREAMS & REGIONAL ONLINE GATHERINGS
            </h2>
              <p class="font-body text-base leading-relaxed text-ink sm:text-lg">
                Follow the Summit livestreams and recordings on our <span style="color:#F69C0E;">CoFSA YouTube channel.</span> Additionally, in partnership with Alef Trust, we will host daily online gatherings across 5 regions: Asia, Africa, Europe, North America, and Latin America, for people around the world to connect, reflect, and engage meaningfully with the conversations taking place in Bhutan.



              </p>
              <div class="flex flex-wrap items-center gap-3 mb-4">
                <a href="https://consciousfoodsummit.com/register-summit"
                  class="mt-3 font-body rounded-full shadow-md bg-accent-2 px-4 py-3 text-center text-base font-semibold text-white transition hover:bg-accent-2/90 inline-flex items-center justify-center gap-2">
                  Register Now
                </a>
                <!-- <a href="https://youtu.be/rW2II8jEOnk?si=6aVCDEEbwvfWwAxi"
                  class="mt-3 inline-block bg-transparent text-[#1f4a3b] font-semibold px-4 py-3 rounded-full border-2 border-[#F59C0E] hover:bg-[#1f4a3b] hover:text-white transition duration-200 ease-in-out text-sm">
                  Promo video
                </a> -->
              </div>

            </div>

            <!-- Photo -->
            <div>
              <img
                src="https://th.bing.com/th/id/R.6c8964b7fd95ce2da2eea9133ad1730b?rik=Nidj742rqlWN2g&riu=http%3a%2f%2frunawayjuno.com%2fwp-content%2fuploads%2f2013%2f08%2fBhutan-Paro-Tigers-Nest-Monastery.jpg&ehk=NcgAwRjdOo%2b%2bI%2bQ83jAuIVGrviYFiQ1wjnKfs6%2fEWeA%3d&risl=&pid=ImgRaw&r=0"
                alt="Landscape photography of a river and mountains"
                class="w-100 object-cover rounded-lg shadow-sm mb-4">
            </div>
          </div>
        </SectionReveal>
      </div>
    </section>
        <!-- new section below the banner-->
    <section class="bg-[#F4F1EA] pt-8 pb-10 sm:pt-10 sm:pb-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <SectionReveal>
          <!-- new -->


          <!-- The four Box-->
          <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-1">


            <div class="rounded-none border bg-white/60 p-6 shadow-sm sm:p-8">
              <h1 class="text-2xl md:text-3xl font-bold text-[#1f4a3b] mb-2">
                Activating the Heart for Food Systems Transformation
              </h1>

              <p class="text-[#2d3e35] text-base md:text-lg leading-relaxed mb-4">
                What are our hearts longing for when it comes to food systems? Begin with our guided meditation, then
                reflect on questions of gratitude, heartbreak, aspiration, and commitment. Share your offering—through
                stories, reflections, poems, artworks, music, videos, or other creative expressions—on our Padlet
                platform. Selected contributions will be showcased through our official channels and woven into a
                collective artistic expression that will accompany the Summit.
              </p>

              <!-- ACTION BUTTONS -->
              <div class="flex flex-wrap items-center gap-4 border-t border-[#c49a30] pt-5 mt-2">
                <a href="https://www.youtube.com/watch?v=SRu5qIdtkWY"
                  class="mt-3 font-body rounded-full shadow-md bg-accent-2 px-4 py-3 text-center text-base font-semibold text-white transition hover:bg-accent-2/90 inline-flex items-center justify-center gap-2">
                  → Listen to the guided meditation
                </a>
                <a href="https://consciousfoodsystems.org/wp-content/uploads/2026/06/CoFSA_Activating_the_Heart_v2_3_CLICKABLE.pdf"
                  class="mt-3 font-body rounded-full shadow-md bg-accent-2 px-4 py-3 text-center text-base font-semibold text-white transition hover:bg-accent-2/90 inline-flex items-center justify-center gap-2">
                  → Read the infographic and contribute your offering
                </a>
              </div>

              <!-- YOUTUBE DESCRIPTION SECTION -->
              <div class="mt-6 bg-white/60 backdrop-blur-sm p-5 md:p-6 rounded-xl border border-white/40">
                <span
                  class="inline-block bg-[#cc0000] text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-2">
                  🎬 YouTube
                </span>
                <h2 class="text-xl font-semibold text-[#1f4a3b] mb-1">
                  Meditation Video Description
                </h2>
                <p class="text-[#2d3e35] leading-relaxed mb-3">

                  Created as part of the <strong>Activating the Heart for Food Systems Transformation</strong> journey
                  accompanying the Global Conscious Food Systems Summit in Bhutan, this meditation invites you to pause,
                  reconnect, and listen deeply to what your heart is calling you to cultivate within yourself and within
                  the food systems shaping our world.
                </p>
                <p class="text-[#2d3e35] leading-relaxed mb-3">
                  Through grounding, reflection, and imagination, you are invited to explore questions of gratitude,
                  grief, responsibility, regeneration, and collective care. <em>What do you cherish? What breaks your
                    heart? What future are you being called to help create?</em>
                </p>
                <p class="text-[#2d3e35] leading-relaxed mb-4">
                  We invite you to carry the harvest of your reflections beyond this practice by sharing your stories,
                  poems, artworks, music, reflections, or other creative expressions as part of this global journey.
                  Selected contributions will accompany the Summit as part of a collective artistic expression.
                </p>

                <!-- YOUTUBE BUTTONS -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                  <a href="#"
                    class="mt-3 font-body rounded-full shadow-md bg-accent-2 px-4 py-3 text-center text-base font-semibold text-white transition hover:bg-accent-2/90 inline-flex items-center justify-center gap-2">
                    📄 Read the infographic
                  </a>
                  <a href="#"
                    class="inline-block bg-transparent text-[#1f4a3b] font-semibold px-5 py-2.5 rounded-full border-2 border-[#F59C0E] hover:bg-[#1f4a3b] hover:text-white transition duration-200 ease-in-out text-sm">
                    📌 Contribute on Padlet
                  </a>
                </div>

                <div class="border-t pt-4 mt-2 text-[#3d4f46] italic leading-relaxed">
                  May this practice nourish your spirit and strengthen the seeds of transformation already growing
                  within you.<br>
                  With gratitude,<br>
                  <strong class="not-italic text-[#1f4a3b]">Conscious Food Systems Alliance (CoFSA)</strong>
                </div>

                <div class="text-sm text-[#4d5f55] mt-3">
                  <em>Find out below how you can share the harvest of your reflections and contribute to the Summit
                    journey.</em>
                  <a href="#" class="text-[#1f4a3b] font-medium underline underline-offset-2 hover:text-[#2d6b4f]"><a
                      href="https://consciousfoodsystems.org/wp-content/uploads/2026/06/CoFSA_Activating_the_Heart_v2_3_CLICKABLE.pdf">infographic</a></a>
                </div>
              </div>



            </div>


            <div
              class="gap-4 mx-auto max-w-6xl gap-6 lg:grid-cols-1 mt-10 rounded-none border bg-white/60 p-6 shadow-sm sm:p-8">
              <h2 class="text-2xl md:text-3xl font-bold text-[#1f4a3b] mb-2">
                <!-- {{ engagementTitle }} -->
                Join Online to the Global Conscious Food Systems Summit​
              </h2>
              <p class="text-[#2d3e35] text-base md:text-lg leading-relaxed mb-4">
                <!-- {{ visioningBody || ctaParagraph3 }} -->
                “Livestreams and recordings will be available for everyone to follow the summit on our <a
                  style="color: blue;"
                  href="https://eur03.safelinks.protection.outlook.com/?url=https%3A%2F%2Fwww.youtube.com%2F%40consciousfoodsystemsallian3322&data=05%7C02%7Creynir.ragnarsson%40undp.org%7C3d5772ab8d8b430844fe08dee3c843f6%7Cb3e5db5e2944483799f57488ace54319%7C0%7C0%7C639198646451158131%7CUnknown%7CTWFpbGZsb3d8eyJFbXB0eU1hcGkiOnRydWUsIlYiOiIwLjAuMDAwMCIsIlAiOiJXaW4zMiIsIkFOIjoiTWFpbCIsIldUIjoyfQ%3D%3D%7C0%7C%7C%7C&sdata=muYgcx2jPAhlh9VQKgD4KDihKX5fhNFjSBrUWVg%2BXvE%3D&reserved=0">Official
                  CoFSA YouTube Channel.</a> To broaden participation, everyday we will host online gatherings in five
                regions to connect and reflect on the Summit’s daily themes, and contribute to the Summit’s
                conversations. <a style="color: blue;"
                  href="https://consciousfoodsystems.org/online-participation/">Check the Online Summit Agenda here"</a>


              </p>
            </div>
            <div
              class="gap-4 mx-auto max-w-6xl gap-6 lg:grid-cols-1 mt-10 rounded-none border bg-white/60 p-6 shadow-sm sm:p-8">
              <h2 class="text-2xl md:text-3xl font-bold text-[#1f4a3b] mb-2">
                <!-- {{ engagementTitle }} -->
                Organize your own event on conscious food
              </h2>
              <p class="text-[#2d3e35] text-base md:text-lg leading-relaxed mb-4">
                <!-- {{ visioningBody || ctaParagraph3 }} -->
                “We warmly invite you to host an online gathering exploring the cultural, relational, and inner
                dimensions of food systems transformation. If you are organising an event, you can submit it through a
                <a style="color: blue;"
                  href="https://eur03.safelinks.protection.outlook.com/?url=https%3A%2F%2Fconsciousfoodsystems.org%2Fadd-event%2F&data=05%7C02%7Creynir.ragnarsson%40undp.org%7C3d5772ab8d8b430844fe08dee3c843f6%7Cb3e5db5e2944483799f57488ace54319%7C0%7C0%7C639198646451202516%7CUnknown%7CTWFpbGZsb3d8eyJFbXB0eU1hcGkiOnRydWUsIlYiOiIwLjAuMDAwMCIsIlAiOiJXaW4zMiIsIkFOIjoiTWFpbCIsIldUIjoyfQ%3D%3D%7C0%7C%7C%7C&sdata=a0RkBSsFusiuuiVUfzvYuqHn8Wx0bdWhDHWH9w%2BhOUk%3D&reserved=0">form</a>,
                in order to be featured on the <a style="color: blue;"
                  href="https://eur03.safelinks.protection.outlook.com/?url=https%3A%2F%2Fconsciousfoodsystems.org%2Fevent%2F&data=05%7C02%7Creynir.ragnarsson%40undp.org%7C3d5772ab8d8b430844fe08dee3c843f6%7Cb3e5db5e2944483799f57488ace54319%7C0%7C0%7C639198646451227353%7CUnknown%7CTWFpbGZsb3d8eyJFbXB0eU1hcGkiOnRydWUsIlYiOiIwLjAuMDAwMCIsIlAiOiJXaW4zMiIsIkFOIjoiTWFpbCIsIldUIjoyfQ%3D%3D%7C0%7C%7C%7C&sdata=Peuq02AKOu%2FgQFcJMBvSut0QSwDOyZ91zpe4%2FE1M63A%3D&reserved=0">official
                  CoFSA Website </a> , where it will be visible to the wider CoFSA community and beyond.<br>
                <a style="color: blue;"
                  href="https://eur03.safelinks.protection.outlook.com/?url=https%3A%2F%2Fconsciousfoodsystems.org%2Fevent%2F&data=05%7C02%7Creynir.ragnarsson%40undp.org%7C3d5772ab8d8b430844fe08dee3c843f6%7Cb3e5db5e2944483799f57488ace54319%7C0%7C0%7C639198646451250715%7CUnknown%7CTWFpbGZsb3d8eyJFbXB0eU1hcGkiOnRydWUsIlYiOiIwLjAuMDAwMCIsIlAiOiJXaW4zMiIsIkFOIjoiTWFpbCIsIldUIjoyfQ%3D%3D%7C0%7C%7C%7C&sdata=N9zzsDKxfL18uuIr4ethlpuqSBG4SlQ%2F48Hqdjb86pQ%3D&reserved=0">Events
                  from April 22, 2022 – May 21, 2024 – CoFSA”</a>



              </p>
            </div>

          </div>





        </SectionReveal>

      </div>
    </section>

        <!-- 3) Together with our partners -->
      <!--  <section class="bg-[#F4F1EA] py-8 sm:py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl text-center">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                            {{ partnersTitle }}
                        </h2>
                        <p class="mx-auto mt-3 max-w-3xl text-center font-body text-base leading-relaxed text-copy sm:text-lg">
                            {{ partnersBody }}
                        </p>
                        <p v-if="partnersPending" class="mx-auto mt-3 max-w-3xl text-center font-body text-base font-semibold text-primary-1">
                            {{ partnersPending }}
                        </p>
                    </div>

                    <div v-if="partnerLogos.length" class="mx-auto mt-8 max-w-6xl">
                        <div class="flex gap-3 overflow-x-auto pb-2 justify-center">
                            <div
                                v-for="(logo, index) in partnerLogos"
                                :key="logo + index"
                                class="flex h-16 w-24 shrink-0 items-center justify-center rounded-none border border-primary-1/10 bg-white p-2 shadow-sm"
                            >
                                <img :src="logo" alt="Partner logo" class="h-full w-full object-contain" loading="lazy" />
                            </div>
                        </div>
                    </div>
                </SectionReveal>
            </div>
        </section> -->
	<!-- new added -->
<section class="relative overflow-hidden border-b border-primary-1/20 bg-[#F4F1EA] pt-8 pb-16 sm:pt-10 sm:pb-20 lg:pt-12 lg:pb-24">
  <div class="relative mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Main Title -->
    <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
      Our Funding Partners
    </h2>

    <!-- Plant Sponsors Section -->
    <div class="mt-12">
      <h3 class="font-title text-center text-xl font-semibold uppercase tracking-wide text-primary-2 sm:text-2xl">
        Plant Sponsors
      </h3>
      
      <!-- Plant Sponsors Logo Marquee -->
      <div class="mt-8 overflow-hidden">
        <div class="marquee-container">
          <div class="marquee-track-smalll">
            <div class="marquee-content-static">
              <div class="logo-item">
                <img src="/brand/plant-logo-1.png" alt="Plant Sponsor 1" class="logo-image">
              </div>
              <div class="logo-item-plant2">
                <img src="/brand/plant-logo-2.png" alt="Plant Sponsor 2" class="logo-image">
              </div>
		<div class="logo-item">
                    <img src="/brand/macarthur.png" alt="Plant Sponsor 2" class="logo-image">
                  </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Soil Sponsors Section -->
    <div class="mt-16">
      <h3 class="font-title text-center text-xl font-semibold uppercase tracking-wide text-primary-2 sm:text-2xl">
        Soil Sponsors
      </h3>
      
      <!-- Soil Sponsors Logo Marquee -->
      <div class="mt-8 overflow-hidden">
  <div class="marquee-container flex justify-center items-center">
    <div class="marquee-track-smalll flex justify-center items-center w-full">
      <div class="marquee-content-static flex justify-center items-center">
        <div class="logo-item flex justify-center items-center">
          <img src="/brand/soil-logo-1.png" alt="Soil Sponsor 1" class="logo-image">
        </div>
	<div class="logo-item">
                    <img src="/brand/pawanka-fund-logo.png" alt="Soil Sponsor 1" class="logo-image">
                  </div>
	<div class="logo-item">
                    <img src="/brand/BESS-family.png" alt="Soil Sponsor 1" class="logo-image">
                  </div>
      </div>
    </div>
  </div>
</div>
</div>
        <!-- Global Advisory Partners Section - Same font and size as Our Funding Partners -->
        <div class="mt-16">
          <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
            Our Global Engagement Partners

          </h2>

          <!-- Global Advisory Partners Logo Marquee -->
          <div class="mt-8 overflow-hidden">
            <div class="marquee-container">
              <div class="marquee-track">
                <div class="marquee-content">
                  <div class="logo-item">
                    <img src="/brand/1.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/2.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/3.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/4.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/5.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/6.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/7.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/8.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/9.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/10.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/11.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/12.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/13.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/14.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/15.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/16.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/17.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/18.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/19.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/20.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/21.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/22.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/23.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/24.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/25.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/26.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/27.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/28.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/29.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  
                </div>
                <div class="marquee-content">
                  <div class="logo-item">
                    <img src="/brand/1.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/2.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/3.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/4.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/5.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/6.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/7.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/8.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/9.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/10.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/11.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/12.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/13.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/14.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/15.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/16.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/17.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/18.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/19.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/20.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/21.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/22.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/23.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/24.png" alt="Global Advisory Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/25.png" alt="Global Advisory Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/26.png" alt="Global Advisory Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/27.png" alt="Global Advisory Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/28.png" alt="Global Advisory Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/29.png" alt="Global Advisory Partner 5" class="logo-image">
                  </div>
                  

                </div>
              </div>
            </div>
          </div>
        </div>
</div>

    <!-- Global Advisory Partners Section - Same font and size as Our Funding Partners -->
        <div class="mt-16">
          <h2 class="font-title text-center text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
            Our Global Advisory Partners
          </h2>

          <!-- Global Engagement Partners Logo Marquee -->
          <div class="mt-8 overflow-hidden">
            <div class="marquee-container">
              <div class="marquee-track">
                <div class="marquee-content">
                  <div class="logo-item">
                    <img src="/brand/g1.png" alt="Global Engagement Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g2.png" alt="Global Engagement Partner 2"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g3.png" alt="Global Engagement Partner 3"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g4.png" alt="Global Engagement Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g5.png" alt="Global Engagement Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g6.png" alt="Global Engagement Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g7.png" alt="Global Engagement Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g8.png" alt="Global Engagement Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g9.png" alt="Global Engagement Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g10.png" alt="Global Engagement Partner 4"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g11.png" alt="Global Engagement Partner 5"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g12.png" alt="Global Engagement Partner 6"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g13.png" alt="Global Engagement Partner 1"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g14.png" alt="Global Engagement Partner 2"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g15.png" alt="Global Engagement Partner 3"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g16.png" alt="Global Engagement Partner 4"
                      class="logo-image">
                  </div>
                  
                </div>
                <div class="marquee-content">
                  <div class="logo-item">
                    <img src="/brand/g1.png" alt="Global Engagement Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g2.png" alt="Global Engagement Partner 2"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g3.png" alt="Global Engagement Partner 3"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g4.png" alt="Global Engagement Partner 4" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g5.png" alt="Global Engagement Partner 5" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g6.png" alt="Global Engagement Partner 6" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g7.png" alt="Global Engagement Partner 1" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g8.png" alt="Global Engagement Partner 2" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g9.png" alt="Global Engagement Partner 3" class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g10.png" alt="Global Engagement Partner 4"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g11.png" alt="Global Engagement Partner 5"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g12.png" alt="Global Engagement Partner 6"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g13.png" alt="Global Engagement Partner 1"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g14.png" alt="Global Engagement Partner 2"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g15.png" alt="Global Engagement Partner 3"
                      class="logo-image">
                  </div>
                  <div class="logo-item">
                    <img src="/brand/g16.png" alt="Global Engagement Partner 4"
                      class="logo-image">
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      
    
</section>

 <!-- end -->	
        <!-- 4) Financial support -->
        <section class="bg-[#F4F1EA] py-8 sm:py-10 lg:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <SectionReveal>
                    <div class="mx-auto max-w-4xl rounded-none border border-primary-1/10 bg-white p-6 shadow-sm sm:p-8 text-left">
                        <h2 class="font-title text-2xl font-bold uppercase tracking-wide text-primary-1 sm:text-3xl">
                            {{ financialTitle }}
                        </h2>
                        <div class="mt-4 space-y-4">
                            <p
                                v-for="(para, index) in (financialBody || '').split('\\n').map(p => p.trim()).filter(Boolean)"
                            :key="index"
                                class="text-left font-body text-base leading-relaxed text-copy sm:text-lg"
                            >
                                {{ para }}
                            </p>
                            </div>
                    </div>
                </SectionReveal>
            </div>
        </section>
    </EventLayout>
</template>

<style>
/* Font imports */





/* .text-primary-1 {
  color: #2c5f2d;
}

.text-primary-2 {
  color: #5a8f4c;
} */

/* Marquee Animation - Right to Left */
.marquee-container {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.marquee-track {
  display: flex;
  animation: scrollRightToLeft 85s linear infinite;
  width: fit-content;
}

/* for small */
.marquee-track-small {
  display: flex;
  animation: scrollRightToLeft 45s linear infinite;
  width: fit-content;
}

.marquee-content {
  display: flex;
  align-items: center;
  justify-content: center;
 
  flex-shrink: 0;
  padding: 0 0;
}

.marquee-content-static {
  display: flex;
  align-items: center;
  justify-content: center;
 
  flex-shrink: 0;
  padding: 0 0;
}

.logo-item {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 280px;
  height: 140px;
  margin: 0;
}

.logo-image {
  max-width: 100%;
  max-height: 100%;
  width: auto;
  height: auto;
  object-fit: contain;
  transition: transform 0.2s ease;
  opacity: 0.85;
}

.logo-item:hover .logo-image {
  transform: scale(1.05);
  opacity: 1;
}

@keyframes scrollRightToLeft {
  0% {
    transform: translateX(0);
  }

  100% {
    transform: translateX(-50%);
  }
}

/* Pause animation on hover */
.marquee-container:hover .marquee-track {
  animation-play-state: paused;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .logo-item {
    width: 210px;
    height: 105px;
  }

  .marquee-content {
    gap: 3rem;
  }
}

@media (max-width: 768px) {
  .logo-item {
    width: 170px;
    height: 85px;
  }

  .marquee-content {
    gap: 2.5rem;
  }
}

@media (max-width: 640px) {
  .logo-item {
    width: 150px;
    height: 75px;
  }

  .marquee-content {
    gap: 2rem;
  }
}
</style>
