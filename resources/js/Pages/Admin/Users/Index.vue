<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    users: Array
});
</script>

<template>
    <Head title="User Management" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Advanced User Management</h2>
                <Link :href="route('admin.users.create')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Add New User</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK / Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Employment</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                    <div class="text-sm text-gray-500">NIK: {{ user.nik }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-for="role in user.roles" :key="role.id" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                                        {{ role.name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div v-if="user.current_employment">
                                        <strong>{{ user.current_employment.principal?.name || 'N/A' }}</strong>
                                        <div>{{ user.current_employment.position?.name || 'N/A' }} (NIP: {{ user.current_employment.nip }})</div>
                                    </div>
                                    <div v-else class="text-yellow-600 italic">No Active Employment</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <Link :href="route('admin.users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900">Manage</Link>
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
