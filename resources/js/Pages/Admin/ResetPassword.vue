<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: { type: String, required: true },
    token: { type: String, required: true },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password" />
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-primary-1 to-primary-2 px-4">
        <div class="w-full max-w-md">
            <div class="rounded-2xl bg-white p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <img src="/brand/logo2.png" alt="Logo" class="mx-auto h-16 w-auto" />
                    <h1 class="mt-4 font-title text-2xl font-bold text-primary-1">Reset Password</h1>
                    <p class="mt-2 font-body text-sm text-copy">Enter your new password below.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="email" class="block font-body text-sm font-medium text-ink">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="mt-2 block w-full rounded-lg border border-primary-2/30 px-4 py-3 font-body text-ink placeholder-copy/50 focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                        />
                        <p v-if="form.errors.email" class="mt-2 font-body text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label for="password" class="block font-body text-sm font-medium text-ink">New Password</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="mt-2 block w-full rounded-lg border border-primary-2/30 px-4 py-3 font-body text-ink placeholder-copy/50 focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            placeholder="Enter new password"
                        />
                        <p v-if="form.errors.password" class="mt-2 font-body text-sm text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-body text-sm font-medium text-ink">Confirm Password</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="mt-2 block w-full rounded-lg border border-primary-2/30 px-4 py-3 font-body text-ink placeholder-copy/50 focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            placeholder="Confirm new password"
                        />
                        <p v-if="form.errors.password_confirmation" class="mt-2 font-body text-sm text-red-600">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-accent-1 px-4 py-3 font-title font-semibold text-white transition hover:bg-accent-1/90 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Resetting...' : 'Reset Password' }}
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
