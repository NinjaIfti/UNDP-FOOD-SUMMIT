<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('admin.password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-primary-1 to-primary-2 px-4">
        <div class="w-full max-w-md">
            <div class="rounded-2xl bg-white p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <img src="/brand/logo2.png" alt="Logo" class="mx-auto h-16 w-auto" />
                    <h1 class="mt-4 font-title text-2xl font-bold text-primary-1">Forgot Password</h1>
                    <p class="mt-2 font-body text-sm text-copy">Enter your admin email and we'll send you a password reset link.</p>
                </div>

                <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-3 font-body text-sm text-green-700">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="email" class="block font-body text-sm font-medium text-ink">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            autofocus
                            autocomplete="email"
                            class="mt-2 block w-full rounded-lg border border-primary-2/30 px-4 py-3 font-body text-ink placeholder-copy/50 focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            placeholder="admin@gcfs.com"
                        />
                        <p v-if="form.errors.email" class="mt-2 font-body text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-accent-1 px-4 py-3 font-title font-semibold text-white transition hover:bg-accent-1/90 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Sending...' : 'Email Password Reset Link' }}
                    </button>
                </form>

                <p class="mt-6 text-center">
                    <Link :href="route('admin.login')" class="font-body text-sm text-accent-1 hover:underline">
                        Back to login
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
