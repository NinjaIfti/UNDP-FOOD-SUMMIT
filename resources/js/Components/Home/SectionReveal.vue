<script setup>
import { ref, onMounted } from 'vue';

defineProps({
    delay: { type: Number, default: 0 },
});

const el = ref(null);
const visible = ref(false);

onMounted(() => {
    const isMobile = window.innerWidth < 768;
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) visible.value = true;
        },
        { 
            threshold: isMobile ? 0.05 : 0.1, 
            rootMargin: isMobile ? '0px 0px -20px 0px' : '0px 0px -50px 0px' 
        }
    );
    if (el.value) observer.observe(el.value);
});
</script>

<template>
    <div
        ref="el"
        class="transition-all duration-700 ease-out"
        :class="[
            visible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0',
        ]"
        :style="{ transitionDelay: `${delay}ms` }"
    >
        <slot />
    </div>
</template>
