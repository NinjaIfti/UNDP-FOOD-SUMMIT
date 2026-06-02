<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

const props = defineProps({
    metrics: { type: Array, default: () => [] },
    registrationData: { type: Array, default: () => [] },
    emailActivity: { type: Object, default: () => ({}) },
    paidParticipants: { type: Array, default: () => [] },
    packages: { type: Array, default: () => [] },
});

const searchQuery = ref('');
const upgradeModalOpen = ref(false);
const upgradeTarget = ref(null);

const upgradeForm = useForm({
    price_tier_raw: '',
    admin_override_price: '',
    field_visit: false,
});

const openUpgradeModal = (participant) => {
    upgradeTarget.value = participant;
    upgradeForm.price_tier_raw = participant.price_tier_raw ?? '';
    upgradeForm.admin_override_price = participant.admin_override_price ?? '';
    upgradeForm.field_visit = !!participant.field_visit;
    upgradeModalOpen.value = true;
};

const closeUpgradeModal = () => {
    upgradeModalOpen.value = false;
    upgradeTarget.value = null;
    upgradeForm.reset();
};

const submitUpgrade = () => {
    if (!upgradeTarget.value) return;
    upgradeForm.post(route('admin.participants.upgrade', upgradeTarget.value.id), {
        preserveScroll: true,
        onSuccess: () => closeUpgradeModal(),
    });
};

const upgradeStatusLabel = (status) => {
    if (status === 'pending') return 'Upgrade pending';
    if (status === 'confirmed') return 'Upgrade confirmed';
    return null;
};

const upgradeStatusClass = (status) => {
    if (status === 'pending') return 'bg-amber-100 text-amber-800';
    if (status === 'confirmed') return 'bg-blue-100 text-blue-800';
    return '';
};

const filteredParticipants = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();
    if (!q) return props.paidParticipants;
    return props.paidParticipants.filter((p) => {
        const name = `${p.first_name || ''} ${p.middle_name || ''} ${p.last_name || ''}`.toLowerCase();
        const email = (p.email || '').toLowerCase();
        const org = (p.organization || '').toLowerCase();
        const country = (p.country || '').toLowerCase();
        return name.includes(q) || email.includes(q) || org.includes(q) || country.includes(q);
    });
});

const chartRange = ref('3');
const chartOptions = [
    { value: '3', label: 'Last 3 months' },
    { value: '6', label: 'Last 6 months' },
    { value: 'all', label: 'All time' },
];

const maxCount = computed(() => {
    if (props.registrationData.length === 0) return 1;
    return Math.max(...props.registrationData.map((d) => d.count), 1);
});

const chartHeight = 300;
const chartWidth = 800;
const padding = { top: 20, right: 20, bottom: 30, left: 40 };

function chartPath() {
    const data = props.registrationData;
    if (!data.length) return '';
    const w = chartWidth - padding.left - padding.right;
    const h = chartHeight - padding.top - padding.bottom;
    const max = maxCount.value;
    const points = data.map((d, i) => {
        const x = padding.left + (i / (data.length - 1 || 1)) * w;
        const y = padding.top + h - (d.count / max) * h;
        return `${x},${y}`;
    });
    return `M ${points.join(' L ')}`;
}

function chartTicks() {
    const max = maxCount.value;
    const ticks = [];
    const step = max <= 5 ? 1 : Math.ceil(max / 5);
    for (let v = 0; v <= max; v += step) {
        ticks.push(v);
    }
    if (ticks[ticks.length - 1] !== max) ticks.push(max);
    return ticks;
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return dateStr;
}

const allHeaders = [
    'First Name', 'Middle Name', 'Last Name', 'Email', 'Phone',
    'Nationality', 'Country of Residence',
    'Organization', 'Organization Type', 'Organization Type (Other)', 'Organization Size',
    'Job Title', 'Website',
    'Package', 'Flexible Support Amount', 'Immersive Experience (Add-on)',
    'Participation Budget Support',
    'Passport Number', 'Passport Expiry',
    'Arrival Date', 'Departure Date',
    'Dietary Requirements', 'Dietary (Other)',
    'Accessibility Needed', 'Accessibility Details',
    'Heard From', 'Heard From (Other)',
    'CoFSA Member', 'CoFSA Newsletter',
    'Emergency Name', 'Emergency Phone', 'Emergency Email', 'Emergency Relation',
    'Payment Status', 'Payment Amount', 'Payment Date',
    'Application Date',
];

function buildRow(p) {
    return [
        p.first_name, p.middle_name || '', p.last_name, p.email, p.phone || '',
        p.country || '', p.country_of_residence || '',
        p.organization || '', p.organization_type || '', p.organization_type_other || '', p.organization_size || '',
        p.job_title || '', p.website || '',
        (p.price_tier || '').replace('tier', 'Tier '), p.flexible_support_amount || '', p.field_visit ? 'Yes' : 'No',
        p.participation_budget_support || '',
        p.passport_number || '', p.passport_expiry || '',
        p.arrival_date || '', p.departure_date || '',
        p.dietary || '', p.dietary_other || '',
        p.accessibility_needed ? 'Yes' : 'No', p.accessibility_details || '',
        p.heard_from || '', p.heard_from_other || '',
        p.cofsa_member ? 'Yes' : 'No', p.cofsa_newsletter ? 'Yes' : 'No',
        p.emergency_name || '', p.emergency_phone || '', p.emergency_email || '', p.emergency_relation || '',
        p.payment_status || 'Paid', p.payment_amount ? `$${p.payment_amount}` : '', p.payment_date || '',
        p.created_at || '',
    ];
}

function downloadCSV() {
    const rows = filteredParticipants.value.map(buildRow);
    const csvContent = [
        allHeaders.join(','),
        ...rows.map((row) => row.map((cell) => `"${String(cell).replace(/"/g, '""')}"`).join(',')),
    ].join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `GCFSS_Paid_Tickets_${new Date().toISOString().slice(0, 10)}.csv`;
    link.click();
    URL.revokeObjectURL(link.href);
}

function downloadPDF() {
    const doc = new jsPDF('landscape');
    doc.setFontSize(16);
    doc.text('GCFSS 2026 - Paid Ticket Purchases', 14, 15);
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`, 14, 22);
    autoTable(doc, {
        head: [allHeaders],
        body: filteredParticipants.value.map(buildRow),
        startY: 28,
        styles: { fontSize: 6 },
        headStyles: { fillColor: [45, 95, 63], textColor: 255 },
        alternateRowStyles: { fillColor: [245, 245, 245] },
    });
    doc.save(`GCFSS_Paid_Tickets_${new Date().toISOString().slice(0, 10)}.pdf`);
}

function downloadExcel() {
    const worksheetData = [allHeaders, ...filteredParticipants.value.map(buildRow)];
    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
    worksheet['!cols'] = allHeaders.map(() => ({ wch: 18 }));
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Paid Tickets');
    XLSX.writeFile(workbook, `GCFSS_Paid_Tickets_${new Date().toISOString().slice(0, 10)}.xlsx`);
}

const iconMap = {
    users: 'M16 7a4 4 0 11-8 0 4 4 0 018 0z M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    check: 'M9 12l2 2 4-4 M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    x: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ticket: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
    mail: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    file: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    download: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
    spreadsheet: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    search: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
};
</script>

<template>
    <Head title="Dashboard Overview" />
    <AdminLayout>
        <div class="space-y-6">
            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 p-3 border border-green-200">
                <p class="text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 p-3 border border-red-200">
                <p class="text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard Overview</h1>
                <p class="text-gray-600">
                    Welcome to the GCFSS 2026 Admin Dashboard
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="metric in metrics"
                    :key="metric.title"
                    class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex items-start justify-between mb-4">
                        <div
                            :class="['w-12 h-12 rounded-lg bg-gradient-to-br flex items-center justify-center shadow-sm', metric.color]"
                        >
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <template v-if="metric.icon === 'users'">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </template>
                                <template v-else-if="metric.icon === 'check'">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </template>
                                <template v-else-if="metric.icon === 'x'">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </template>
                                <template v-else-if="metric.icon === 'ticket'">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </template>
                                <template v-else>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap[metric.icon] || iconMap.users" />
                                </template>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm text-gray-500 mb-1">{{ metric.title }}</h3>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ metric.value }}</p>
                    <p class="text-sm text-[#4a7c59]">{{ metric.change }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Registration Traffic</h2>
                            <p class="text-sm text-gray-500">Applications over time</p>
                        </div>
                        <select
                            v-model="chartRange"
                            class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-white"
                        >
                            <option v-for="opt in chartOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </div>
                    <div class="overflow-x-auto">
                        <svg
                            :viewBox="`0 0 ${chartWidth} ${chartHeight}`"
                            class="w-full"
                            style="min-height: 300px;"
                            preserveAspectRatio="xMidYMid meet"
                        >
                            <line
                                v-for="tick in chartTicks()"
                                :key="'tick-' + tick"
                                :x1="padding.left"
                                :x2="chartWidth - padding.right"
                                :y1="chartHeight - padding.bottom - ((tick / maxCount) * (chartHeight - padding.top - padding.bottom))"
                                :y2="chartHeight - padding.bottom - ((tick / maxCount) * (chartHeight - padding.top - padding.bottom))"
                                stroke="#e5e7eb"
                                stroke-dasharray="3 3"
                            />
                            <path
                                :d="chartPath()"
                                fill="none"
                                stroke="#2d5f3f"
                                stroke-width="3"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <circle
                                v-for="(d, i) in registrationData"
                                :key="d.date"
                                :cx="padding.left + (i / (registrationData.length - 1 || 1)) * (chartWidth - padding.left - padding.right)"
                                :cy="padding.top + (chartHeight - padding.top - padding.bottom) - (d.count / maxCount) * (chartHeight - padding.top - padding.bottom)"
                                r="4"
                                fill="#2d5f3f"
                            />
                            <g v-for="(d, i) in registrationData" :key="'l-' + d.date">
                                <text
                                    :x="padding.left + (i / (registrationData.length - 1 || 1)) * (chartWidth - padding.left - padding.right)"
                                    :y="chartHeight - 8"
                                    text-anchor="middle"
                                    class="text-[10px] fill-gray-500"
                                >
                                    {{ d.date }}
                                </text>
                            </g>
                        </svg>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#d4af37] to-[#8b7355] flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap.mail" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Email Activity</h2>
                            <p class="text-xs text-gray-500">All time totals</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Approval emails sent</span>
                            <span class="text-lg font-bold text-[#2d5f3f]">{{ emailActivity.approval_sent ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Registration confirmations sent</span>
                            <span class="text-lg font-bold text-[#4a7c59]">{{ emailActivity.confirmation_sent ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Payment reminder emails sent</span>
                            <span class="text-lg font-bold text-[#d4af37]">{{ emailActivity.reminder_sent ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Total emails sent</span>
                            <span class="text-lg font-bold text-[#8b7355]">{{ emailActivity.total_sent ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-[#2d5f3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap.ticket" />
                            </svg>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Paid Ticket Purchases</h2>
                                <p class="text-xs text-gray-500">{{ filteredParticipants.length }} paid tickets</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="downloadCSV"
                                class="flex items-center gap-2 px-3 py-2 bg-[#2d5f3f] text-white rounded-lg hover:bg-[#234a32] transition-colors text-sm"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap.file" /></svg>
                                CSV
                            </button>
                            <button
                                type="button"
                                @click="downloadPDF"
                                class="flex items-center gap-2 px-3 py-2 bg-[#d4af37] text-[#1a2e1a] rounded-lg hover:bg-[#b8962f] transition-colors text-sm"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap.download" /></svg>
                                PDF
                            </button>
                            <button
                                type="button"
                                @click="downloadExcel"
                                class="flex items-center gap-2 px-3 py-2 bg-[#4a7c59] text-white rounded-lg hover:bg-[#3d6749] transition-colors text-sm"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap.spreadsheet" /></svg>
                                Excel
                            </button>
                        </div>
                    </div>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconMap.search" />
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by name, email, organization, or country..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                        />
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Upgrade</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="filteredParticipants.length === 0">
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">No paid tickets found</td>
                            </tr>
                            <tr
                                v-for="p in filteredParticipants"
                                :key="p.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ [p.first_name, p.middle_name, p.last_name].filter(Boolean).join(' ') }}</div>
                                    <div class="text-xs text-gray-500">{{ p.country }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ p.email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ p.organization }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="text-gray-900">{{ p.price_tier || '—' }}</div>
                                    <div v-if="p.field_visit" class="text-xs text-[#8b7355]">+ Field Visit</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ p.payment_amount ? `$${Number(p.payment_amount).toLocaleString()}` : '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#2d5f3f] text-white">Paid</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        v-if="upgradeStatusLabel(p.upgrade_status)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="upgradeStatusClass(p.upgrade_status)"
                                    >
                                        {{ upgradeStatusLabel(p.upgrade_status) }}
                                    </span>
                                    <span v-else class="text-xs text-gray-400">—</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ formatDate(p.payment_date) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button
                                        v-if="p.price_tier_raw !== 'sponsored' && !(p.price_tier || '').toLowerCase().includes('sponsored participant')"
                                        type="button"
                                        @click="openUpgradeModal(p)"
                                        class="inline-flex items-center gap-1 rounded-lg bg-[#d4af37] px-3 py-1.5 text-xs font-medium text-[#1a2e1a] transition-colors hover:bg-[#b8962f]"
                                        title="Sync higher tier or add-on to DashTicket"
                                    >
                                        Upgrade
                                    </button>
                                    <span v-else class="text-xs text-gray-400">N/A</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                v-if="upgradeModalOpen && upgradeTarget"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div class="w-full max-w-lg rounded-lg border border-gray-200 bg-white p-6 shadow-lg max-h-[90vh] overflow-y-auto">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">DashTicket Upgrade</h2>
                            <p class="text-sm text-gray-500">
                                {{ [upgradeTarget.first_name, upgradeTarget.last_name].filter(Boolean).join(' ') }} — {{ upgradeTarget.email }}
                            </p>
                        </div>
                        <button type="button" @click="closeUpgradeModal" class="rounded p-1.5 text-gray-500 hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="mb-4 text-sm text-gray-600">
                        Updates entitlements in DashTicket. If the participant already paid, they will be charged only the price difference on their next visit.
                    </p>
                    <form @submit.prevent="submitUpgrade" class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Package tier</label>
                            <select
                                v-model="upgradeForm.price_tier_raw"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#2d5f3f] focus:outline-none focus:ring-2 focus:ring-[#2d5f3f]/20"
                            >
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                    {{ pkg.name }} — USD {{ Number(pkg.price).toLocaleString() }}
                                </option>
                            </select>
                            <p v-if="upgradeForm.errors.price_tier_raw" class="mt-1 text-xs text-red-600">{{ upgradeForm.errors.price_tier_raw }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Admin price override (optional)</label>
                            <input
                                v-model="upgradeForm.admin_override_price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="Leave empty to use tier price"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#2d5f3f] focus:outline-none focus:ring-2 focus:ring-[#2d5f3f]/20"
                            />
                            <p v-if="upgradeForm.errors.admin_override_price" class="mt-1 text-xs text-red-600">{{ upgradeForm.errors.admin_override_price }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                id="upgrade-field-visit"
                                v-model="upgradeForm.field_visit"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-[#2d5f3f] focus:ring-[#2d5f3f]"
                            />
                            <label for="upgrade-field-visit" class="text-sm text-gray-700">Immersive experience (field visit add-on)</label>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button
                                type="button"
                                @click="closeUpgradeModal"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="upgradeForm.processing"
                                class="rounded-lg bg-[#2d5f3f] px-4 py-2 text-sm font-medium text-white hover:bg-[#234a32] disabled:opacity-50"
                            >
                                {{ upgradeForm.processing ? 'Syncing...' : 'Apply upgrade' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
