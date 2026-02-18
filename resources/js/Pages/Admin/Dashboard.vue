<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

const props = defineProps({
    metrics: { type: Array, default: () => [] },
    registrationData: { type: Array, default: () => [] },
    emailActivity: { type: Object, default: () => ({}) },
    paidParticipants: { type: Array, default: () => [] },
});

const searchQuery = ref('');

const filteredParticipants = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();
    if (!q) return props.paidParticipants;
    return props.paidParticipants.filter((p) => {
        const name = `${p.first_name} ${p.last_name}`.toLowerCase();
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

function downloadCSV() {
    const headers = [
        'Participant',
        'Email',
        'Organization',
        'Country',
        'Package',
        'Field Visit',
        'Amount',
        'Payment Status',
        'Payment Date',
    ];
    const rows = filteredParticipants.value.map((p) => [
        `${p.first_name} ${p.last_name}`,
        p.email,
        p.organization,
        p.country,
        (p.price_tier || '').replace('tier', 'Tier '),
        p.field_visit ? 'Yes' : 'No',
        p.payment_amount ? `$${p.payment_amount}` : '',
        'Paid',
        p.payment_date || '',
    ]);
    const csvContent = [
        headers.join(','),
        ...rows.map((row) => row.map((cell) => `"${cell}"`).join(',')),
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
    const tableData = filteredParticipants.value.map((p) => [
        `${p.first_name} ${p.last_name}`,
        p.email,
        p.organization,
        p.country,
        (p.price_tier || '').replace('tier', 'Tier '),
        p.field_visit ? 'Yes' : 'No',
        p.payment_amount ? `$${p.payment_amount}` : '',
        p.payment_date || '',
    ]);
    autoTable(doc, {
        head: [['Participant', 'Email', 'Organization', 'Country', 'Package', 'Field Visit', 'Amount', 'Payment Date']],
        body: tableData,
        startY: 28,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [45, 95, 63], textColor: 255 },
        alternateRowStyles: { fillColor: [245, 245, 245] },
    });
    doc.save(`GCFSS_Paid_Tickets_${new Date().toISOString().slice(0, 10)}.pdf`);
}

function downloadExcel() {
    const rows = filteredParticipants.value.map((p) => [
        `${p.first_name} ${p.last_name}`,
        p.email,
        p.organization,
        p.country,
        (p.price_tier || '').replace('tier', 'Tier '),
        p.field_visit ? 'Yes' : 'No',
        p.payment_amount || '',
        'Paid',
        p.payment_date || '',
    ]);
    const worksheetData = [
        ['Participant', 'Email', 'Organization', 'Country', 'Package', 'Field Visit', 'Amount', 'Payment Status', 'Payment Date'],
        ...rows,
    ];
    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Paid Tickets');
    worksheet['!cols'] = [{ wch: 20 }, { wch: 30 }, { wch: 30 }, { wch: 20 }, { wch: 12 }, { wch: 12 }, { wch: 12 }, { wch: 12 }, { wch: 15 }];
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
                            <p class="text-xs text-gray-500">Today's summary</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Approval emails sent</span>
                            <span class="text-lg font-bold text-[#2d5f3f]">{{ emailActivity.approval_sent ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Emails opened</span>
                            <span class="text-lg font-bold text-[#4a7c59]">{{ emailActivity.opened ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Payment links clicked</span>
                            <span class="text-lg font-bold text-[#d4af37]">{{ emailActivity.payment_clicked ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Reminder emails sent</span>
                            <span class="text-lg font-bold text-[#8b7355]">{{ emailActivity.reminder_sent ?? 0 }}</span>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="filteredParticipants.length === 0">
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No paid tickets found</td>
                            </tr>
                            <tr
                                v-for="p in filteredParticipants"
                                :key="p.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ p.first_name }} {{ p.last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ p.country }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ p.email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ p.organization }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <div class="text-gray-900">{{ (p.price_tier || '').replace('tier', 'Tier ') }}</div>
                                    <div v-if="p.field_visit" class="text-xs text-[#8b7355]">+ Field Visit</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ p.payment_amount ? `$${Number(p.payment_amount).toLocaleString()}` : '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#2d5f3f] text-white">Paid</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ formatDate(p.payment_date) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
