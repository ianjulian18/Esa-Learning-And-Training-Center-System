<script setup>
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    enrollments: Array
});
</script>

<template>
    <Head title="My Courses" />
    <LearnerLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Courses</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="enrollment in enrollments" :key="enrollment.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                        <div class="p-6 flex-1">
                            <h3 class="text-xl font-bold mb-2">{{ enrollment.course?.title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ enrollment.course?.description }}</p>
                            
                            <div class="mt-auto">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Status: <span class="font-bold text-indigo-600">{{ enrollment.status }}</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">{{ enrollment.course?.modules?.length || 0 }} Modules</span>
                            <Link :href="route('learner.courses.show', enrollment.course_id)" class="text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded text-sm font-medium transition">
                                {{ enrollment.status === 'NOT STARTED' ? 'Start Course' : 'Continue' }}
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-if="enrollments.length === 0" class="bg-white p-12 text-center shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900">No assigned courses</h3>
                    <p class="text-gray-500 mt-2">You currently have no courses assigned to your profile. Please contact your administrator.</p>
                </div>

            </div>
        </div>
    </LearnerLayout>
</template>
