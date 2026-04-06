<script setup>
import { computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    participants: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
    packages: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
const paymentFilter = ref('all');
const viewModalOpen = ref(false);
const addModalOpen = ref(false);
const selectedParticipant = ref(null);
const syncing = ref(false);

const paymentStatusColors = {
    paid: 'bg-primary-2 text-white',
    pending: 'bg-accent-2 text-primary-1',
    partial: 'bg-ink text-white',
    refunded: 'bg-gray-600 text-white',
};

const addForm = useForm({
    first_name: '',
    last_name: '',
    email: '',
    organization: '',
    country: '',
    price_tier: props.packages?.[0]?.id ?? '',
    field_visit: false,
    payment_status: 'pending',
    payment_amount: '',
});

const filteredParticipants = computed(() => {
    if (!props.participants?.data) return [];

    const query = searchQuery.value.toLowerCase().trim();
    const statusFilter = paymentFilter.value;

    return props.participants.data.filter((participant) => {
        const matchesSearch =
            (participant.first_name || '').toLowerCase().includes(query) ||
            (participant.last_name || '').toLowerCase().includes(query) ||
            (participant.email || '').toLowerCase().includes(query) ||
            (participant.organization || '').toLowerCase().includes(query);
        const matchesPayment = statusFilter === 'all' || participant.payment_status === statusFilter;
        return matchesSearch && matchesPayment;
    });
});

const exportParticipants = () => {
    router.get(route('admin.payments.export', {
        search: searchQuery.value || undefined,
        payment_status: paymentFilter.value !== 'all' ? paymentFilter.value : undefined,
    }));
};

const resendApprovalEmail = (participant) => {
    router.post(route('admin.payments.resend-email', participant.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by flash message
        },
    });
};

const openViewModal = (participant) => {
    selectedParticipant.value = participant;
    viewModalOpen.value = true;
};

const handleAddParticipant = () => {
    addForm.post(route('admin.payments.store'), {
        preserveScroll: true,
        onSuccess: () => {
            addModalOpen.value = false;
            addForm.reset();
        },
    });
};

const syncDashTicket = () => {
    syncing.value = true;
    router.post(route('admin.payments.sync-dashticket'), {}, {
        preserveScroll: true,
        onFinish: () => {
            syncing.value = false;
        },
    });
};

const updateStatus = (participant, status) => {
    router.put(route('admin.payments.update-status', participant.id), {
        payment_status: status,
        payment_amount: participant.payment_amount ?? null,
    }, { preserveScroll: true });
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
    <Head title="Payment Management" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="mb-2 font-title text-2xl font-bold text-primary-1">
                        Payment Management
                    </h1>
                    <p class="font-body text-sm text-copy">
                        Track and manage participant payments and registration status
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        @click="syncDashTicket"
                        :disabled="syncing"
                        class="inline-flex items-center gap-2 rounded-lg border border-primary-2 px-4 py-2 font-body text-sm font-medium text-primary-2 transition-colors hover:bg-primary-2/10 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg class="h-4 w-4" :class="{ 'animate-spin': syncing }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                        {{ syncing ? 'Syncing...' : 'Sync from DashTicket' }}
                    </button>
                    <button
                        type="button"
                        @click="exportParticipants"
                        class="inline-flex items-center gap-2 rounded-lg bg-ink px-4 py-2 font-body text-sm font-medium text-white transition-colors hover:bg-ink/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V3"
                            />
                        </svg>
                        Export
                    </button>
                    <button
                        type="button"
                        @click="addModalOpen = true"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors hover:bg-primary-2/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                            />
                        </svg>
                        Add Manual Registration
                    </button>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="mb-1 font-body text-sm text-copy">Total Approved</p>
                    <p class="font-title text-2xl font-bold text-primary-1">
                        {{ stats?.total_approved ?? 0 }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="mb-1 font-body text-sm text-copy">Paid</p>
                    <p class="font-title text-2xl font-bold text-primary-2">
                        {{ stats?.paid ?? 0 }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="mb-1 font-body text-sm text-copy">Pending Payment</p>
                    <p class="font-title text-2xl font-bold text-accent-2">
                        {{ stats?.pending ?? 0 }}
                    </p>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row">
                    <div class="relative flex-1">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search participants..."
                            class="block w-full rounded-full border border-gray-300 bg-white py-2 pl-10 pr-4 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        />
                        <svg
                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                            />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-copy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 4h18M4 9h16M5 14h14M7 19h10"
                            />
                        </svg>
                        <select
                            v-model="paymentFilter"
                            class="rounded-full border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        >
                            <option value="all">All Payment Status</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Name
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Email
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Organization
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Payment Status
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Package
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="participant in filteredParticipants"
                                :key="participant.id"
                                class="transition-colors hover:bg-gray-50"
                            >
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="font-title font-medium text-primary-1">
                                        {{ participant.first_name }} {{ participant.last_name }}
                                    </div>
                                    <div class="font-body text-xs text-copy">
                                        {{ participant.country }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 font-body text-sm text-copy">
                                    {{ participant.email }}
                                </td>
                                <td class="px-4 py-3 font-body text-sm text-primary-1">
                                    <div class="max-w-[200px] truncate" :title="participant.organization">
                                        {{ participant.organization }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        :class="paymentStatusColors[participant.payment_status] || paymentStatusColors.pending"
                                    >
                                        {{
                                            participant.payment_status === 'paid'
                                                ? 'Paid'
                                                : participant.payment_status.charAt(0).toUpperCase() +
                                                  participant.payment_status.slice(1)
                                        }}
                                    </span>
                                    <div v-if="participant.payment_amount" class="mt-1 font-body text-xs text-copy">
                                        ${{ participant.payment_amount }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 font-body text-sm text-primary-1">
                                    <div>
                                        {{ participant.price_tier }}
                                    </div>
                                    <div v-if="participant.field_visit" class="font-body text-xs text-ink">
                                        + Field Visit
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <button
                                            v-if="participant.payment_status !== 'paid'"
                                            type="button"
                                            @click="updateStatus(participant, 'paid')"
                                            class="rounded p-1.5 text-primary-2 transition-colors hover:bg-primary-2/10"
                                            title="Mark as Paid"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <button
                                            v-if="participant.payment_status === 'paid'"
                                            type="button"
                                            @click="updateStatus(participant, 'pending')"
                                            class="rounded p-1.5 text-accent-2 transition-colors hover:bg-accent-2/10"
                                            title="Mark as Pending"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            @click="resendApprovalEmail(participant)"
                                            class="rounded p-1.5 text-accent-2 transition-colors hover:bg-accent-2/10"
                                            title="Resend Email"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            @click="openViewModal(participant)"
                                            class="rounded p-1.5 text-primary-2 transition-colors hover:bg-primary-2/10"
                                            title="View Record"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="!filteredParticipants.length" class="py-12 text-center">
                    <p class="font-body text-copy">No participants found</p>
                </div>
            </div>

            <div v-if="props.participants?.links?.length" class="rounded-lg border border-gray-200 bg-white px-4 py-3">
                <div class="flex flex-wrap gap-2">
                    <a
                        v-for="link in props.participants.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        class="rounded px-3 py-1 font-body text-sm"
                        :class="link.active ? 'bg-primary-1 text-white' : 'text-ink hover:bg-gray-100'"
                        v-html="link.label"
                    />
                </div>
            </div>

            <!-- View Modal -->
            <div
                v-if="viewModalOpen && selectedParticipant"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div class="w-full max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-lg">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="font-title text-xl font-bold text-primary-1">Participant Details</h2>
                        <button
                            type="button"
                            @click="viewModalOpen = false"
                            class="rounded p-1.5 text-accent-2 transition-colors hover:bg-accent-2/10"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Name:</div>
                            <div class="font-body text-primary-1">
                                {{ selectedParticipant.first_name }} {{ selectedParticipant.last_name }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Email:</div>
                            <div class="font-body text-primary-1">{{ selectedParticipant.email }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Organization:</div>
                            <div class="font-body text-primary-1">{{ selectedParticipant.organization }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Country:</div>
                            <div class="font-body text-primary-1">{{ selectedParticipant.country }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Payment Status:</div>
                            <div class="font-body text-primary-1">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="
                                        paymentStatusColors[selectedParticipant.payment_status] ||
                                        paymentStatusColors.pending
                                    "
                                >
                                    {{
                                        selectedParticipant.payment_status === 'paid'
                                            ? 'Paid'
                                            : selectedParticipant.payment_status.charAt(0).toUpperCase() +
                                              selectedParticipant.payment_status.slice(1)
                                    }}
                                </span>
                                <div v-if="selectedParticipant.payment_amount" class="mt-1 font-body text-xs text-copy">
                                    ${{ selectedParticipant.payment_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Package:</div>
                            <div class="font-body text-primary-1">
                                {{ selectedParticipant.price_tier }}
                                <div v-if="selectedParticipant.field_visit" class="font-body text-xs text-ink">
                                    + Field Visit
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="font-body font-medium text-primary-1">Application Date:</div>
                            <div class="font-body text-primary-1">
                                {{ formatDate(selectedParticipant.created_at) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Modal -->
            <div
                v-if="addModalOpen"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div class="w-full max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-lg max-h-[90vh] overflow-y-auto">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="font-title text-xl font-bold text-primary-1">Add Manual Registration</h2>
                        <button
                            type="button"
                            @click="addModalOpen = false; addForm.reset()"
                            class="rounded p-1.5 text-accent-2 transition-colors hover:bg-accent-2/10"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="handleAddParticipant" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">First Name:</label>
                            <input
                                v-model="addForm.first_name"
                                type="text"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.first_name" class="text-xs text-red-600">
                                {{ addForm.errors.first_name }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Last Name:</label>
                            <input
                                v-model="addForm.last_name"
                                type="text"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.last_name" class="text-xs text-red-600">
                                {{ addForm.errors.last_name }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Email:</label>
                            <input
                                v-model="addForm.email"
                                type="email"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.email" class="text-xs text-red-600">
                                {{ addForm.errors.email }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Organization:</label>
                            <input
                                v-model="addForm.organization"
                                type="text"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.organization" class="text-xs text-red-600">
                                {{ addForm.errors.organization }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Country:</label>
                            <input
                                v-model="addForm.country"
                                type="text"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.country" class="text-xs text-red-600">
                                {{ addForm.errors.country }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Payment Status:</label>
                            <select
                                v-model="addForm.payment_status"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            >
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                                <option value="partial">Partial</option>
                                <option value="refunded">Refunded</option>
                            </select>
                            <div v-if="addForm.errors.payment_status" class="text-xs text-red-600">
                                {{ addForm.errors.payment_status }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Payment Amount:</label>
                            <input
                                v-model="addForm.payment_amount"
                                type="number"
                                step="0.01"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.payment_amount" class="text-xs text-red-600">
                                {{ addForm.errors.payment_amount }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Package:</label>
                            <select
                                v-model="addForm.price_tier"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            >
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }} — USD {{ Number(pkg.price).toLocaleString() }}</option>
                            </select>
                            <div v-if="addForm.errors.price_tier" class="text-xs text-red-600">
                                {{ addForm.errors.price_tier }}
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-32 font-body font-medium text-primary-1">Field Visit:</label>
                            <input
                                v-model="addForm.field_visit"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-primary-2 focus:ring-primary-2 focus:ring-offset-0"
                            />
                        </div>
                        <div class="flex justify-end pt-4">
                            <button
                                type="submit"
                                :disabled="addForm.processing"
                                class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors disabled:cursor-not-allowed disabled:opacity-50 hover:bg-primary-2/90"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
