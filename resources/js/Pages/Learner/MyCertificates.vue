<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';

defineProps<{
    certificates: any[];
}>();
</script>

<template>
    <Head title="My Certificates" />

    <LearnerLayout>
        <div class="bg-primary-600 pb-24 pt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">My Certificates</h1>
                <p class="mt-2 max-w-xl text-primary-100">View and download your earned certificates.</p>
            </div>
        </div>

        <div class="-mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <div v-if="certificates.length === 0" class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">No certificates yet</h3>
                <p class="mt-1 text-sm text-slate-500">Complete courses and pass the assessments to earn certificates.</p>
            </div>

            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="cert in certificates" :key="cert.id" class="flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="h-40 bg-gradient-to-br from-slate-800 to-slate-900 w-full relative flex items-center justify-center border-b border-yellow-500 border-b-4">
                        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                        <div class="z-10 text-center px-4">
                            <h4 class="text-white font-serif text-xl tracking-widest uppercase">CERTIFICATE</h4>
                            <p class="text-yellow-400 text-xs mt-2 uppercase tracking-widest">Of Completion</p>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-slate-900 line-clamp-2">{{ cert.course.title }}</h3>
                        <div class="mt-3 text-sm text-slate-500 space-y-1">
                            <p><span class="font-medium">Issued:</span> {{ new Date(cert.issue_date).toLocaleDateString() }}</p>
                            <p><span class="font-medium">ID:</span> {{ cert.certificate_number }}</p>
                        </div>
                        
                        <div class="mt-auto pt-6 flex space-x-3">
                            <a :href="route('learner.certificates.download', cert.id)" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 hover:text-primary-600 transition-colors">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LearnerLayout>
</template>
