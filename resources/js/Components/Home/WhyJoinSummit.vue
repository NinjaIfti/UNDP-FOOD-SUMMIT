<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SectionReveal from './SectionReveal.vue';

const props = defineProps({
    settings: Object,
});

</script>
<section class="partners">
    
</section>
<template>
  <section class="scroll-mt-24 bg-[#F4F1EA] pt-8 pb-16 sm:pt-10 sm:pb-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- split card layout -->
      <div class="bg-white shadow-xl overflow-hidden">
        <div class="flex flex-col md:flex-row">
          
          <!-- LEFT: PHOTO SIDE - larger column -->
          <div class="md:w-1/2 flex flex-col bg-stone-100">
            <div class="relative overflow-hidden group h-64 md:h-auto md:min-h-[420px] flex-1">
              <img 
                src="/brand/whyjoinsummit.jpg" 
                alt="Listening to the Land Day 2026 - Oxford Real Farming Conference"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              >
            </div>
            <!-- photo credit - exact text -->
            <div class="bg-black/70 backdrop-blur-sm text-amber-50/90 py-1.5 px-4 text-center text-md font-medium border-t border-white/20">
              <i class="fas fa-camera mr-1 text-amber-300/80 text-xs"></i>
               <span class="credit-photo">Photo Credit:</span> Warwick, at Listening to the Land Day 2026 by Oxford Real Farming Conference (ORFC)
            </div>
          </div>
          
          <!-- RIGHT: TEXT SIDE -->
          <div class="md:w-1/2 p-6 sm:p-7 lg:p-8 bg-white flex flex-col">
            
            <!-- TITLE -->
            <h1 class="text-2xl uppercase sm:text-3xl text-primary-1 font-extrabold tracking-tight bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent mb-1.5">
              Why join the Summit?
            </h1>
            
            <!-- SUBTITLE -->
            <div class="border-l-4 border-green-500 pl-4 text-gray-800 text-lg sm:text-xl font-medium leading-tight mb-6 font-body">
              Become a conscious food systems leader, able to integrate this agenda into your work
            </div>
            
            <!-- BULLET POINTS - exact text only -->
            <ul class="space-y-4 font-body">
              <li class="flex gap-3 items-start">
                <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-gray-800 text-sm sm:text-base leading-relaxed">Nurture your inspiration and confidence to step up as a conscious food systems leader</span>
              </li>
              <li class="flex gap-3 items-start">
                <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-gray-800 text-sm sm:text-base leading-relaxed">Learn from existing examples how to integrate this agenda into your work</span>
              </li>
              <li class="flex gap-3 items-start">
                <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-gray-800 text-sm sm:text-base leading-relaxed">Connect with a global network and build collaborations</span>
              </li>
              <li class="flex gap-3 items-start">
                <svg class="w-8 h-8 text-accent-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-gray-800 text-sm sm:text-base leading-relaxed">Be part of this historical gathering in a unique setting</span>
              </li>
            </ul>
            
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style>
.credit-photo{
  color: #D8B74C;
}
</style>
<script>
export default {
  name: 'SummitSection'
}
</script>
