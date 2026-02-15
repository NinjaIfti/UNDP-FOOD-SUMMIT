<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EventLayout from '@/Layouts/EventLayout.vue';

defineProps({
    posts: Object,
});
</script>

<template>
    <Head title="Blog" />
    <EventLayout>
        <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
            <h1 class="font-title text-3xl font-bold text-primary-1 sm:text-4xl">Blog</h1>
            <p class="mt-2 font-body text-copy">Stories and updates from the Global Conscious Food Systems Summit</p>

            <div v-if="posts?.data?.length" class="mt-10 grid gap-8 sm:grid-cols-2">
                <Link
                    v-for="post in posts.data"
                    :key="post.id"
                    :href="route('blog.show', post.slug)"
                    class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
                >
                    <div v-if="post.image" class="aspect-[16/10] overflow-hidden">
                        <img
                            :src="post.image"
                            :alt="post.title"
                            class="h-full w-full object-cover transition group-hover:scale-105"
                        />
                    </div>
                    <div v-else class="flex aspect-[16/10] items-center justify-center bg-gray-100">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="p-5">
                        <span v-if="post.category" class="font-body text-xs font-medium uppercase tracking-wide text-accent-1">{{ post.category }}</span>
                        <h2 class="mt-2 font-title text-lg font-semibold text-primary-1 group-hover:text-accent-1">{{ post.title }}</h2>
                        <p v-if="post.excerpt" class="mt-2 line-clamp-2 font-body text-sm text-copy">{{ post.excerpt }}</p>
                        <p class="mt-3 font-body text-xs text-gray-500">{{ post.author || 'GCFSS' }} · {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '' }}</p>
                    </div>
                </Link>
            </div>

            <div v-else class="mt-16 rounded-xl border border-gray-200 bg-white p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 font-body text-copy">No blog posts yet. Check back soon.</p>
            </div>

            <div v-if="posts?.links?.length" class="mt-10 flex flex-wrap justify-center gap-2">
                <Link
                    v-for="link in posts.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="rounded-lg px-4 py-2 font-body text-sm font-medium transition"
                    :class="link.active ? 'bg-primary-2 text-white' : 'border border-gray-300 text-ink hover:bg-gray-50'"
                    v-html="link.label"
                />
            </div>
        </div>
    </EventLayout>
</template>
