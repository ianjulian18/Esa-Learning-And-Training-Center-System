<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ batches: Array });
const form = useForm({ file: null });

const submit = () => { 
    form.post(route('admin.imports.store'), {
        onSuccess: () => form.reset()
    }); 
};
</script>

<template>
    <Head title="Bulk Import" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Identity: Bulk Import Users</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Information & Download Template -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 shadow-sm sm:rounded-lg flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-blue-800 mb-2">How to bulk import users</h3>
                        <p class="text-sm text-blue-700 mb-2">
                            Please upload a standard CSV file. The first row must contain exactly these column headers: <br/>
                            <code class="bg-blue-100 px-1 py-0.5 rounded font-mono text-xs text-blue-900">name, email, nik, password, principal_id, position_id, department_id, join_date</code>
                        </p>
                        <p class="text-xs text-blue-600">
                            * Note: For principals, positions, and departments, you must use their numeric Database IDs, not their string names.
                        </p>
                    </div>
                    <div>
                        <a :href="route('admin.imports.template')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download CSV Template
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Data</h3>
                    <form @submit.prevent="submit" class="flex items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select CSV File</label>
                            <input type="file" accept=".csv" @input="form.file = $event.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-md shadow-sm" required />
                        </div>
                        <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-indigo-700 disabled:opacity-50">Upload & Process</button>
                    </form>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
