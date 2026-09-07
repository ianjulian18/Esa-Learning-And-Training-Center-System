<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    rules: Array
});
</script>

<template>
    <Head title="Assignment Rules" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assignment Rules</h2>
                <Link :href="route('admin.assignment_rules.create')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Add New Rule</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Principal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Criteria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="rule in rules" :key="rule.id">
                                <td class="px-6 py-4 font-medium">{{ rule.course?.title }}</td>
                                <td class="px-6 py-4">{{ rule.principal?.name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div v-if="rule.department">Dept: {{ rule.department.name }}</div>
                                    <div>
                                        Positions: 
                                        <span v-for="pos in rule.positions" :key="pos.id" class="mr-1 bg-gray-200 px-1 rounded">{{ pos.name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ rule.status }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="rules.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No Assignment Rules found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
