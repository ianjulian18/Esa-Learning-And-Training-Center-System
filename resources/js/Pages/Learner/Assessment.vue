<script setup>
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    course: Object,
    assessment: Object,
    attempt: Object,
    questions: Array
});

const currentQuestionIndex = ref(0);
const form = useForm({
    answers: {}
});

const selectAnswer = (qId, option) => {
    form.answers[qId] = option;
};

const next = () => {
    if (currentQuestionIndex.value < props.questions.length - 1) {
        currentQuestionIndex.value++;
    }
};

const prev = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

const submit = () => {
    if (confirm('Are you sure you want to submit your answers?')) {
        form.post(route('learner.courses.attempts.submit', [props.course.id, props.attempt.id]));
    }
};
</script>

<template>
    <Head :title="'Assessment: ' + course.title" />
    <LearnerLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('learner.courses.show', course.id)" class="text-gray-500 hover:text-gray-700">? Exit</Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assessment: {{ course.title }} ({{ assessment.type }})</h2>
            </div>
        </template>
        
        <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg overflow-hidden flex flex-col min-h-[500px]">
                
                <!-- Header -->
                <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                    <span class="font-bold text-gray-600">Question {{ currentQuestionIndex + 1 }} of {{ questions.length }}</span>
                    <span class="text-sm bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold">Passing Grade: {{ assessment.passing_grade }}</span>
                </div>

                <!-- Body -->
                <div class="p-8 flex-1">
                    <h3 class="text-2xl font-bold mb-6">{{ questions[currentQuestionIndex].content }}</h3>
                    
                    <div class="space-y-3">
                        <label v-for="(opt, idx) in questions[currentQuestionIndex].options" :key="idx" 
                               class="flex items-center p-4 border rounded cursor-pointer transition hover:bg-gray-50"
                               :class="{'border-indigo-500 bg-indigo-50': form.answers[questions[currentQuestionIndex].id] === opt}">
                            <input type="radio" :name="'question_' + questions[currentQuestionIndex].id" :value="opt" 
                                   @change="selectAnswer(questions[currentQuestionIndex].id, opt)" class="hidden">
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4"
                                 :class="{'border-indigo-600': form.answers[questions[currentQuestionIndex].id] === opt}">
                                <div v-if="form.answers[questions[currentQuestionIndex].id] === opt" class="w-3 h-3 rounded-full bg-indigo-600"></div>
                            </div>
                            <span class="text-lg">{{ opt }}</span>
                        </label>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t flex justify-between">
                    <button @click="prev" :disabled="currentQuestionIndex === 0" class="px-6 py-2 border rounded shadow-sm disabled:opacity-50">Previous</button>
                    
                    <button v-if="currentQuestionIndex < questions.length - 1" @click="next" class="px-6 py-2 bg-indigo-600 text-white rounded shadow-sm hover:bg-indigo-700">Next</button>
                    <button v-else @click="submit" class="px-8 py-2 bg-green-600 text-white font-bold rounded shadow-sm hover:bg-green-700">Submit Assessment</button>
                </div>

            </div>
            
            <div class="mt-6 flex flex-wrap gap-2">
                <button v-for="(q, idx) in questions" :key="idx" @click="currentQuestionIndex = idx"
                        class="w-10 h-10 flex items-center justify-center rounded border font-bold"
                        :class="[form.answers[q.id] ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-white text-gray-500', currentQuestionIndex === idx ? 'ring-2 ring-indigo-500' : '']">
                    {{ idx + 1 }}
                </button>
            </div>
        </div>
    </LearnerLayout>
</template>
