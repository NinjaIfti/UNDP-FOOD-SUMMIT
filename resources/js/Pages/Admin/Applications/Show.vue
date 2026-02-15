<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    application: Object,
});

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

const formatDate = (d) => d ? new Date(d).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) : '';

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
    <Head title="Application Details" />
    <AdminLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-6">
                <Link :href="route('admin.applications.index')" class="font-body text-sm text-accent-1 hover:underline">Back to Applications</Link>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="mb-4 rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div v-if="application" class="space-y-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="font-title text-2xl font-bold text-primary-1">{{ application.first_name }} {{ application.last_name }}</h1>
                        <p class="font-body text-copy">{{ application.email }}</p>
                        <span class="mt-2 inline-block rounded-full px-2 py-0.5 text-xs font-medium" :class="statusBadgeClass(application.status)">
                            {{ application.status }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template v-if="application.status === 'pending' || application.status === 'waitlisted'">
                            <button
                                @click="approve(application.id)"
                                class="rounded-lg bg-green-600 px-4 py-2 font-body text-sm font-semibold text-white transition hover:bg-green-700"
                            >
                                Approve
                            </button>
                            <button
                                v-if="application.status === 'pending'"
                                @click="waitlist(application.id)"
                                class="rounded-lg bg-amber-500 px-4 py-2 font-body text-sm font-semibold text-white transition hover:bg-amber-600"
                            >
                                Waitlist
                            </button>
                            <button
                                @click="reject(application.id)"
                                class="rounded-lg border border-red-200 px-4 py-2 font-body text-sm font-medium text-red-600 transition hover:bg-red-50"
                            >
                                Reject
                            </button>
                        </template>
                        <button
                            v-else-if="application.status === 'rejected' || application.status === 'waitlisted'"
                            @click="revert(application.id)"
                            class="rounded-lg border border-gray-300 px-4 py-2 font-body text-sm font-medium text-ink transition hover:bg-gray-50"
                        >
                            Revert to Pending
                        </button>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Personal Information</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div><dt class="font-body text-xs text-gray-500">Name</dt><dd class="font-body text-ink">{{ application.first_name }} {{ application.last_name }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Email</dt><dd class="font-body text-ink">{{ application.email }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Phone</dt><dd class="font-body text-ink">{{ application.phone }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Country</dt><dd class="font-body text-ink">{{ application.country || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Organization</dt><dd class="font-body text-ink">{{ application.organization }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Job Title</dt><dd class="font-body text-ink">{{ application.job_title }}</dd></div>
                        <div v-if="application.website" class="sm:col-span-2"><dt class="font-body text-xs text-gray-500">Website</dt><dd class="font-body text-ink"><a :href="application.website" target="_blank" class="text-accent-1 hover:underline">{{ application.website }}</a></dd></div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Visa and Travel</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div><dt class="font-body text-xs text-gray-500">Passport Number</dt><dd class="font-body text-ink">{{ application.passport_number }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Passport Expiry</dt><dd class="font-body text-ink">{{ formatDate(application.passport_expiry) || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Arrival Date</dt><dd class="font-body text-ink">{{ formatDate(application.arrival_date) }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Departure Date</dt><dd class="font-body text-ink">{{ formatDate(application.departure_date) }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Participation</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div><dt class="font-body text-xs text-gray-500">Price Tier</dt><dd class="font-body text-ink">{{ application.price_tier }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Field Visit</dt><dd class="font-body text-ink">{{ application.field_visit ? 'Yes' : 'No' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Accessibility</dt><dd class="font-body text-ink">{{ application.accessibility_needed }} <span v-if="application.accessibility_details">- {{ application.accessibility_details }}</span></dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Dietary</dt><dd class="font-body text-ink">{{ application.dietary }} <span v-if="application.dietary_other">({{ application.dietary_other }})</span></dd></div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Emergency Contact</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div><dt class="font-body text-xs text-gray-500">Name</dt><dd class="font-body text-ink">{{ application.emergency_name }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Phone</dt><dd class="font-body text-ink">{{ application.emergency_phone }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Email</dt><dd class="font-body text-ink">{{ application.emergency_email }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Relation</dt><dd class="font-body text-ink">{{ application.emergency_relation }}</dd></div>
                    </dl>
                </div>

                <div v-if="application.additional_members && application.additional_members.length" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Additional Members</h2>
                    <div class="mt-4 space-y-3">
                        <div v-for="(m, i) in application.additional_members" :key="i" class="rounded-lg border border-gray-100 p-3">
                            <p class="font-body text-sm text-ink">{{ m.first_name }} {{ m.last_name }} <span v-if="m.passport_number">- Passport: {{ m.passport_number }}</span></p>
                        </div>
                    </div>
                </div>

                <div v-if="application.photo" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Photo</h2>
                    <img :src="application.photo" alt="Applicant photo" class="mt-4 h-32 w-24 rounded-lg object-cover" />
                </div>

                <p class="font-body text-xs text-gray-500">Submitted {{ formatDate(application.created_at) }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
