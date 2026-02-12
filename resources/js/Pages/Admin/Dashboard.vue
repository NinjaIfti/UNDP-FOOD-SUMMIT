<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Admin Dashboard" />
    <AdminLayout>
        <div class="mx-auto max-w-7xl">
            <h2 class="mb-6 font-title text-2xl font-bold text-primary-1">Dashboard</h2>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-accent-2 p-3">
                            <svg class="h-6 w-6 text-primary-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-copy">Homepage</p>
                            <p class="font-title text-2xl font-bold text-primary-1">Editable</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-accent-1 p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-copy">Admin</p>
                            <p class="font-title text-2xl font-bold text-primary-1">Active</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-primary-2 p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-copy">Pages</p>
                            <p class="font-title text-2xl font-bold text-primary-1">1</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-title text-lg font-bold text-primary-1">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <a
                        :href="route('admin.homepage')"
                        class="inline-flex items-center gap-2 rounded-lg bg-accent-1 px-6 py-3 font-title font-semibold text-white transition hover:bg-accent-1/90"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Homepage
                    </a>

                    <a
                        href="/"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-lg border-2 border-primary-1 px-6 py-3 font-title font-semibold text-primary-1 transition hover:bg-primary-1/5"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Website
                    </a>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
