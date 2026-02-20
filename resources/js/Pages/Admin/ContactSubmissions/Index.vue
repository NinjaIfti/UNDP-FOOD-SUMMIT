<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    submissions: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const filter = ref(props.filters?.filter || 'all');

function applyFilters() {
    router.get(route('admin.contact-submissions.index'), {
        search: search.value || undefined,
        filter: filter.value !== 'all' ? filter.value : undefined,
    }, { preserveState: true });
}

function deleteSubmission(id) {
    if (confirm('Are you sure you want to delete this contact submission?')) {
        router.delete(route('admin.contact-submissions.destroy', id));
    }
}

const formatDate = (d) => d ? new Date(d).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';
</script>

<template>
    <Head title="Contact Submissions" />
    <AdminLayout>
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="font-title text-3xl font-bold text-primary-1">Contact Submissions</h1>
                    <p class="mt-1 font-body text-sm text-copy">View and manage contact form submissions</p>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <div class="mb-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-sm text-gray-600">Total Submissions</p>
                    <p class="mt-1 font-title text-2xl font-bold text-primary-1">{{ stats.total }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-sm text-gray-600">Unread</p>
                    <p class="mt-1 font-title text-2xl font-bold text-accent-2">{{ stats.unread }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-sm text-gray-600">Read</p>
                    <p class="mt-1 font-title text-2xl font-bold text-primary-2">{{ stats.read }}</p>
                </div>
            </div>

            <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 gap-2">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by name, email, subject..."
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        @keyup.enter="applyFilters"
                    />
                    <select
                        v-model="filter"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        @change="applyFilters"
                    >
                        <option value="all">All</option>
                        <option value="unread">Unread</option>
                    </select>
                    <button
                        @click="applyFilters"
                        class="rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition hover:bg-primary-2/90"
                    >
                        Search
                    </button>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-body text-xs font-medium uppercase tracking-wider text-gray-700">Name</th>
                                <th class="px-6 py-3 text-left font-body text-xs font-medium uppercase tracking-wider text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left font-body text-xs font-medium uppercase tracking-wider text-gray-700">Subject</th>
                                <th class="px-6 py-3 text-left font-body text-xs font-medium uppercase tracking-wider text-gray-700">Date</th>
                                <th class="px-6 py-3 text-left font-body text-xs font-medium uppercase tracking-wider text-gray-700">Status</th>
                                <th class="px-6 py-3 text-right font-body text-xs font-medium uppercase tracking-wider text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="submission in submissions.data" :key="submission.id" class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 font-body text-sm text-ink">{{ submission.name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-body text-sm text-ink">
                                    <a :href="'mailto:' + submission.email" class="text-accent-1 hover:underline">{{ submission.email }}</a>
                                </td>
                                <td class="px-6 py-4 font-body text-sm text-ink">{{ submission.subject }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-body text-sm text-gray-500">{{ formatDate(submission.created_at) }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-medium"
                                        :class="submission.read ? 'bg-green-100 text-green-800' : 'bg-accent-2 text-primary-1'"
                                    >
                                        {{ submission.read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right font-body text-sm">
                                    <Link
                                        :href="route('admin.contact-submissions.show', submission.id)"
                                        class="text-accent-1 hover:underline"
                                    >
                                        View
                                    </Link>
                                    <button
                                        @click="deleteSubmission(submission.id)"
                                        class="ml-4 text-red-600 hover:underline"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!submissions.data || submissions.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center font-body text-sm text-gray-500">No contact submissions found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="submissions.links && submissions.links.length > 3" class="border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <Link
                                v-if="submissions.links[0].url"
                                :href="submissions.links[0].url"
                                class="rounded-md border border-gray-300 bg-white px-4 py-2 font-body text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Previous
                            </Link>
                            <Link
                                v-if="submissions.links[submissions.links.length - 1].url"
                                :href="submissions.links[submissions.links.length - 1].url"
                                class="ml-3 rounded-md border border-gray-300 bg-white px-4 py-2 font-body text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="font-body text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ submissions.from }}</span> to <span class="font-medium">{{ submissions.to }}</span> of <span class="font-medium">{{ submissions.total }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <Link
                                        v-for="link in submissions.links"
                                        :key="link.label"
                                        :href="link.url || '#'"
                                        class="relative inline-flex items-center px-4 py-2 font-body text-sm font-semibold"
                                        :class="[
                                            link.active ? 'z-10 bg-primary-2 text-white focus:z-20' : 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20',
                                            !link.url ? 'cursor-not-allowed opacity-50' : '',
                                        ]"
                                        v-html="link.label"
                                    />
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
