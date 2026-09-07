<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';

defineProps<{
    courses: any[];
}>();
</script>

<template>
    <Head title="My Learning" />

    <LearnerLayout>
        <div class="bg-primary-600 pb-24 pt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">My Learning</h1>
                <p class="mt-2 max-w-xl text-primary-100">Pick up where you left off or start a new course assigned to you.</p>
            </div>
        </div>

        <div class="-mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <div v-if="courses.length === 0" class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">No courses assigned</h3>
                <p class="mt-1 text-sm text-slate-500">You don't have any pending courses to take.</p>
            </div>

            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="course in courses" :key="course.id" class="flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="h-48 bg-slate-200 w-full object-cover">
                         <div class="flex h-full items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200 text-primary-500">
                            <svg class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-slate-900 line-clamp-2">{{ course.title }}</h3>
                        <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ course.description }}</p>
                        
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span>Progress</span>
                                <span>0%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-1.5">
                                <div class="bg-primary-600 h-1.5 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="mt-auto pt-6">
                            <Link :href="route('learner.learn', course.id)" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none transition-colors">
                                Start Learning
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LearnerLayout>
</template>
