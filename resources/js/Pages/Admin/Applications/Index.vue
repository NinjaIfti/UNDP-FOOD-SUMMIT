<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    applications: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || 'all');

function applyFilters() {
    router.get(route('admin.applications.index'), {
        search: search.value || undefined,
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
    }, { preserveState: true });
}

function approve(id) {
    if (confirm('Approve this application? A user account will be created and a password reset link will be sent to the applicant.')) {
        router.post(route('admin.applications.approve', id));
    }
}

function reject(id) {
    if (confirm('Reject this application?')) {
        router.post(route('admin.applications.reject', id));
    }
}

function waitlist(id) {
    router.post(route('admin.applications.waitlist', id));
}

function revert(id) {
    router.post(route('admin.applications.revert', id));
}

const statusBadgeClass = (status) => {
    switch (status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        case 'waitlisted': return 'bg-amber-100 text-amber-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Applications" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl">
            <div class="mb-6">
                <h1 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">Applications</h1>
                <p class="mt-1 font-body text-sm text-copy">Review and manage summit registration applications</p>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="mb-4 rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Pending</p>
                    <p class="font-title text-xl font-bold text-primary-1">{{ stats?.pending ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Approved</p>
                    <p class="font-title text-xl font-bold text-green-600">{{ stats?.approved ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Rejected</p>
                    <p class="font-title text-xl font-bold text-red-600">{{ stats?.rejected ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Waitlisted</p>
                    <p class="font-title text-xl font-bold text-amber-600">{{ stats?.waitlisted ?? 0 }}</p>
                </div>
            </div>

            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by name, email, organization..."
                        class="block w-full rounded-lg border border-gray-300 py-2 pl-4 pr-4 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        @keyup.enter="applyFilters"
                    />
                </div>
                <select
                    v-model="statusFilter"
                    @change="applyFilters"
                    class="rounded-lg border border-gray-300 px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                >
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="waitlisted">Waitlisted</option>
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
                        v-for="app in applications?.data"
                        :key="app.id"
                        class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="font-title font-semibold text-primary-1">{{ app.first_name }} {{ app.last_name }}</h3>
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="statusBadgeClass(app.status)">
                                    {{ app.status }}
                                </span>
                            </div>
                            <p class="font-body text-sm text-copy">{{ app.email }}</p>
                            <p class="font-body text-sm text-gray-500">{{ app.organization }} · {{ app.price_tier }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <Link
                                :href="route('admin.applications.show', app.id)"
                                class="rounded-lg border border-gray-300 px-3 py-2 font-body text-sm font-medium text-ink transition hover:bg-gray-50"
                            >
                                View
                            </Link>
                            <template v-if="app.status === 'pending'">
                                <button
                                    @click="approve(app.id)"
                                    class="rounded-lg bg-green-600 px-3 py-2 font-body text-sm font-medium text-white transition hover:bg-green-700"
                                >
                                    Approve
                                </button>
                                <button
                                    @click="waitlist(app.id)"
                                    class="rounded-lg bg-amber-500 px-3 py-2 font-body text-sm font-medium text-white transition hover:bg-amber-600"
                                >
                                    Waitlist
                                </button>
                                <button
                                    @click="reject(app.id)"
                                    class="rounded-lg border border-red-200 px-3 py-2 font-body text-sm font-medium text-red-600 transition hover:bg-red-50"
                                >
                                    Reject
                                </button>
                            </template>
                            <template v-else-if="app.status === 'waitlisted'">
                                <button
                                    @click="approve(app.id)"
                                    class="rounded-lg bg-green-600 px-3 py-2 font-body text-sm font-medium text-white transition hover:bg-green-700"
                                >
                                    Approve
                                </button>
                                <button
                                    @click="reject(app.id)"
                                    class="rounded-lg border border-red-200 px-3 py-2 font-body text-sm font-medium text-red-600 transition hover:bg-red-50"
                                >
                                    Reject
                                </button>
                            </template>
                        </div>
                    </div>
                    <div v-if="!applications?.data?.length" class="p-8 text-center">
                        <p class="font-body text-copy">No applications yet.</p>
                    </div>
                </div>
                <div v-if="applications?.links?.length" class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="link in applications.links"
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
