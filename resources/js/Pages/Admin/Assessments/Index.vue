<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ assessments: Array });
</script>

<template>
    <Head title="Assessments" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assessment: Tests & Exams</h2>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Create Assessment</button>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm">Assessments Management module placeholder. Links to courses and passing grades.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
