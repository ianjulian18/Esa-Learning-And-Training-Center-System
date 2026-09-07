<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({ file: null });
const submit = () => { form.post(route('admin.imports.store')); };
</script>

<template>
    <Head title="Bulk Import" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Identity: Bulk Import Users</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <input type="file" @input="form.file = $event.target.files[0]" />
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm">Upload CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
