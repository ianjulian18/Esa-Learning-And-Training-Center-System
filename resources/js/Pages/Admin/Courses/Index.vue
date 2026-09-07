<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps<{
    courses: any;
}>();
const deleteCourse = (id: number, title: string) => {
    if(confirm(`Are you sure you want to delete ${title}? This will also delete all modules and lessons inside it.`)) {
        router.delete(route('admin.courses.destroy', id));
    }
};
</script>

<template>
    <Head title="Course Management" />

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
            <div class="sm:flex sm:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">Courses</h1>
                    <p class="mt-2 text-sm text-slate-700">Manage all learning materials, modules, and lessons here.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button type="button" class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-primary-700 focus:outline-none transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Course
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="courses.data.length === 0" class="text-center mt-12 bg-white rounded-lg shadow border border-slate-200 py-16 px-4">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">No courses</h3>
                <p class="mt-1 text-sm text-slate-500">Get started by creating a new course.</p>
                <div class="mt-6">
                    <button type="button" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        New Course
                    </button>
                </div>
            </div>

            <!-- Grid -->
            <div v-else class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div v-for="course in courses.data" :key="course.id" class="group relative flex flex-col overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary-500 cursor-pointer">
                    <!-- Thumbnail -->
                    <div class="aspect-w-3 aspect-h-2 bg-slate-200 group-hover:opacity-90 transition-opacity">
                        <img v-if="course.thumbnail" :src="course.thumbnail" class="h-48 w-full object-cover" />
                        <div v-else class="flex h-48 items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200 text-primary-500">
                            <svg class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        
                        <div class="absolute top-2 right-2">
                            <span :class="{'bg-yellow-100 text-yellow-800': course.status === 'DRAFT', 'bg-green-100 text-green-800': course.status === 'PUBLISHED'}" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold shadow-sm">
                                {{ course.status || 'DRAFT' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="text-sm font-semibold text-slate-900 group-hover:text-primary-600 line-clamp-2">
                            <a href="#">
                                <span class="absolute inset-0"></span>
                                {{ course.title }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 line-clamp-2" :title="course.description">{{ course.description || 'No description provided.' }}</p>
                        
                        <!-- Footer details -->
                        <div class="mt-auto pt-4 flex items-center justify-between text-xs text-slate-500">
                            <div class="flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span>{{ course.modules_count || 0 }} Modules</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ course.duration || 0 }}m</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination (if needed) -->
        </div>
    </AdminLayout>
</template>


