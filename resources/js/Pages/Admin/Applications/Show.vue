<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    application: Object,
    packages: { type: Array, default: () => [] },
});

const priceForm = useForm({
    price_tier_raw: props.application?.price_tier_raw ?? props.application?.price_tier ?? '',
    admin_override_price: props.application?.admin_override_price ?? '',
});

const selectedTierPrice = computed(() => {
    const pkg = props.packages.find(p => String(p.id) === String(priceForm.price_tier_raw));
    return pkg ? Number(pkg.price) : null;
});

const effectivePrice = computed(() => {
    if (priceForm.admin_override_price !== '' && priceForm.admin_override_price !== null) {
        return Number(priceForm.admin_override_price);
    }
    return selectedTierPrice.value;
});

function savePrice() {
    priceForm.patch(route('admin.applications.update-price', props.application.id), {
        preserveScroll: true,
    });
}

function approve(id) {
    if (confirm('Approve this application? A user account will be created and an approval email with payment link will be sent to the applicant.')) {
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
                        <h1 class="font-title text-2xl font-bold text-primary-1">{{ [application.first_name, application.middle_name, application.last_name].filter(Boolean).join(' ') }}</h1>
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
                        <div><dt class="font-body text-xs text-gray-500">Name</dt><dd class="font-body text-ink">{{ [application.first_name, application.middle_name, application.last_name].filter(Boolean).join(' ') }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Gender</dt><dd class="font-body text-ink">{{ application.gender || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Email</dt><dd class="font-body text-ink">{{ application.email }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Phone</dt><dd class="font-body text-ink">{{ application.phone }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Nationality</dt><dd class="font-body text-ink">{{ application.country || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Country of residence</dt><dd class="font-body text-ink">{{ application.country_of_residence || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Organization</dt><dd class="font-body text-ink">{{ application.organization }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Organization Type</dt><dd class="font-body text-ink">{{ application.organization_type || '-' }}</dd></div>
                        <div v-if="application.organization_type === 'Other' || application.organization_type_other"><dt class="font-body text-xs text-gray-500">Organization Type (Other)</dt><dd class="font-body text-ink">{{ application.organization_type_other || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Organization Size</dt><dd class="font-body text-ink">{{ application.organization_size || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Participation supported by budget</dt><dd class="font-body text-ink">{{ application.participation_budget_support || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Job Title</dt><dd class="font-body text-ink">{{ application.job_title }}</dd></div>
                        <div v-if="application.website" class="sm:col-span-2"><dt class="font-body text-xs text-gray-500">Website</dt><dd class="font-body text-ink"><a :href="application.website" target="_blank" class="text-accent-1 hover:underline">{{ application.website }}</a></dd></div>
                    </dl>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Visa and Travel</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div><dt class="font-body text-xs text-gray-500">Passport Number</dt><dd class="font-body text-ink">{{ application.passport_number || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Passport Expiry</dt><dd class="font-body text-ink">{{ formatDate(application.passport_expiry) || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Arrival Date</dt><dd class="font-body text-ink">{{ formatDate(application.arrival_date) }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Departure Date</dt><dd class="font-body text-ink">{{ formatDate(application.departure_date) }}</dd></div>
                    </dl>
                </div>

                <!-- Admin price edit — only for pending / waitlisted -->
                <div
                    v-if="application.status === 'pending' || application.status === 'waitlisted'"
                    class="rounded-xl border-2 border-primary-2/30 bg-primary-2/5 p-6 shadow-sm"
                >
                    <div class="mb-4 flex items-center gap-2">
                        <svg class="h-5 w-5 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <h2 class="font-title text-lg font-semibold text-primary-1">Admin Price Override</h2>
                    </div>

                    <div v-if="$page.props.flash?.success" class="mb-3 rounded-lg bg-green-50 p-3">
                        <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block font-body text-sm font-medium text-ink">Package Tier</label>
                            <select
                                v-model="priceForm.price_tier_raw"
                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 font-body text-sm focus:border-primary-2 focus:outline-none focus:ring-2 focus:ring-primary-2/20"
                            >
                                <option value="">— Keep current —</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="String(pkg.id)">
                                    {{ pkg.name }} — USD {{ Number(pkg.price).toLocaleString() }}
                                </option>
                            </select>
                            <p v-if="selectedTierPrice !== null" class="mt-1 font-body text-xs text-copy">
                                Tier base price: USD {{ Number(selectedTierPrice).toLocaleString() }}
                            </p>
                        </div>

                        <div>
                            <label class="block font-body text-sm font-medium text-ink">
                                Custom Price Override (USD)
                            </label>
                            <input
                                v-model="priceForm.admin_override_price"
                                type="number"
                                min="0"
                                step="0.01"
                                placeholder="Leave blank to use tier price"
                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 font-body text-sm focus:border-primary-2 focus:outline-none focus:ring-2 focus:ring-primary-2/20"
                            />
                            <p class="mt-1 font-body text-xs text-copy">Overrides the tier price when set. Used when creating the user in DashTicket on approval.</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div v-if="effectivePrice !== null" class="font-body text-sm font-semibold text-primary-1">
                            Price sent to DashTicket on approval:
                            <span class="text-primary-2">USD {{ Number(effectivePrice).toLocaleString() }}</span>
                        </div>
                        <div v-else class="font-body text-sm text-copy">
                            No price set — DashTicket will use the default tier price.
                        </div>
                        <button
                            type="button"
                            :disabled="priceForm.processing"
                            @click="savePrice"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-semibold text-white transition hover:bg-primary-2/90 disabled:opacity-50"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Price
                        </button>
                    </div>
                    <p v-if="priceForm.errors.admin_override_price" class="mt-2 font-body text-xs text-red-600">{{ priceForm.errors.admin_override_price }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Participation</h2>
                    <dl class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div><dt class="font-body text-xs text-gray-500">Price Tier</dt><dd class="font-body text-ink">{{ application.price_tier_display || application.price_tier_name || application.price_tier }}<span v-if="application.price_tier_amount !== null"> — USD {{ Number(application.price_tier_amount).toLocaleString() }}</span></dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Selected Ticket ID</dt><dd class="font-body text-ink">{{ application.price_tier_raw || application.price_tier || '-' }}</dd></div>
                        <div v-if="application.admin_override_price"><dt class="font-body text-xs text-gray-500">Admin Price Override</dt><dd class="font-body font-semibold text-primary-2">USD {{ Number(application.admin_override_price).toLocaleString() }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Ticket Confirmation</dt><dd class="font-body text-ink">{{ application.confirm_tier ? 'Agreed' : 'Not agreed' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Flexible Amount (USD)</dt><dd class="font-body text-ink">{{ application.flexible_support_amount || '-' }}</dd></div>
                        <div class="sm:col-span-2"><dt class="font-body text-xs text-gray-500">Subsidised Justification</dt><dd class="font-body text-ink whitespace-pre-line">{{ application.subsidized_price_justification || '-' }}</dd></div>
                        <div class="sm:col-span-2"><dt class="font-body text-xs text-gray-500">How did you hear about this event?</dt><dd class="font-body text-ink">{{ Array.isArray(application.heard_from) && application.heard_from.length ? application.heard_from.join(', ') : '-' }}<span v-if="application.heard_from_other"> (Other: {{ application.heard_from_other }})</span></dd></div>
                        <div><dt class="font-body text-xs text-gray-500">CoFSA Member</dt><dd class="font-body text-ink">{{ application.cofsa_member || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">CoFSA Newsletter</dt><dd class="font-body text-ink">{{ application.cofsa_newsletter || '-' }}</dd></div>
                        <div><dt class="font-body text-xs text-gray-500">Optional Immersive Experience (Add-on)</dt><dd class="font-body text-ink">{{ application.field_visit ? 'Yes' : 'No' }}</dd></div>
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

                <div v-if="application.photo || application.photo_url" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-title text-lg font-semibold text-primary-1">Photo / Document</h2>
                    <div class="mt-4 flex flex-col gap-2">
                        <img :src="application.photo_url || application.photo" alt="Applicant photo" class="h-40 w-32 rounded-lg object-cover border border-gray-200" />
                        <a :href="application.photo_url || application.photo" target="_blank" rel="noopener noreferrer" class="text-sm text-accent-1 hover:underline">Open full size in new tab</a>
                    </div>
                </div>

                <p class="font-body text-xs text-gray-500">Submitted {{ formatDate(application.created_at) }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
