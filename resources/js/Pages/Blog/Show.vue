<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

defineProps({
    post: Object,
});
</script>

<template>
    <Head :title="post?.title ?? 'Blog Post'" />
    <EventLayout>
        <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
            <Link :href="route('blog.index')" class="inline-flex items-center gap-1 font-body text-sm text-accent-1 hover:underline">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Blog
            </Link>

            <header class="mt-6">
                <span v-if="post?.category" class="font-body text-xs font-medium uppercase tracking-wide text-accent-1">{{ post.category }}</span>
                <h1 class="mt-2 font-title text-3xl font-bold text-primary-1 sm:text-4xl">{{ post?.title }}</h1>
                <p class="mt-3 font-body text-sm text-gray-500">
                    {{ post?.author || 'GCFSS' }}
                    <span v-if="post?.published_at"> · {{ new Date(post.published_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                </p>
            </header>

            <div v-if="post?.image" class="mt-8 overflow-hidden rounded-xl">
                <img :src="post.image" :alt="post.title" class="w-full object-cover" />
            </div>

            <div v-if="post?.excerpt" class="mt-6 font-body text-lg text-copy">{{ post.excerpt }}</div>

            <div class="prose prose-lg mt-8 max-w-none font-body text-copy" v-html="(post?.content || '').replace(/\n/g, '<br>')"></div>
        </article>
    </EventLayout>
</template>
