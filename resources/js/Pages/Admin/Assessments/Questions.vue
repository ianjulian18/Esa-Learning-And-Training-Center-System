<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ bank: Object });

const form = useForm({
    content: '',
    type: 'MULTIPLE_CHOICE',
    options: ['','','',''],
    correct_answer: '',
    explanation: ''
});

const submit = () => {
    // Only send options that are not empty
    form.options = form.options.filter(o => o.trim() !== '');
    form.post(route('admin.question_banks.questions.store', props.bank.id), {
        onSuccess: () => {
            form.reset('content', 'correct_answer', 'explanation');
            form.options = ['','','',''];
        }
    });
};
</script>

<template>
    <Head :title="bank.title + ' - Questions'" />
    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.question_banks.index')" class="text-gray-500 hover:text-gray-700">? Back</Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bank: {{ bank.title }}</h2>
            </div>
        </template>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-2 gap-6">
            <div class="space-y-4">
                <div v-for="(q, idx) in bank.questions" :key="q.id" class="bg-white p-4 shadow rounded-lg">
                    <div class="flex justify-between">
                        <span class="text-xs font-bold text-gray-400">Q{{ idx + 1 }} - {{ q.type }}</span>
                    </div>
                    <p class="font-bold mt-2">{{ q.content }}</p>
                    <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                        <li v-for="(opt, i) in q.options" :key="i" :class="{'text-green-600 font-bold': opt === q.correct_answer}">{{ opt }}</li>
                    </ul>
                </div>
                <div v-if="bank.questions.length === 0" class="text-gray-500 bg-white p-4 text-center rounded shadow">No questions yet.</div>
            </div>
            <div>
                <form @submit.prevent="submit" class="bg-white p-4 shadow rounded-lg sticky top-6">
                    <h3 class="font-bold mb-4">Add Question</h3>
                    <div class="mb-4">
                        <label class="block text-sm">Question Content</label>
                        <textarea v-model="form.content" required class="w-full rounded border-gray-300"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm">Type</label>
                        <select v-model="form.type" class="w-full rounded border-gray-300">
                            <option value="MULTIPLE_CHOICE">Multiple Choice</option>
                            <option value="TRUE_FALSE">True / False</option>
                        </select>
                    </div>
                    
                    <div v-if="form.type === 'MULTIPLE_CHOICE'" class="mb-4 space-y-2">
                        <label class="block text-sm">Options</label>
                        <input v-for="(opt, i) in 4" :key="i" v-model="form.options[i]" type="text" class="w-full rounded border-gray-300" :placeholder="'Option ' + (i+1)">
                    </div>
                    <div v-if="form.type === 'TRUE_FALSE'" class="mb-4">
                        <label class="block text-sm">Options will be automatically set to True and False.</label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-green-600">Correct Answer (must match one option exactly)</label>
                        <input v-model="form.correct_answer" type="text" required class="w-full rounded border-green-300 border-2">
                    </div>
                    
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded shadow hover:bg-indigo-700">Add Question</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
