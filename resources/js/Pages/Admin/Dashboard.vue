<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Admin Dashboard" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-2xl font-bold mb-4">Welcome to Admin Portal</h3>
                <p class="text-gray-600">
                    You can manage users, courses, assignments, and view system reports from the sidebar.
                </p>
            </div>
        </div>
    </AdminLayout>
</template>
