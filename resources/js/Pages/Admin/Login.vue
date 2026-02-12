<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('admin.authenticate'));
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-primary-1 to-primary-2 px-4">
        <div class="w-full max-w-md">
            <div class="rounded-2xl bg-white p-8 shadow-2xl">
                <div class="mb-8 text-center">
                    <img src="/brand/logo2.png" alt="Logo" class="mx-auto h-16 w-auto" />
                    <h1 class="mt-4 font-title text-2xl font-bold text-primary-1">Admin Portal</h1>
                    <p class="mt-2 font-body text-sm text-copy">Enter password to continue</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="password" class="block font-body text-sm font-medium text-ink">Password</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="mt-2 block w-full rounded-lg border border-primary-2/30 px-4 py-3 font-body text-ink placeholder-copy/50 focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            placeholder="Enter admin password"
                        />
                        <p v-if="form.errors.password" class="mt-2 font-body text-sm text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-accent-1 px-4 py-3 font-title font-semibold text-white transition hover:bg-accent-1/90 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Logging in...' : 'Login' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
