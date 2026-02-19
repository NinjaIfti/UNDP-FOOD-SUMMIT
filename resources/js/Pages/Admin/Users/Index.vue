<script setup>
import { computed, nextTick, onMounted, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            super_admins: 0,
            cms_admins: 0,
        }),
    },
});

const searchQuery = ref('');
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedUser = ref(null);
const openDropdown = ref(null);
const dropdownPositions = ref({});

const permissionLabels = [
    { key: 'homepage', label: 'Homepage' },
    { key: 'programme-experience', label: 'Programme/Experience' },
    { key: 'your-bhutan-stay', label: 'Your Bhutan Stay' },
    { key: 'get-involved', label: 'Get Involved' },
    { key: 'faqs', label: 'FAQs' },
    { key: 'blog', label: 'Blog' },
    { key: 'applications', label: 'Applications' },
    { key: 'payments', label: 'Payments' },
    { key: 'packages', label: 'Packages' },
    { key: 'communications', label: 'Communications' },
    { key: 'users', label: 'Users' },
    { key: 'settings', label: 'Settings' },
];

const addForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'cms-admin',
    permissions: {},
});

const editForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'cms-admin',
    permissions: {},
});

onMounted(() => {
    permissionLabels.forEach((p) => {
        if (!addForm.permissions[p.key]) {
            addForm.permissions[p.key] = p.key !== 'users' && p.key !== 'settings';
        }
    });
});

const filteredUsers = computed(() => {
    if (!props.users?.data) return [];

    const query = searchQuery.value.toLowerCase().trim();
    if (!query) return props.users.data;

    return props.users.data.filter((user) => {
        const name = (user.name || '').toLowerCase();
        const email = (user.email || '').toLowerCase();
        const role = (user.role || '').toLowerCase();
        return name.includes(query) || email.includes(query) || role.includes(query);
    });
});

const togglePermission = (form, key) => {
    form.permissions[key] = !form.permissions[key];
};

const handleAddUser = () => {
    addForm.post(route('admin.users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
            addForm.permissions = {};
            permissionLabels.forEach((p) => {
                addForm.permissions[p.key] = p.key !== 'users' && p.key !== 'settings';
            });
        },
    });
};

const handleEditUser = () => {
    if (!selectedUser.value) return;

    editForm.put(route('admin.users.update', selectedUser.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            selectedUser.value = null;
            editForm.reset();
        },
    });
};

const handleDeleteUser = (userId) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.destroy', userId), {
            preserveScroll: true,
        });
        openDropdown.value = null;
    }
};

const toggleDropdown = (userId, event) => {
    if (openDropdown.value === userId) {
        openDropdown.value = null;
    } else {
        openDropdown.value = userId;
        nextTick(() => {
            const button = event.target.closest('button');
            if (button) {
                const rect = button.getBoundingClientRect();
                dropdownPositions.value[userId] = {
                    top: rect.bottom + 8,
                    right: window.innerWidth - rect.right,
                };
            }
        });
    }
};

const openEditModal = (user) => {
    selectedUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.password = '';
    editForm.password_confirmation = '';
    editForm.role = user.role_name || 'cms-admin';
    editForm.permissions = { ...user.permissions };
    showEditModal.value = true;
    openDropdown.value = null;
};

const getRoleColor = (role) => {
    return role === 'Super Admin'
        ? 'bg-accent-2/10 text-accent-2'
        : 'bg-primary-2/10 text-primary-2';
};

const closeAddModal = () => {
    showAddModal.value = false;
    addForm.reset();
    addForm.permissions = {};
    permissionLabels.forEach((p) => {
        addForm.permissions[p.key] = p.key !== 'users' && p.key !== 'settings';
    });
};

const closeEditModal = () => {
    showEditModal.value = false;
    selectedUser.value = null;
    editForm.reset();
};
</script>

<template>
    <Head title="Users" />
    <AdminLayout>
        <div class="mx-auto max-w-6xl space-y-6 overflow-visible">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="mb-2 font-title text-2xl font-bold text-primary-1">Users</h1>
                    <p class="font-body text-sm text-copy">
                        Manage admin users and their role-based access
                    </p>
                </div>
                <button
                    type="button"
                    @click="showAddModal = true"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors hover:bg-primary-2/90"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </button>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 p-3">
                <p class="font-body text-sm text-green-800">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 p-3">
                <p class="font-body text-sm text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-2/10">
                            <svg class="h-6 w-6 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A4 4 0 019 16h6a4 4 0 013.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-copy">Total Users</p>
                            <p class="font-title text-2xl font-bold text-primary-1">{{ stats?.total ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-accent-2/10">
                            <svg class="h-6 w-6 text-accent-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-copy">Super Admins</p>
                            <p class="font-title text-2xl font-bold text-primary-1">
                                {{ stats?.super_admins ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-2/10">
                            <svg class="h-6 w-6 text-primary-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="font-body text-sm text-copy">CMS Admins</p>
                            <p class="font-title text-2xl font-bold text-primary-1">
                                {{ stats?.cms_admins ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search users by name, email, or role..."
                        class="block w-full rounded-full border border-gray-300 py-2 pl-10 pr-4 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
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
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-visible">
                <div class="overflow-x-auto overflow-y-visible">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Created
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-copy">
                                    Last Login
                                </th>
                                <th class="sticky right-0 bg-gray-50 px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-copy">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="user in filteredUsers"
                                :key="user.id"
                                class="transition-colors hover:bg-gray-50 [&>td:last-child]:bg-white [&:hover>td:last-child]:bg-gray-50"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary-2 to-primary-2/80"
                                        >
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5.121 17.804A4 4 0 019 16h6a4 4 0 013.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                            </svg>
                                        </div>
                                        <span class="font-title font-medium text-primary-1">{{ user.name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-2 font-body text-sm text-copy">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                        {{ user.email }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium"
                                        :class="getRoleColor(user.role)"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                            />
                                        </svg>
                                        {{ user.role }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                                        {{ user.status }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-body text-sm text-copy">
                                    {{ user.created_at }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-body text-sm text-copy">
                                    {{ user.last_login }}
                                </td>
                                <td class="sticky right-0 whitespace-nowrap bg-white px-6 py-4 text-right">
                                    <div class="relative inline-block">
                                        <button
                                            type="button"
                                            @click="toggleDropdown(user.id, $event)"
                                            class="rounded-lg p-2 transition-colors hover:bg-gray-100"
                                        >
                                            <svg class="h-5 w-5 text-copy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                                                />
                                            </svg>
                                        </button>

                                        <Teleport to="body">
                                            <div
                                                v-if="openDropdown === user.id"
                                                class="fixed z-[100] w-48 rounded-lg border border-gray-200 bg-white shadow-lg"
                                                :style="dropdownPositions[user.id] ? `top: ${dropdownPositions[user.id].top}px; right: ${dropdownPositions[user.id].right}px;` : ''"
                                            >
                                            <button
                                                type="button"
                                                @click="openEditModal(user)"
                                                class="flex w-full items-center gap-2 rounded-t-lg px-4 py-2 text-sm text-primary-1 transition-colors hover:bg-gray-50"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    />
                                                </svg>
                                                Edit User
                                            </button>
                                            <button
                                                type="button"
                                                @click="handleDeleteUser(user.id)"
                                                class="flex w-full items-center gap-2 rounded-b-lg px-4 py-2 text-sm text-red-600 transition-colors hover:bg-gray-50"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    />
                                                </svg>
                                                Delete User
                                            </button>
                                            </div>
                                        </Teleport>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="!filteredUsers.length" class="py-12 text-center">
                    <svg
                        class="mx-auto mb-4 h-12 w-12 text-copy"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5.121 17.804A4 4 0 019 16h6a4 4 0 013.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p class="font-body text-copy">No users found</p>
                </div>
            </div>

            <!-- Add User Modal -->
            <div
                v-if="showAddModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div class="w-full max-w-md rounded-lg border border-gray-200 bg-white shadow-lg max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4">
                        <h2 class="font-title text-lg font-bold text-primary-1">Add New User</h2>
                        <button type="button" @click="closeAddModal" class="rounded-lg p-2 transition-colors hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="handleAddUser" class="space-y-3 p-4">
                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Full Name
                            </label>
                            <input
                                v-model="addForm.name"
                                type="text"
                                placeholder="Enter full name..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.name" class="mt-1 text-xs text-red-600">
                                {{ addForm.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Email Address
                            </label>
                            <input
                                v-model="addForm.email"
                                type="email"
                                placeholder="Enter email address..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.email" class="mt-1 text-xs text-red-600">
                                {{ addForm.errors.email }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Password
                            </label>
                            <input
                                v-model="addForm.password"
                                type="password"
                                placeholder="Enter password..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="addForm.errors.password" class="mt-1 text-xs text-red-600">
                                {{ addForm.errors.password }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Confirm Password
                            </label>
                            <input
                                v-model="addForm.password_confirmation"
                                type="password"
                                placeholder="Confirm password..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Role
                            </label>
                            <select
                                v-model="addForm.role"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            >
                                <option value="cms-admin">CMS Admin</option>
                                <option value="super-admin">Super Admin</option>
                            </select>
                            <div v-if="addForm.errors.role" class="mt-1 text-xs text-red-600">
                                {{ addForm.errors.role }}
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <p class="mb-2 font-body text-sm font-medium text-primary-1">Page Access Permissions:</p>
                            <div class="max-h-32 space-y-1 overflow-y-auto">
                                <label
                                    v-for="permission in permissionLabels"
                                    :key="permission.key"
                                    class="flex cursor-pointer items-center gap-2 rounded p-1.5 transition-colors hover:bg-white/50"
                                >
                                    <input
                                        v-model="addForm.permissions[permission.key]"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-primary-2 focus:ring-primary-2 focus:ring-offset-0"
                                    />
                                    <span class="font-body text-sm text-primary-1">{{ permission.label }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3 border-t border-gray-200 pt-4">
                            <button
                                type="button"
                                @click="closeAddModal"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 font-body text-sm transition-colors hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="addForm.processing || !addForm.name || !addForm.email || !addForm.password"
                                class="flex-1 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors disabled:cursor-not-allowed disabled:opacity-50 hover:bg-primary-2/90"
                            >
                                Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div
                v-if="showEditModal && selectedUser"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            >
                <div class="w-full max-w-md rounded-lg border border-gray-200 bg-white shadow-lg max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4">
                        <h2 class="font-title text-lg font-bold text-primary-1">Edit User</h2>
                        <button type="button" @click="closeEditModal" class="rounded-lg p-2 transition-colors hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="handleEditUser" class="space-y-3 p-4">
                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Full Name
                            </label>
                            <input
                                v-model="editForm.name"
                                type="text"
                                placeholder="Enter full name..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="editForm.errors.name" class="mt-1 text-xs text-red-600">
                                {{ editForm.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Email Address
                            </label>
                            <input
                                v-model="editForm.email"
                                type="email"
                                placeholder="Enter email address..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <div v-if="editForm.errors.email" class="mt-1 text-xs text-red-600">
                                {{ editForm.errors.email }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Role
                            </label>
                            <select
                                v-model="editForm.role"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            >
                                <option value="cms-admin">CMS Admin</option>
                                <option value="super-admin">Super Admin</option>
                            </select>
                            <div v-if="editForm.errors.role" class="mt-1 text-xs text-red-600">
                                {{ editForm.errors.role }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-body text-sm font-medium text-primary-1">
                                Change Password (Optional)
                            </label>
                            <input
                                v-model="editForm.password"
                                type="password"
                                placeholder="Enter new password..."
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 font-body text-sm focus:border-accent-1 focus:outline-none focus:ring-2 focus:ring-accent-1/20"
                            />
                            <p class="mt-1 font-body text-xs text-copy">
                                Leave blank to keep current password
                            </p>
                            <div v-if="editForm.errors.password" class="mt-1 text-xs text-red-600">
                                {{ editForm.errors.password }}
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <p class="mb-2 font-body text-sm font-medium text-primary-1">Page Access Permissions:</p>
                            <div class="max-h-32 space-y-1 overflow-y-auto">
                                <label
                                    v-for="permission in permissionLabels"
                                    :key="permission.key"
                                    class="flex cursor-pointer items-center gap-2 rounded p-1.5 transition-colors hover:bg-white/50"
                                >
                                    <input
                                        v-model="editForm.permissions[permission.key]"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-primary-2 focus:ring-primary-2 focus:ring-offset-0"
                                    />
                                    <span class="font-body text-sm text-primary-1">{{ permission.label }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3 border-t border-gray-200 pt-4">
                            <button
                                type="button"
                                @click="closeEditModal"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 font-body text-sm transition-colors hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="editForm.processing || !editForm.name || !editForm.email"
                                class="flex-1 rounded-lg bg-primary-2 px-4 py-2 font-body text-sm font-medium text-white transition-colors disabled:cursor-not-allowed disabled:opacity-50 hover:bg-primary-2/90"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Click outside to close dropdown -->
            <div
                v-if="openDropdown"
                class="fixed inset-0 z-40"
                @click="openDropdown = null"
            />
        </div>
    </AdminLayout>
</template>
