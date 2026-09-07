<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps<{
    users: any;
}>();

const deleteUser = (id: number, name: string) => {
    if(confirm(`Are you sure you want to delete ${name}?`)) {
        router.delete(route('admin.users.destroy', id));
    }
};
</script>

<template>
    <Head title="User Management" />

    <AdminLayout>
        <div v-if="$page.props.flash?.success" class="mb-4 bg-green-50 p-4 rounded-md flex items-center shadow-sm">
            <svg class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
        </div>
        <div v-if="$page.props.flash?.error" class="mb-4 bg-red-50 p-4 rounded-md flex items-center shadow-sm">
            <svg class="h-5 w-5 text-red-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-semibold text-slate-900">Users</h1>
                    <p class="mt-2 text-sm text-slate-700">A list of all the users in your account including their name, NIK, role, and email.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('admin.users.create')" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto transition-all">
                        Add user
                    </Link>
                </div>
            </div>
            
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-slate-300">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-slate-900 sm:pl-6">Name</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">NIK</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Role</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Status</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full" :src="`https://ui-avatars.com/api/?name=${user.name}&background=eff6ff&color=1d4ed8`" alt="" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-slate-900">{{ user.name }}</div>
                                                    <div class="text-slate-500">{{ user.email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                            {{ user.nik || '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                            <span v-if="user.roles.length > 0" class="inline-flex rounded-full bg-primary-100 px-2 text-xs font-semibold leading-5 text-primary-800">
                                                {{ user.roles[0].name }}
                                            </span>
                                            <span v-else class="text-slate-400">None</span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                            <span :class="{'bg-green-100 text-green-800': user.status === 'ACTIVE', 'bg-red-100 text-red-800': user.status !== 'ACTIVE'}" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                                {{ user.status }}
                                            </span>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 flex justify-end items-center h-full space-x-3 mt-2">
                                            <Link :href="route('admin.users.edit', user.id)" class="text-primary-600 hover:text-primary-900">Edit</Link>
                                            <button @click="deleteUser(user.id, user.name)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 flex items-center justify-between" v-if="users.links.length > 3">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-700">
                            Showing <span class="font-medium">{{ users.from }}</span> to <span class="font-medium">{{ users.to }}</span> of <span class="font-medium">{{ users.total }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <Link v-for="(link, k) in users.links" :key="k" 
                                  :href="link.url || '#'" 
                                  v-html="link.label"
                                  :class="[
                                      link.active ? 'z-10 bg-primary-50 border-primary-500 text-primary-600' : 'bg-white border-slate-300 text-slate-500 hover:bg-slate-50',
                                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                      {'rounded-l-md': k === 0, 'rounded-r-md': k === users.links.length - 1}
                                  ]" />
                        </nav>
                    </div>
                </div>
            </div>
            
        </div>
    </AdminLayout>
</template>
