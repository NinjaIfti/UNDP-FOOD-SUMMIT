<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
    show: { type: Boolean, default: false },
});

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/register', {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
        onError: () => {
            form.reset('password', 'password_confirmation');
        },
    });
}
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center px-4" @click.self="emit('close')">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')" />
                <div class="relative w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl">
                    <button
                        type="button"
                        class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full text-copy transition hover:bg-gray-100"
                        @click="emit('close')"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h2 class="font-title text-2xl font-bold text-primary-1">Register</h2>
                    <p class="mt-1 font-body text-sm text-copy">Create your account for the Summit</p>
                    <form @submit.prevent="submit" class="mt-6 space-y-4">
                        <div>
                            <label for="reg-name" class="mb-1 block font-title text-sm font-medium text-primary-1">Full Name</label>
                            <input
                                id="reg-name"
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 font-body text-primary-1 focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                placeholder="Your full name"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label for="reg-email" class="mb-1 block font-title text-sm font-medium text-primary-1">Email</label>
                            <input
                                id="reg-email"
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 font-body text-primary-1 focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                placeholder="you@example.com"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label for="reg-password" class="mb-1 block font-title text-sm font-medium text-primary-1">Password</label>
                            <input
                                id="reg-password"
                                v-model="form.password"
                                type="password"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 font-body text-primary-1 focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                placeholder="Create a password"
                            />
                            <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">{{ form.errors.password }}</p>
                        </div>
                        <div>
                            <label for="reg-password-confirm" class="mb-1 block font-title text-sm font-medium text-primary-1">Confirm Password</label>
                            <input
                                id="reg-password-confirm"
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 font-body text-primary-1 focus:border-accent-2 focus:outline-none focus:ring-1 focus:ring-accent-2"
                                placeholder="Confirm your password"
                            />
                            <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-500">{{ form.errors.password_confirmation }}</p>
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-lg bg-accent-2 py-3 font-title font-semibold text-primary-1 transition hover:bg-accent-2/90 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Registering...' : 'Register Now' }}
                        </button>
                    </form>
                    <p class="mt-4 text-center font-body text-sm text-copy">
                        Already have an account?
                        <a href="/login" class="font-semibold text-accent-1 hover:underline">Log in</a>
                    </p>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.25s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
