<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';

const props = defineProps<{
    enrollments: any[];
    availableCourses: any[];
}>();

const enroll = (courseId: number) => {
    router.post(route('learner.enroll', courseId));
};
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

        <div class="-mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 space-y-12">
            <!-- Enrolled Courses -->
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center bg-white py-2 px-4 rounded-lg shadow-sm border border-slate-200 w-max">
                    <span class="bg-primary-100 text-primary-700 p-1.5 rounded mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    Enrolled Courses
                </h2>
                
                <div v-if="enrollments.length === 0" class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                    <p class="text-sm text-slate-500">You don't have any active enrollments.</p>
                </div>

                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="enr in enrollments" :key="enr.id" class="flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="h-48 bg-slate-200 w-full object-cover">
                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-primary-100 to-primary-200 text-primary-500">
                                <svg class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium" :class="enr.status === 'COMPLETED' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'">
                                    {{ enr.status }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 line-clamp-2">{{ enr.course.title }}</h3>
                            <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ enr.course.description }}</p>
                            
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs font-medium text-slate-700 mb-1">
                                    <span>Progress</span>
                                    <span>{{ enr.progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" :style="`width: ${enr.progress_percentage}%`"></div>
                                </div>
                            </div>

                            <div class="mt-auto pt-6 flex space-x-3">
                                <Link :href="route('learner.learn', enr.course.id)" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none transition-colors">
                                    {{ enr.progress_percentage === 0 ? 'Start Course' : 'Continue' }}
                                </Link>
                                <Link v-if="enr.progress_percentage === 100 || enr.status === 'COMPLETED'" :href="route('learner.assessment', enr.course.id)" class="inline-flex justify-center items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-colors">
                                    Assessment
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Courses (For Manual Enrollment during dev) -->
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center bg-white py-2 px-4 rounded-lg shadow-sm border border-slate-200 w-max">
                    <span class="bg-slate-100 text-slate-700 p-1.5 rounded mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    Available Courses
                </h2>
                
                <div v-if="availableCourses.length === 0" class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                    <p class="text-sm text-slate-500">No more courses available right now.</p>
                </div>

                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="course in availableCourses" :key="course.id" class="flex flex-col bg-slate-50 rounded-xl border border-slate-200 overflow-hidden hover:border-primary-300 transition-colors">
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-base font-bold text-slate-900 line-clamp-2">{{ course.title }}</h3>
                            <p class="mt-1 text-xs text-slate-500 line-clamp-3">{{ course.description }}</p>
                            
                            <div class="mt-4 flex items-center space-x-4 text-xs text-slate-500">
                                <span class="flex items-center">
                                    <svg class="mr-1.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    {{ course.modules_count }} Modules
                                </span>
                            </div>

                            <div class="mt-auto pt-4">
                                <button @click="enroll(course.id)" class="w-full inline-flex justify-center items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 hover:text-primary-600 focus:outline-none transition-colors">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </LearnerLayout>
</template>
