<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    submission: Object,
});

function markAsRead(id) {
    router.post(route('admin.contact-submissions.mark-as-read', id), {}, {
        preserveScroll: true,
    });
}

function markAsUnread(id) {
    router.post(route('admin.contact-submissions.mark-as-unread', id), {}, {
        preserveScroll: true,
    });
}

function deleteSubmission(id) {
    if (confirm('Are you sure you want to delete this contact submission?')) {
        router.delete(route('admin.contact-submissions.destroy', id));
    }
}

const formatDate = (d) => d ? new Date(d).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';
</script>

<template>
    <Head title="Contact Submission Details" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-6">
                <Link :href="route('admin.contact-submissions.index')" class="font-body text-sm text-accent-1 hover:underline">Back to Contact Submissions</Link>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <div v-if="submission" class="space-y-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="font-title text-2xl font-bold text-primary-1">Contact Submission</h1>
                        <p class="font-body text-copy">{{ submission.email }}</p>
                        <span
                            class="mt-2 inline-block rounded-full px-2 py-0.5 text-xs font-medium"
                            :class="submission.read ? 'bg-green-100 text-green-800' : 'bg-accent-2 text-primary-1'"
                        >
                            {{ submission.read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-if="!submission.read"
                            @click="markAsRead(submission.id)"
                            class="rounded-lg bg-green-600 px-4 py-2 font-body text-sm font-semibold text-white transition hover:bg-green-700"
                        >
                            Mark as Read
                        </button>
                        <button
                            v-else
                            @click="markAsUnread(submission.id)"
                            class="rounded-lg bg-amber-500 px-4 py-2 font-body text-sm font-semibold text-white transition hover:bg-amber-600"
                        >
                            Mark as Unread
                        </button>
                        <button
                            @click="deleteSubmission(submission.id)"
                            class="rounded-lg border border-red-200 px-4 py-2 font-body text-sm font-medium text-red-600 transition hover:bg-red-50"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Contact Information</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div>
                            <dt class="font-body text-xs text-gray-500">Name</dt>
                            <dd class="font-body text-ink">{{ submission.name }}</dd>
                        </div>
                        <div>
                            <dt class="font-body text-xs text-gray-500">Email</dt>
                            <dd class="font-body text-ink">
                                <a :href="'mailto:' + submission.email" class="text-accent-1 hover:underline">{{ submission.email }}</a>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="font-body text-xs text-gray-500">Subject</dt>
                            <dd class="font-body text-ink">{{ submission.subject }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Message</h2>
                    <p class="mt-4 whitespace-pre-wrap font-body text-base leading-relaxed text-ink">{{ submission.message }}</p>
                </div>

                <p class="font-body text-xs text-gray-500">Submitted {{ formatDate(submission.created_at) }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
