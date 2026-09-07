<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    course: any;
    assessment: any;
    attempts: any[];
    enrollment: any;
}>();

const answers = ref<Record<number, string>>({});
const isSubmitting = ref(false);

const latestAttempt = computed(() => {
    if (!props.attempts || props.attempts.length === 0) return null;
    // Sort by id descending to get latest
    return [...props.attempts].sort((a, b) => b.id - a.id)[0];
});

const submitAssessment = () => {
    if(confirm('Are you sure you want to submit your answers?')) {
        isSubmitting.value = true;
        router.post(route('learner.assessment.submit', props.course.id), {
            answers: answers.value
        }, {
            onFinish: () => isSubmitting.value = false
        });
    }
};

const hasPassed = computed(() => {
    return latestAttempt.value?.status === 'PASSED';
});

const isPendingGrading = computed(() => {
    return latestAttempt.value?.status === 'PENDING_GRADING';
});

</script>

<template>
    <Head :title="`Assessment: ${course.title}`" />

    <LearnerLayout>
        <div class="bg-slate-900 pb-24 pt-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-4">
                    <Link :href="route('learner.learn', course.id)" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white transition-colors">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Course
                    </Link>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-white">Course Assessment</h1>
                <p class="mt-2 text-slate-300 text-lg">{{ course.title }}</p>
            </div>
        </div>

        <div class="-mt-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border border-green-200 p-4 rounded-md shadow-sm">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                
                <!-- If already passed -->
                <div v-if="hasPassed" class="p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Congratulations!</h2>
                    <p class="text-slate-600 mb-6">You have passed this assessment with a score of <strong>{{ latestAttempt.score }}</strong> (Passing Grade: {{ assessment.passing_grade }}).</p>
                    <div class="flex justify-center space-x-4">
                        <Link :href="route('learner.courses')" class="inline-flex justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                            Back to Dashboard
                        </Link>
                        <!-- Future: Link to Certificate -->
                        <button class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700" disabled>
                            View Certificate
                        </button>
                    </div>
                </div>
                
                <!-- If pending grading -->
                <div v-else-if="isPendingGrading" class="p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-amber-100 mb-6">
                        <svg class="h-10 w-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Pending Manual Grading</h2>
                    <p class="text-slate-600 mb-6">Your assessment includes essay questions which require manual grading by an instructor. You will be notified once it has been evaluated.</p>
                    <Link :href="route('learner.courses')" class="inline-flex justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                        Back to Dashboard
                    </Link>
                </div>

                <!-- If failed -->
                <div v-else-if="latestAttempt && latestAttempt.status === 'FAILED'" class="p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                        <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Assessment Failed</h2>
                    <p class="text-slate-600 mb-6">You scored <strong>{{ latestAttempt.score }}</strong>. A minimum score of {{ assessment.passing_grade }} is required to pass.</p>
                    <div class="flex justify-center space-x-4">
                        <Link :href="route('learner.learn', course.id)" class="inline-flex justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                            Review Materials
                        </Link>
                        <button @click="answers = {};" class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            Retake Assessment
                        </button>
                    </div>
                </div>

                <!-- Taking Assessment -->
                <div v-else>
                    <div class="p-6 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-slate-900">Instructions</h3>
                            <p class="text-sm text-slate-500 mt-1">Please answer all questions below. You need a score of {{ assessment.passing_grade }} to pass.</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300">
                                {{ assessment.questions.length }} Questions
                            </span>
                        </div>
                    </div>

                    <form @submit.prevent="submitAssessment" class="p-6 space-y-10">
                        <div v-for="(aq, index) in assessment.questions" :key="aq.id" class="space-y-4">
                            <div class="flex">
                                <span class="font-bold text-slate-900 mr-3">{{ index + 1 }}.</span>
                                <div class="flex-1">
                                    <p class="text-slate-900 font-medium text-lg leading-snug">{{ aq.question_bank.question_text }}</p>
                                    
                                    <!-- Multiple Choice or True/False -->
                                    <div v-if="['MULTIPLE_CHOICE', 'TRUE_FALSE'].includes(aq.question_bank.question_type)" class="mt-4 space-y-3">
                                        <label v-for="(opt, oIdx) in (aq.question_bank.options || [])" :key="oIdx" class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:bg-slate-50 transition-colors" :class="answers[aq.id] === opt ? 'border-primary-600 ring-1 ring-primary-600' : 'border-slate-300'">
                                            <input type="radio" :name="`q_${aq.id}`" :value="opt" v-model="answers[aq.id]" class="sr-only" required />
                                            <span class="flex flex-1">
                                                <span class="flex flex-col">
                                                    <span class="block text-sm font-medium text-slate-900">{{ opt }}</span>
                                                </span>
                                            </span>
                                            <svg v-if="answers[aq.id] === opt" class="h-5 w-5 text-primary-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                    </div>

                                    <!-- Essay -->
                                    <div v-else-if="aq.question_bank.question_type === 'ESSAY'" class="mt-4">
                                        <textarea v-model="answers[aq.id]" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Write your essay answer here..." required></textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-200">
                            <button type="submit" :disabled="isSubmitting" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none disabled:opacity-50 transition-colors">
                                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submit Assessment
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </LearnerLayout>
</template>
