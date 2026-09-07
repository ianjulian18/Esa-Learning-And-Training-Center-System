<script setup>
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    certificates: Array
});
</script>

<template>
    <Head title="My Certificates" />
    <LearnerLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Certificates</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                <div v-if="certificates.length === 0" class="text-center py-12 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No certificates</h3>
                    <p class="mt-1 text-sm text-gray-500">Complete courses to earn certificates.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="cert in certificates" :key="cert.id" class="border rounded-lg overflow-hidden flex flex-col hover:shadow-md transition">
                        <div class="bg-indigo-50 p-6 flex-1 text-center border-b border-indigo-100">
                            <svg class="mx-auto h-16 w-16 text-indigo-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <h3 class="font-bold text-lg text-gray-900">{{ cert.course.title }}</h3>
                            <p class="text-xs text-gray-500 mt-2">Issued on: {{ new Date(cert.issued_at).toLocaleDateString() }}</p>
                            <p class="text-xs text-gray-400 mt-1">ID: {{ cert.certificate_number }}</p>
                        </div>
                        <div class="bg-white p-4">
                            <a :href="route('learner.my-certificates.download', cert.id)" target="_blank" class="block w-full text-center bg-indigo-600 text-white font-medium py-2 rounded shadow-sm hover:bg-indigo-700">
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </LearnerLayout>
</template>
