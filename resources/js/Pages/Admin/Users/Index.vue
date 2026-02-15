<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    users: Object,
});
</script>

<template>
    <Head title="Users" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl">
            <div class="mb-6">
                <h1 class="font-title text-2xl font-bold text-primary-1 sm:text-3xl">Users</h1>
                <p class="mt-1 font-body text-sm text-copy">Approved applicants with user accounts</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="divide-y divide-gray-200">
                    <div
                        v-for="user in users?.data"
                        :key="user.id"
                        class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="min-w-0 flex-1">
                            <h3 class="font-title font-semibold text-primary-1">{{ user.name }}</h3>
                            <p class="font-body text-sm text-copy">{{ user.email }}</p>
                            <p v-if="user.summit_registration" class="font-body text-xs text-gray-500">
                                {{ user.summit_registration.organization }} · {{ user.summit_registration.price_tier }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-500">
                            Account created {{ user.created_at ? new Date(user.created_at).toLocaleDateString() : '' }}
                        </div>
                    </div>
                    <div v-if="!users?.data?.length" class="p-8 text-center">
                        <p class="font-body text-copy">No users yet. Approve applications to create user accounts.</p>
                        <Link :href="route('admin.applications.index')" class="mt-2 inline-block font-body text-sm font-medium text-accent-1 hover:underline">
                            Go to Applications
                        </Link>
                    </div>
                </div>
                <div v-if="users?.links?.length" class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="link in users.links"
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
