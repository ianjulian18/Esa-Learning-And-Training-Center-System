<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps<{
    stats: {
        total_users: number;
        total_learners: number;
        total_courses: number;
        total_enrollments: number;
        completed_enrollments: number;
        total_certificates_issued: number;
    };
    topCourses: any[];
}>();
</script>

<template>
    <Head title="Reports Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Users / Learners</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_users }} / {{ stats.total_learners }}</div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Courses</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_courses }}</div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                        <div class="text-sm font-medium text-gray-500 truncate">Certificates Issued</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total_certificates_issued }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Completion Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Enrollment & Completion</h3>
                        </div>
                        <div class="p-6 flex flex-col items-center justify-center">
                            <div class="relative w-48 h-48 flex items-center justify-center rounded-full bg-gray-100 mb-4">
                                <div class="absolute inset-0 rounded-full border-8 border-gray-200"></div>
                                <div class="absolute inset-0 rounded-full border-8 border-green-500" :style="`clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); transform: rotate(${stats.total_enrollments ? (stats.completed_enrollments / stats.total_enrollments * 360) : 0}deg);`"></div>
                                <div class="text-center z-10 bg-white rounded-full h-32 w-32 flex flex-col items-center justify-center shadow-inner">
                                    <span class="text-3xl font-bold text-gray-900">{{ stats.total_enrollments ? Math.round((stats.completed_enrollments / stats.total_enrollments) * 100) : 0 }}%</span>
                                    <span class="text-xs text-gray-500 uppercase">Completion Rate</span>
                                </div>
                            </div>
                            <div class="w-full flex justify-between text-sm text-gray-600 mt-4 px-8">
                                <div class="text-center"><div class="font-bold text-lg">{{ stats.total_enrollments }}</div>Total Enrolled</div>
                                <div class="text-center"><div class="font-bold text-lg text-green-600">{{ stats.completed_enrollments }}</div>Completed</div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Courses -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Top 5 Popular Courses</h3>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <li v-if="topCourses.length === 0" class="p-6 text-gray-500 text-center">No enrollments yet.</li>
                            <li v-for="(course, index) in topCourses" :key="course.id" class="p-4 flex items-center hover:bg-gray-50">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                                    #{{ index + 1 }}
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ course.title }}</div>
                                    <div class="text-sm text-gray-500">{{ course.category }}</div>
                                </div>
                                <div class="ml-4 flex-shrink-0 text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ course.enrollments_count }} Enrollments
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
