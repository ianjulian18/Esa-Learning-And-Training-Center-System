<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps<{
    roles: any[];
}>();

const form = useForm({
    name: '',
    email: '',
    nik: '',
    role: '',
    status: 'ACTIVE'
});

const submit = () => {
    form.post(route('admin.users.store'));
};
</script>

<template>
    <Head title="Create User" />

    <AdminLayout>
        <div class="max-w-3xl mx-auto py-8">
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        Create New User
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <Link :href="route('admin.users.index')" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                        Cancel
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-white shadow-sm ring-1 ring-slate-200 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        
                        <!-- Name -->
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Full Name</label>
                            <div class="mt-2">
                                <input type="text" id="name" v-model="form.name" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email Address</label>
                            <div class="mt-2">
                                <input type="email" id="email" v-model="form.email" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>

                        <!-- NIK -->
                        <div class="sm:col-span-3">
                            <label for="nik" class="block text-sm font-medium leading-6 text-slate-900">NIK (Nomor Induk Karyawan)</label>
                            <div class="mt-2">
                                <input type="text" id="nik" v-model="form.nik" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.nik" class="mt-2 text-sm text-red-600">{{ form.errors.nik }}</p>
                        </div>

                        <!-- Role -->
                        <div class="sm:col-span-3">
                            <label for="role" class="block text-sm font-medium leading-6 text-slate-900">Role</label>
                            <div class="mt-2">
                                <select id="role" v-model="form.role" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                    <option value="" disabled>Select a role</option>
                                    <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                                </select>
                            </div>
                            <p v-if="form.errors.role" class="mt-2 text-sm text-red-600">{{ form.errors.role }}</p>
                        </div>

                        <!-- Status -->
                        <div class="sm:col-span-3">
                            <label for="status" class="block text-sm font-medium leading-6 text-slate-900">Status</label>
                            <div class="mt-2">
                                <select id="status" v-model="form.status" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                    <option value="ACTIVE">ACTIVE</option>
                                    <option value="INACTIVE">INACTIVE</option>
                                </select>
                            </div>
                            <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</p>
                        </div>
                        
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <p class="text-sm text-slate-500">Note: Default password for new users is <strong>password123</strong>.</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-x-6 border-t border-slate-900/10 px-4 py-4 sm:px-8 bg-slate-50 rounded-b-xl">
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:opacity-50">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

