<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    entities: Array,
    principals: Array,
    regions: Array,
    areas: Array,
    positions: Array,
    departments: Array,
    roles: Array,
});

const form = useForm({
    nik: '',
    name: '',
    email: '',
    password: '',
    role: '',
    nip: '',
    entity_id: '',
    principal_id: '',
    region_id: '',
    area_id: '',
    position_id: '',
    department_id: '',
    start_date: ''
});

const submit = () => {
    form.post(route('admin.users.store'));
};
</script>

<template>
    <Head title="Create User" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create User (Identity & Employment)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-medium text-slate-800">1. Core Identity & Role</h3>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium">NIK (KTP)</label>
                                    <input v-model="form.nik" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.nik" class="text-red-500 text-xs mt-1">{{ form.errors.nik }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Full Name</label>
                                    <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Email</label>
                                    <input v-model="form.email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Password</label>
                                    <input v-model="form.password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium">Role</label>
                                    <select v-model="form.role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="" disabled>Select Role</option>
                                        <option v-for="r in roles" :key="r.id" :value="r.name">{{ r.name }}</option>
                                    </select>
                                    <div v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="border-b pb-4">
                            <h3 class="text-lg font-medium text-slate-800">2. Initial Employment Information</h3>
                            <p class="text-sm text-gray-500">This creates the user's first Active Employment History.</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium">NIP (Employee ID)</label>
                                    <input v-model="form.nip" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.nip" class="text-red-500 text-xs mt-1">{{ form.errors.nip }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Entity</label>
                                    <select v-model="form.entity_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="" disabled>Select Entity</option>
                                        <option v-for="e in entities" :key="e.id" :value="e.id">{{ e.name }}</option>
                                    </select>
                                    <div v-if="form.errors.entity_id" class="text-red-500 text-xs mt-1">{{ form.errors.entity_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Principal</label>
                                    <select v-model="form.principal_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="" disabled>Select Principal</option>
                                        <option v-for="p in principals" :key="p.id" :value="p.id">{{ p.name }}</option>
                                    </select>
                                    <div v-if="form.errors.principal_id" class="text-red-500 text-xs mt-1">{{ form.errors.principal_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Region (Optional)</label>
                                    <select v-model="form.region_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="">None</option>
                                        <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
                                    </select>
                                    <div v-if="form.errors.region_id" class="text-red-500 text-xs mt-1">{{ form.errors.region_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Area (Optional)</label>
                                    <select v-model="form.area_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="">None</option>
                                        <option v-for="a in areas" :key="a.id" :value="a.id">{{ a.name }}</option>
                                    </select>
                                    <div v-if="form.errors.area_id" class="text-red-500 text-xs mt-1">{{ form.errors.area_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Position</label>
                                    <select v-model="form.position_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="" disabled>Select Position</option>
                                        <option v-for="p in positions" :key="p.id" :value="p.id">{{ p.name }}</option>
                                    </select>
                                    <div v-if="form.errors.position_id" class="text-red-500 text-xs mt-1">{{ form.errors.position_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Department (Optional)</label>
                                    <select v-model="form.department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm">
                                        <option value="">None</option>
                                        <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                                    </select>
                                    <div v-if="form.errors.department_id" class="text-red-500 text-xs mt-1">{{ form.errors.department_id }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Start Date</label>
                                    <input v-model="form.start_date" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.start_date" class="text-red-500 text-xs mt-1">{{ form.errors.start_date }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <Link :href="route('admin.users.index')" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
