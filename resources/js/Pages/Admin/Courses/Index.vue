<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    courses: Array
});
</script>

<template>
    <Head title="Courses" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Course Builder</h2>
                <Link :href="route('admin.courses.create')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Add New Course</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Principals</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="course in courses" :key="course.id">
                                <td class="px-6 py-4 font-medium">{{ course.code }}</td>
                                <td class="px-6 py-4">{{ course.title }}</td>
                                <td class="px-6 py-4">
                                    <span v-for="prin in course.principals" :key="prin.id" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                                        {{ prin.name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ course.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <Link :href="route('admin.courses.show', course.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Build Curriculum</Link>
                                </td>
                            </tr>
                            <tr v-if="courses.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No courses available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

