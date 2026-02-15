<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    posts: Object,
    stats: Object,
    blogEnabled: Boolean,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || 'all');

const settingsForm = useForm({
    blog_enabled: props.blogEnabled,
});

function applyFilters() {
    router.get(route('admin.blog.index'), {
        search: search.value || undefined,
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
    }, { preserveState: true });
}

function toggleBlogVisibility() {
    settingsForm.blog_enabled = !settingsForm.blog_enabled;
    settingsForm.post(route('admin.blog.settings'), { preserveScroll: true });
}

function deletePost(id) {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('admin.blog.destroy', id));
    }
}
</script>

<template>
    <Head title="Blog Management" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">Blog Management</h1>
                    <p class="mt-1 font-body text-sm text-copy">Create and manage blog posts for the GCFSS website</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="font-body text-sm text-ink">Show blog on website</span>
                        <button
                            type="button"
                            @click="toggleBlogVisibility"
                            :disabled="settingsForm.processing"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors focus:outline-none focus:ring-2 focus:ring-accent-1 focus:ring-offset-2"
                            :class="blogEnabled ? 'bg-primary-2' : 'bg-gray-200'"
                            role="switch"
                        >
                            <span
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition"
                                :class="blogEnabled ? 'translate-x-5' : 'translate-x-1'"
                            />
                        </button>
                    </div>
                    <Link
                        :href="route('admin.blog.create')"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2.5 font-body text-sm font-semibold text-white transition hover:bg-primary-2/90"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Blog Post
                    </Link>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-2/10">
                            <svg class="h-5 w-5 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-xs text-copy">Total Posts</p>
                            <p class="font-title text-xl font-bold text-primary-1">{{ stats?.total ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-xs text-copy">Published</p>
                            <p class="font-title text-xl font-bold text-primary-1">{{ stats?.published ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100">
                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-xs text-copy">Drafts</p>
                            <p class="font-title text-xl font-bold text-primary-1">{{ stats?.drafts ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-accent-1/10">
                            <svg class="h-5 w-5 text-accent-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-xs text-copy">Total Views</p>
                            <p class="font-title text-xl font-bold text-primary-1">{{ (stats?.views ?? 0).toLocaleString() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by title, author, or category..."
                        class="block w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        @keyup.enter="applyFilters"
                    />
                </div>
                <select
                    v-model="statusFilter"
                    @change="applyFilters"
                    class="rounded-lg border border-gray-300 px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                >
                    <option value="all">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
                <button
                    @click="applyFilters"
                    class="rounded-lg bg-primary-1 px-4 py-2 font-body text-sm font-medium text-white transition hover:bg-primary-1/90"
                >
                    Apply
                </button>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="post in posts?.data"
                        :key="post.id"
                        class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex min-w-0 flex-1 gap-4">
                            <img
                                v-if="post.image"
                                :src="post.image"
                                :alt="post.title"
                                class="h-16 w-24 shrink-0 rounded-lg object-cover"
                            />
                            <div class="min-w-0 flex-1">
                                <h3 class="font-title font-semibold text-primary-1 truncate">{{ post.title }}</h3>
                                <p class="mt-1 font-body text-sm text-copy">
                                    {{ post.author || 'No author' }} · {{ post.category || 'Uncategorized' }}
                                </p>
                                <span
                                    class="mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-medium"
                                    :class="post.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'"
                                >
                                    {{ post.status }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Link
                                :href="route('admin.blog.edit', post.id)"
                                class="rounded-lg border border-gray-300 px-3 py-2 font-body text-sm font-medium text-ink transition hover:bg-gray-50"
                            >
                                Edit
                            </Link>
                            <a
                                v-if="post.status === 'published'"
                                :href="`/blog/${post.slug}`"
                                target="_blank"
                                class="rounded-lg border border-primary-2 px-3 py-2 font-body text-sm font-medium text-primary-2 transition hover:bg-primary-2/5"
                            >
                                View
                            </a>
                            <button
                                @click="deletePost(post.id)"
                                class="rounded-lg border border-red-200 px-3 py-2 font-body text-sm font-medium text-red-600 transition hover:bg-red-50"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                    <div v-if="!posts?.data?.length" class="p-8 text-center">
                        <p class="font-body text-copy">No blog posts yet.</p>
                        <Link
                            :href="route('admin.blog.create')"
                            class="mt-2 inline-block font-body text-sm font-medium text-accent-1 hover:underline"
                        >
                            Create your first post
                        </Link>
                    </div>
                </div>
                <div v-if="posts?.links" class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="link in posts.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="rounded px-3 py-1 font-body text-sm"
                            :class="link.active ? 'bg-primary-1 text-white' : 'text-ink hover:bg-gray-100'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
