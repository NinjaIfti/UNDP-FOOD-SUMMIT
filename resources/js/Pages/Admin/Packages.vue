<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    tiers: {
        type: Array,
        default: () => [],
    },
});

const editingTier = ref(null);
const isAddingTier = ref(false);

const tierForm = useForm({
    name: '',
    description: '',
    price: '',
});

const toggleTier = (tierId) => {
    router.post(route('admin.packages.toggle', tierId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Refresh will happen via Inertia
        },
    });
};

const handleAddTier = () => {
    if (!tierForm.name.trim() || !tierForm.description.trim() || !tierForm.price) return;

    tierForm.post(route('admin.packages.store'), {
        preserveScroll: true,
        onSuccess: () => {
            tierForm.reset();
            isAddingTier.value = false;
        },
    });
};

const handleEditTier = (tier) => {
    editingTier.value = tier.id;
    tierForm.name = tier.name;
    tierForm.description = tier.description;
    tierForm.price = tier.price.toString();
};

const handleUpdateTier = () => {
    if (!tierForm.name.trim() || !tierForm.description.trim() || !tierForm.price || !editingTier.value) return;

    tierForm.put(route('admin.packages.update', editingTier.value), {
        preserveScroll: true,
        onSuccess: () => {
            editingTier.value = null;
            tierForm.reset();
        },
    });
};

const handleDeleteTier = (id) => {
    if (confirm('Are you sure you want to delete this package tier?')) {
        router.delete(route('admin.packages.destroy', id), {
            preserveScroll: true,
        });
    }
};

const cancelTierEdit = () => {
    isAddingTier.value = false;
    editingTier.value = null;
    tierForm.reset();
};
</script>

<template>
    <Head title="Package & Ticketing Management" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl space-y-8">
            <div>
                <h1 class="mb-2 font-title text-3xl font-bold text-primary-1">
                    Package & Ticketing Management
                </h1>
                <p class="font-body text-sm text-copy">
                    Manage price tiers and package options for summit participants
                </p>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-title text-xl font-bold text-primary-1">Price Tiers</h2>
                    <button
                        type="button"
                        @click="isAddingTier = true"
                        :disabled="isAddingTier || editingTier !== null"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors disabled:cursor-not-allowed disabled:opacity-50 hover:bg-primary-2/90"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Package Tier
                    </button>
                </div>

                <div
                    v-if="isAddingTier || editingTier"
                    class="mb-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm"
                >
                    <h3 class="mb-4 font-title text-lg font-semibold text-primary-1">
                        {{ editingTier ? 'Edit Package Tier' : 'Add New Package Tier' }}
                    </h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="tierForm.name"
                                type="text"
                                placeholder="e.g., Standard Package"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="tierForm.errors.name" class="mt-1 text-xs text-red-600">
                                {{ tierForm.errors.name }}
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                v-model="tierForm.description"
                                placeholder="Enter package description..."
                                rows="3"
                                class="block w-full resize-none rounded-lg border border-gray-300 px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="tierForm.errors.description" class="mt-1 text-xs text-red-600">
                                {{ tierForm.errors.description }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Price (USD) <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="tierForm.price"
                                type="number"
                                placeholder="1500"
                                min="0"
                                step="0.01"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="tierForm.errors.price" class="mt-1 text-xs text-red-600">
                                {{ tierForm.errors.price }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            @click="editingTier ? handleUpdateTier() : handleAddTier()"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors hover:bg-primary-2/90"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ editingTier ? 'Update Package' : 'Save Package' }}
                        </button>
                        <button
                            type="button"
                            @click="cancelTierEdit"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 font-body text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div
                        v-for="tier in tiers"
                        :key="tier.id"
                        :class="[
                            'rounded-xl border-2 p-6 shadow-sm transition-all',
                            tier.active
                                ? 'border-primary-2 shadow-md'
                                : 'border-gray-200 opacity-60',
                        ]"
                    >
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-primary-2 to-primary-2/80">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <button
                                type="button"
                                @click="toggleTier(tier.id)"
                                :class="[
                                    'rounded-full px-3 py-1 text-xs font-medium transition-colors',
                                    tier.active
                                        ? 'bg-primary-2 text-white'
                                        : 'bg-gray-100 text-gray-600',
                                ]"
                            >
                                {{ tier.active ? 'Active' : 'Inactive' }}
                            </button>
                        </div>
                        <h3 class="mb-2 font-title text-lg font-bold text-primary-1">
                            {{ tier.name }}
                        </h3>
                        <div
                            class="mb-4 font-body text-sm text-copy [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mb-1 [&_p]:mb-2 [&_p]:last:mb-0 [&_strong]:font-bold [&_b]:font-bold [&_em]:italic [&_i]:italic [&_u]:underline"
                            v-html="tier.description"
                        />
                        <div class="mb-4 flex items-baseline gap-1">
                            <span class="font-title text-3xl font-bold text-accent-2">
                                ${{ tier.price }}
                            </span>
                            <span class="font-body text-sm text-copy">USD</span>
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                @click="handleEditTier(tier)"
                                :disabled="isAddingTier || editingTier !== null"
                                class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2 font-body text-sm transition-colors disabled:cursor-not-allowed disabled:opacity-50 hover:bg-gray-50"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                            <button
                                type="button"
                                @click="handleDeleteTier(tier.id)"
                                :disabled="isAddingTier || editingTier !== null"
                                class="rounded-lg border border-red-200 px-3 py-2 text-red-600 transition-colors disabled:cursor-not-allowed disabled:opacity-50 hover:bg-red-50"
                                title="Delete package"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
