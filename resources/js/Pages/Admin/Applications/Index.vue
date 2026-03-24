<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

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

const heardFromStyles = {
    'Email Newsletter': { icon: 'mail', bg: 'bg-primary-2/10', color: 'text-primary-2' },
    'Social Media': { icon: 'share', bg: 'bg-primary-2/10', color: 'text-primary-2' },
    'Referral': { icon: 'users', bg: 'bg-primary-2/10', color: 'text-primary-2' },
    'Partner Organization': { icon: 'briefcase', bg: 'bg-accent-2/10', color: 'text-accent-2' },
    'Website Search': { icon: 'search', bg: 'bg-ink/10', color: 'text-ink' },
    Other: { icon: 'dots', bg: 'bg-gray-200', color: 'text-ink' },
};

const heardFromLabels = [
    'Email Newsletter',
    'Social Media',
    'Referral',
    'Partner Organization',
    'Website Search',
    'Other',
];

const getHeardFromStyle = (label) => {
    return heardFromStyles[label] || heardFromStyles.Other;
};

const formatSubmittedDate = (dateString) => {
    if (!dateString) return { date: '—', time: '' };
    
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return { date: '—', time: '' };
    
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2);
    
    let hours = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12;
    const time = `${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;
    
    return {
        date: `${day}-${month}-${year}`,
        time: time,
    };
};

function exportApplicationsPdf() {
    const rows = (props.applications?.data || []).map((app) => [
        `${app.first_name} ${app.last_name}`,
        app.email,
        app.country,
        app.country_of_residence || '',
        app.organization,
        app.price_tier,
        app.status,
        app.created_at,
    ]);

    const doc = new jsPDF('landscape');

    doc.setFontSize(16);
    doc.text('Applications', 14, 15);

    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 22);

    autoTable(doc, {
        head: [[
            'Name',
            'Email',
            'Nationality',
            'Country of residence',
            'Organization',
            'Price tier',
            'Status',
            'Submitted',
        ]],
        body: rows,
        startY: 28,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [45, 95, 63], textColor: 255 },
        alternateRowStyles: { fillColor: [245, 245, 245] },
    });

    const date = new Date().toISOString().slice(0, 10);
    doc.save(`applications_${date}.pdf`);
}

function exportApplicationsExcel() {
    const data = props.applications?.data || [];

    const worksheetData = [
        ['First name', 'Last name', 'Email', 'Nationality', 'Country of residence', 'Organization', 'Price tier', 'Status', 'Submitted'],
        ...data.map((app) => [
            app.first_name,
            app.last_name,
            app.email,
            app.country,
            app.country_of_residence || '',
            app.organization,
            app.price_tier,
            app.status,
            app.created_at,
        ]),
    ];

    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Applications');

    worksheet['!cols'] = [
        { wch: 16 },
        { wch: 16 },
        { wch: 28 },
        { wch: 18 },
        { wch: 26 },
        { wch: 12 },
        { wch: 12 },
        { wch: 20 },
    ];

    const date = new Date().toISOString().slice(0, 10);
    XLSX.writeFile(workbook, `applications_${date}.xlsx`);
}
</script>

<template>
    <Head title="Application Review" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">Application Review</h1>
                    <p class="mt-1 font-body text-sm text-copy">Review and manage pending applications</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Link
                        :href="route('admin.applications.export', {
                            search: search || undefined,
                            status: statusFilter !== 'all' ? statusFilter : undefined,
                        })"
                        class="inline-flex items-center gap-2 rounded-full bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white shadow-sm transition hover:bg-primary-2/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V3" />
                        </svg>
                        <span>Export CSV</span>
                    </Link>
                    <button
                        type="button"
                        @click="exportApplicationsPdf"
                        class="inline-flex items-center gap-2 rounded-full bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white shadow-sm transition hover:bg-primary-2/90"
                    >
                        <span>PDF</span>
                    </button>
                    <button
                        type="button"
                        @click="exportApplicationsExcel"
                        class="inline-flex items-center gap-2 rounded-full bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white shadow-sm transition hover:bg-primary-2/90"
                    >
                        <span>Excel</span>
                    </button>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="mb-4 rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Pending Review</p>
                    <p class="mt-2 font-title text-2xl font-semibold text-primary-2">{{ stats?.pending ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Approved</p>
                    <p class="mt-2 font-title text-2xl font-semibold text-green-600">{{ stats?.approved ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Rejected</p>
                    <p class="mt-2 font-title text-2xl font-semibold text-red-600">{{ stats?.rejected ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="font-body text-xs text-copy">Waitlist</p>
                    <p class="mt-2 font-title text-2xl font-semibold text-primary-2">{{ stats?.waitlisted ?? 0 }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="font-title text-base font-semibold text-primary-1">How Applicants Found Us</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-3 lg:grid-cols-6">
                    <div
                        v-for="label in heardFromLabels"
                        :key="label"
                        class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-2/10 text-primary-2"
                        >
                            <svg
                                v-if="getHeardFromStyle(label).icon === 'mail'"
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z" />
                            </svg>
                            <svg
                                v-else-if="getHeardFromStyle(label).icon === 'share'"
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12v3a4 4 0 004 4h8a4 4 0 004-4v-3M16 6l-4-4m0 0L8 6m4-4v14" />
                            </svg>
                            <svg
                                v-else-if="getHeardFromStyle(label).icon === 'users'"
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg
                                v-else-if="getHeardFromStyle(label).icon === 'briefcase'"
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6h4m-9 4h14M6 10V7a2 2 0 012-2h8a2 2 0 012 2v3m-2 4h-3m-4 0H6m3 0v1a2 2 0 002 2h2a2 2 0 002-2v-1" />
                            </svg>
                            <svg
                                v-else-if="getHeardFromStyle(label).icon === 'search'"
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                            </svg>
                            <svg
                                v-else
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10h.01M12 10h.01M18 10h.01M6 16h.01M12 16h.01M18 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-ink">{{ label }}</p>
                            <p class="mt-1 font-title text-lg font-semibold text-primary-1">
                                {{ (stats && stats.heard_from && stats.heard_from[label]) || 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search by name, email, organization..."
                            class="block w-full rounded-full border border-gray-300 py-2 pl-10 pr-4 font-body text-sm focus:border-primary-2 focus:outline-none focus:ring-2 focus:ring-primary-2/20"
                            @keyup.enter="applyFilters"
                        />
                        <svg
                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <select
                                v-model="statusFilter"
                                @change="applyFilters"
                                class="rounded-full border border-gray-300 px-4 py-2 pl-9 font-body text-sm focus:border-primary-2 focus:outline-none focus:ring-2 focus:ring-primary-2/20"
                            >
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="waitlisted">Waitlisted</option>
                            </select>
                            <svg
                                class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M4 9h16M5 14h14M7 19h10" />
                            </svg>
                        </div>
                        <button
                            @click="applyFilters"
                            class="rounded-full bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition hover:bg-primary-2/90"
                        >
                            Apply
                        </button>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Name
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Email
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Nationality
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Country of residence
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Organization
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Price Tier
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Submitted
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Status
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="app in applications?.data"
                                :key="app.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="font-title text-sm font-semibold text-primary-1">
                                        {{ app.first_name }} {{ app.last_name }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-body text-copy">
                                    {{ app.email }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-body text-copy">
                                    {{ app.country }}
                                </td>
                                <td class="max-w-xs truncate px-4 py-3 text-sm font-body text-copy">
                                    {{ app.organization }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-body text-copy">
                                    <span class="inline-flex items-center rounded-full border border-primary-2/30 bg-primary-2/10 px-2.5 py-0.5 text-xs font-medium text-primary-2">
                                        {{ app.price_tier_display || app.price_tier }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-body text-copy">
                                    <div class="flex flex-col">
                                        <span>{{ formatSubmittedDate(app.created_at).date }}</span>
                                        <span class="text-xs text-gray-500">{{ formatSubmittedDate(app.created_at).time }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span
                                        class="rounded-full px-2 py-0.5 text-xs font-medium"
                                        :class="statusBadgeClass(app.status)"
                                    >
                                        {{ app.status }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <Link
                                            :href="route('admin.applications.show', app.id)"
                                            class="rounded-lg bg-primary-2 px-3 py-2 font-body text-xs font-medium text-white transition hover:bg-primary-2/90"
                                        >
                                            View
                                        </Link>
                                        <template v-if="app.status === 'pending'">
                                            <button
                                                @click="approve(app.id)"
                                                class="rounded-lg bg-primary-2 px-3 py-2 font-body text-xs font-medium text-white transition hover:bg-primary-2/90"
                                            >
                                                Approve
                                            </button>
                                            <button
                                                @click="waitlist(app.id)"
                                                class="rounded-lg border border-primary-2 px-3 py-2 font-body text-xs font-medium text-primary-2 transition hover:bg-primary-2/10"
                                            >
                                                Waitlist
                                            </button>
                                            <button
                                                @click="reject(app.id)"
                                                class="rounded-lg border border-primary-2/50 px-3 py-2 font-body text-xs font-medium text-primary-1 transition hover:bg-primary-2/5"
                                            >
                                                Reject
                                            </button>
                                        </template>
                                        <template v-else-if="app.status === 'waitlisted'">
                                            <button
                                                @click="approve(app.id)"
                                                class="rounded-lg bg-primary-2 px-3 py-2 font-body text-xs font-medium text-white transition hover:bg-primary-2/90"
                                            >
                                                Approve
                                            </button>
                                            <button
                                                @click="reject(app.id)"
                                                class="rounded-lg border border-primary-2/50 px-3 py-2 font-body text-xs font-medium text-primary-1 transition hover:bg-primary-2/5"
                                            >
                                                Reject
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!applications?.data?.length">
                                <td colspan="8" class="px-4 py-8 text-center">
                                    <p class="font-body text-copy">No applications yet.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="applications?.links?.length" class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="link in applications.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="rounded px-3 py-1 font-body text-sm"
                            :class="link.active ? 'bg-primary-2 text-white' : 'text-ink hover:bg-primary-2/10'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
