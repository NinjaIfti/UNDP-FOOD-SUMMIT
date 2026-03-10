<script setup>
import { ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
});

const logoForm = useForm({
    logo: null,
    delete_logo: false,
});

const logoSecondaryForm = useForm({
    logo_secondary: null,
    delete_logo_secondary: false,
});

const faviconForm = useForm({
    favicon: null,
    delete_favicon: false,
});

const footerForm = useForm({
    newsletter_text: props.settings.newsletter_text || '',
    contact_email: props.settings.contact_email || '',
    contact_phone: props.settings.contact_phone || '',
    contact_location: props.settings.contact_location || '',
    social_facebook: props.settings.social_facebook || '',
    social_twitter: props.settings.social_twitter || '',
    social_instagram: props.settings.social_instagram || '',
    social_linkedin: props.settings.social_linkedin || '',
    social_youtube: props.settings.social_youtube || '',
    footer_support_text: props.settings.footer_support_text || '',
});

const logoPreview = ref(props.settings.logo || '');
const logoSecondaryPreview = ref(props.settings.logo_secondary || '');
const faviconPreview = ref(props.settings.favicon || '');

watch(() => props.settings.logo, (newLogo) => {
    if (newLogo) logoPreview.value = newLogo;
    else if (!logoForm.logo) logoPreview.value = '';
});
watch(() => props.settings.logo_secondary, (v) => {
    if (v) logoSecondaryPreview.value = v;
    else if (!logoSecondaryForm.logo_secondary) logoSecondaryPreview.value = '';
});
watch(() => props.settings.favicon, (newFavicon) => {
    if (newFavicon) faviconPreview.value = newFavicon;
    else if (!faviconForm.favicon) faviconPreview.value = '';
});

const handleLogoUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        logoForm.logo = file;
        const reader = new FileReader();
        reader.onloadend = () => { logoPreview.value = reader.result; };
        reader.readAsDataURL(file);
    }
};
const handleLogoSecondaryUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        logoSecondaryForm.logo_secondary = file;
        const reader = new FileReader();
        reader.onloadend = () => { logoSecondaryPreview.value = reader.result; };
        reader.readAsDataURL(file);
    }
};
const handleFaviconUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        faviconForm.favicon = file;
        const reader = new FileReader();
        reader.onloadend = () => { faviconPreview.value = reader.result; };
        reader.readAsDataURL(file);
    }
};

const removeLogo = () => {
    logoForm.logo = null;
    logoForm.delete_logo = true;
    logoPreview.value = '';
};
const removeLogoSecondary = () => {
    logoSecondaryForm.logo_secondary = null;
    logoSecondaryForm.delete_logo_secondary = true;
    logoSecondaryPreview.value = '';
};
const removeFavicon = () => {
    faviconForm.favicon = null;
    faviconForm.delete_favicon = true;
    faviconPreview.value = '';
};

const saveLogo = () => {
    if (!logoForm.logo && !logoForm.delete_logo) return;
    
    logoForm.post(route('admin.settings.update-general'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (page) => {
            if (page.props.settings?.logo) {
                logoPreview.value = page.props.settings.logo;
            } else {
                logoPreview.value = '';
            }
            logoForm.reset();
            logoForm.delete_logo = false;
        },
    });
};
const saveLogoSecondary = () => {
    if (!logoSecondaryForm.logo_secondary && !logoSecondaryForm.delete_logo_secondary) return;
    
    logoSecondaryForm.post(route('admin.settings.update-general'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (page) => {
            if (page.props.settings?.logo_secondary) {
                logoSecondaryPreview.value = page.props.settings.logo_secondary;
            } else {
                logoSecondaryPreview.value = '';
            }
            logoSecondaryForm.reset();
            logoSecondaryForm.delete_logo_secondary = false;
        },
    });
};
const saveFavicon = () => {
    if (!faviconForm.favicon && !faviconForm.delete_favicon) return;
    
    faviconForm.post(route('admin.settings.update-general'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (page) => {
            if (page.props.settings?.favicon) {
                faviconPreview.value = page.props.settings.favicon;
            } else {
                faviconPreview.value = '';
            }
            faviconForm.reset();
            faviconForm.delete_favicon = false;
        },
        onError: (errors) => {
            console.error('Favicon save error:', errors);
        },
    });
};

const saveFooter = (fields) => {
    router.post(route('admin.settings.update-footer'), fields, { preserveScroll: true });
};
</script>

<template>
    <Head title="Settings" />

    <AdminLayout>
        <div class="space-y-6">
            <!-- Page Header -->
            <div>
                <h1 class="text-2xl font-bold text-foreground mb-2">Settings</h1>
                <p class="text-muted-foreground">
                    Manage system settings, security, and data
                </p>
            </div>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="faviconForm.errors.favicon" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                Favicon error: {{ faviconForm.errors.favicon }}
            </div>
            <div v-if="logoForm.errors.logo" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                Logo error: {{ logoForm.errors.logo }}
            </div>
            <div v-if="logoSecondaryForm.errors.logo_secondary" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                Logo 2 error: {{ logoSecondaryForm.errors.logo_secondary }}
            </div>

            <!-- General Settings -->
            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-6 h-6 text-[#2d5f3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-foreground">
                        General Settings
                    </h2>
                </div>

                <div class="space-y-6">
                    <!-- Logo Upload -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#2d5f3f]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#2d5f3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 mb-1">
                                    Navbar Logo 1 (Main Summit Logo)
                                </h3>
                                <p class="text-sm text-gray-500 mb-3">
                                    Main logo displayed in the navbar (recommended size: 200x60px, PNG or SVG)
                                </p>
                                
                                <div v-if="logoPreview" class="mb-3 p-3 border border-border rounded-lg bg-white inline-block">
                                    <img :src="logoPreview" alt="Logo preview" class="h-12 max-w-xs object-contain" />
                                </div>

                                <div class="flex gap-2">
                                    <label class="px-4 py-2 text-sm border border-border rounded-lg hover:bg-muted transition-colors cursor-pointer flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Upload Logo
                                        <input
                                            type="file"
                                            accept="image/png,image/svg+xml,image/jpeg"
                                            @change="handleLogoUpload"
                                            class="hidden"
                                        />
                                    </label>
                                    <button
                                        v-if="logoPreview"
                                        @click="removeLogo"
                                        class="px-4 py-2 text-sm border border-border rounded-lg hover:bg-muted transition-colors"
                                    >
                                        Remove
                                    </button>
                                    <button
                                        v-if="logoForm.logo || logoForm.delete_logo"
                                        @click="saveLogo"
                                        :disabled="logoForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ logoForm.processing ? 'Saving...' : 'Save Logo' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navbar Logo 2 (secondary / partner) -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4a7c59]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#4a7c59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 mb-1">Navbar Logo 2 (Partner Logo)</h3>
                                <p class="text-sm text-gray-500 mb-3">
                                    Second logo displayed in the navbar (e.g. Government of Bhutan, recommended 200x60px)
                                </p>
                                <div v-if="logoSecondaryPreview" class="mb-3 p-3 border border-gray-200 rounded-lg bg-white inline-block">
                                    <img :src="logoSecondaryPreview" alt="Logo 2 preview" class="h-12 max-w-xs object-contain" />
                                </div>
                                <div class="flex gap-2">
                                    <label class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                        Upload Logo 2
                                        <input type="file" accept="image/png,image/svg+xml,image/jpeg" @change="handleLogoSecondaryUpload" class="hidden" />
                                    </label>
                                    <button v-if="logoSecondaryPreview" @click="removeLogoSecondary" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Remove</button>
                                    <button v-if="logoSecondaryForm.logo_secondary || logoSecondaryForm.delete_logo_secondary" @click="saveLogoSecondary" :disabled="logoSecondaryForm.processing" class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] disabled:opacity-50">{{ logoSecondaryForm.processing ? 'Saving...' : 'Save Logo 2' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Upload -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4a7c59]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#4a7c59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">
                                    Favicon
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Upload your favicon icon (recommended size: 32x32px or 64x64px, PNG or ICO)
                                </p>
                                
                                <div v-if="faviconPreview" class="mb-3 p-3 border border-border rounded-lg bg-white inline-block">
                                    <img :src="faviconPreview" alt="Favicon preview" class="h-8 w-8 object-contain" />
                                </div>

                                <div class="flex gap-2">
                                    <label class="px-4 py-2 text-sm border border-border rounded-lg hover:bg-muted transition-colors cursor-pointer flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Upload Favicon
                                        <input
                                            type="file"
                                            accept="image/png,image/x-icon,image/jpeg,image/svg+xml,.ico"
                                            @change="handleFaviconUpload"
                                            class="hidden"
                                        />
                                    </label>
                                    <button
                                        v-if="faviconPreview"
                                        @click="removeFavicon"
                                        class="px-4 py-2 text-sm border border-border rounded-lg hover:bg-muted transition-colors"
                                    >
                                        Remove
                                    </button>
                                    <button
                                        v-if="faviconForm.favicon || faviconForm.delete_favicon"
                                        @click="saveFavicon"
                                        :disabled="faviconForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ faviconForm.processing ? 'Saving...' : 'Save Favicon' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Settings -->
            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-6 h-6 text-[#2d5f3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-foreground">
                        Footer Settings
                    </h2>
                </div>

                <div class="space-y-6">
                    <!-- Newsletter Text -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">
                                    Newsletter Text
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Customize the newsletter subscription text displayed in the footer
                                </p>
                                
                                <input
                                    v-model="footerForm.newsletter_text"
                                    type="text"
                                    class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                    placeholder="Enter newsletter text..."
                                />
                                
                                <div class="mt-3">
                                    <button
                                        @click="saveFooter({ newsletter_text: footerForm.newsletter_text })"
                                        :disabled="footerForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ footerForm.processing ? 'Saving...' : 'Save Newsletter Text' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Email -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">
                                    Contact Email
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Set the contact email address displayed in the footer
                                </p>
                                
                                <input
                                    v-model="footerForm.contact_email"
                                    type="email"
                                    class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                    placeholder="Enter contact email..."
                                />
                                
                                <div class="mt-3">
                                    <button
                                        @click="saveFooter({ contact_email: footerForm.contact_email })"
                                        :disabled="footerForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ footerForm.processing ? 'Saving...' : 'Save Contact Email' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Phone -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">
                                    Contact Phone
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Set the contact phone number displayed in the footer
                                </p>
                                
                                <input
                                    v-model="footerForm.contact_phone"
                                    type="text"
                                    class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                    placeholder="Enter contact phone..."
                                />
                                
                                <div class="mt-3">
                                    <button
                                        @click="saveFooter({ contact_phone: footerForm.contact_phone })"
                                        :disabled="footerForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ footerForm.processing ? 'Saving...' : 'Save Contact Phone' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Location -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">
                                    Contact Location
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Set the contact location displayed in the footer
                                </p>
                                
                                <textarea
                                    v-model="footerForm.contact_location"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                    placeholder="Enter contact location..."
                                ></textarea>
                                
                                <div class="mt-3">
                                    <button
                                        @click="saveFooter({ contact_location: footerForm.contact_location })"
                                        :disabled="footerForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ footerForm.processing ? 'Saving...' : 'Save Contact Location' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support line under social icons -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">Footer support line</h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Shown under the social media icons in the footer (e.g. 24/7 support message). Leave empty to hide.
                                </p>
                                <textarea
                                    v-model="footerForm.footer_support_text"
                                    rows="2"
                                    class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                    placeholder="24/7 support provided by the event team."
                                />
                                <div class="mt-3">
                                    <button
                                        type="button"
                                        @click="saveFooter({ footer_support_text: footerForm.footer_support_text })"
                                        :disabled="footerForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ footerForm.processing ? 'Saving...' : 'Save Support Line' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-foreground mb-1">
                                    Social Media Links
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    Set the social media links displayed in the footer
                                </p>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">
                                            Facebook
                                        </label>
                                        <input
                                            v-model="footerForm.social_facebook"
                                            type="url"
                                            class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                            placeholder="Enter Facebook URL..."
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">
                                            Twitter
                                        </label>
                                        <input
                                            v-model="footerForm.social_twitter"
                                            type="url"
                                            class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                            placeholder="Enter Twitter URL..."
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">
                                            Instagram
                                        </label>
                                        <input
                                            v-model="footerForm.social_instagram"
                                            type="url"
                                            class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                            placeholder="Enter Instagram URL..."
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">
                                            LinkedIn
                                        </label>
                                        <input
                                            v-model="footerForm.social_linkedin"
                                            type="url"
                                            class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                            placeholder="Enter LinkedIn URL..."
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-muted-foreground mb-1">
                                            YouTube
                                        </label>
                                        <input
                                            v-model="footerForm.social_youtube"
                                            type="url"
                                            class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2d5f3f] bg-white text-sm"
                                            placeholder="Enter YouTube URL..."
                                        />
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button
                                        @click="saveFooter({ social_facebook: footerForm.social_facebook, social_twitter: footerForm.social_twitter, social_instagram: footerForm.social_instagram, social_linkedin: footerForm.social_linkedin, social_youtube: footerForm.social_youtube })"
                                        :disabled="footerForm.processing"
                                        class="px-4 py-2 text-sm bg-[#2d5f3f] text-white rounded-lg hover:bg-[#4a7c59] transition-colors disabled:opacity-50"
                                    >
                                        {{ footerForm.processing ? 'Saving...' : 'Save Social Media Links' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
