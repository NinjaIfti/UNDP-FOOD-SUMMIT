<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
});

const isEdit = !!props.post;

const form = useForm({
    title: props.post?.title ?? '',
    slug: props.post?.slug ?? '',
    author: props.post?.author ?? '',
    category: props.post?.category ?? '',
    excerpt: props.post?.excerpt ?? '',
    content: props.post?.content ?? '',
    image: null,
    delete_image: false,
    status: props.post?.status ?? 'draft',
});

function submit() {
    const opts = { preserveScroll: true, forceFormData: !!form.image };
    if (isEdit) {
        form.put(route('admin.blog.update', props.post.id), opts);
    } else {
        form.post(route('admin.blog.store'), opts);
    }
}

function onImageChange(e) {
    const file = e.target?.files?.[0];
    if (file) {
        form.image = file;
        form.delete_image = false;
    }
}
</script>

<template>
    <Head :title="isEdit ? 'Edit Blog Post' : 'Create Blog Post'" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="font-title text-2xl font-bold text-primary-1">{{ isEdit ? 'Edit Blog Post' : 'Create Blog Post' }}</h1>
                <Link :href="route('admin.blog.index')" class="rounded-lg border border-gray-300 px-4 py-2 font-body text-sm font-medium text-ink transition hover:bg-gray-50">
                    Back to list
                </Link>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="space-y-4">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Title *</label>
                            <input v-model="form.title" type="text" required class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            <p v-if="form.errors.title" class="mt-1 font-body text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Slug (URL)</label>
                            <input v-model="form.slug" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" placeholder="auto-generated from title" />
                            <p v-if="form.errors.slug" class="mt-1 font-body text-sm text-red-600">{{ form.errors.slug }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block font-body text-sm font-medium text-ink">Author</label>
                                <input v-model="form.author" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                            <div>
                                <label class="block font-body text-sm font-medium text-ink">Category</label>
                                <input v-model="form.category" type="text" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" />
                            </div>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Excerpt</label>
                            <textarea v-model="form.excerpt" rows="3" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Content</label>
                            <textarea v-model="form.content" rows="12" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20" placeholder="Write your blog content here..."></textarea>
                            <p v-if="form.errors.content" class="mt-1 font-body text-sm text-red-600">{{ form.errors.content }}</p>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Featured Image</label>
                            <div class="mt-2 space-y-3">
                                <div v-if="post?.image && !form.delete_image" class="flex items-center gap-3">
                                    <img :src="post.image" alt="Current" class="h-24 w-32 rounded-lg object-cover" />
                                    <button type="button" @click="form.delete_image = true; form.image = null" class="rounded-lg bg-red-100 px-3 py-1 font-body text-sm text-red-700 hover:bg-red-200">Remove</button>
                                </div>
                                <div v-if="form.image" class="flex items-center gap-3">
                                    <span class="font-body text-sm text-green-600">New image: {{ form.image.name }}</span>
                                    <button type="button" @click="form.image = null" class="font-body text-sm text-red-600 hover:underline">Clear</button>
                                </div>
                                <input type="file" accept="image/*" @change="onImageChange" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-1 file:px-4 file:py-2 file:font-medium file:text-white file:hover:bg-primary-1/90" />
                            </div>
                        </div>
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Status</label>
                            <select v-model="form.status" class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 font-body focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-primary-1 px-6 py-3 font-body text-sm font-semibold text-white transition hover:bg-primary-1/90 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : (form.status === 'draft' ? 'Save as Draft' : 'Publish') }}
                    </button>
                    <Link :href="route('admin.blog.index')" class="rounded-lg border border-gray-300 px-6 py-3 font-body text-sm font-medium text-ink transition hover:bg-gray-50">
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
