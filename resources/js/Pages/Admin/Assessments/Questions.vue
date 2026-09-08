<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ bank: Object });
const form = useForm({
    question_text: '',
    type: 'MULTIPLE_CHOICE',
    options: ['Option A', 'Option B', 'Option C', 'Option D'],
    answer_key: '0',
    points: 10
});

const isAdding = ref(false);

const addOption = () => { form.options.push(`Option ${String.fromCharCode(65 + form.options.length)}`); };
const removeOption = (index) => { form.options.splice(index, 1); if(form.answer_key == index) form.answer_key = '0'; };

const submit = () => {
    form.post(route('admin.question_banks.questions.store', props.bank.id), {
        onSuccess: () => {
            form.reset();
            isAdding.value = false;
        }
    });
};
</script>

<template>
    <Head :title="bank.title + ' - Questions'" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ bank.title }} ({{ bank.questions.length }} Questions)</h2>
                <div class="flex gap-2">
                    <Link :href="route('admin.question_banks.index')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Back</Link>
                    <button @click="isAdding = !isAdding" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        {{ isAdding ? 'Cancel' : 'Add Question' }}
                    </button>
                </div>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Add Question Form (Google Form style) -->
                <div v-if="isAdding" class="bg-white p-6 shadow-sm sm:rounded-lg border-t-4 border-indigo-500">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Question Text</label>
                            <textarea v-model="form.question_text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-lg p-3" rows="3" placeholder="Write your question here..."></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Question Type</label>
                                <select v-model="form.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="MULTIPLE_CHOICE">Multiple Choice</option>
                                    <option value="TRUE_FALSE">True / False</option>
                                    <option value="ESSAY">Essay</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Points</label>
                                <input v-model="form.points" type="number" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Dynamic Options for Multiple Choice -->
                        <div v-if="form.type === 'MULTIPLE_CHOICE'" class="space-y-3 bg-gray-50 p-4 rounded-md border">
                            <label class="block text-sm font-medium text-gray-700">Answer Options (Select the correct one)</label>
                            <div v-for="(opt, idx) in form.options" :key="idx" class="flex items-center gap-3">
                                <input type="radio" v-model="form.answer_key" :value="idx.toString()" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <input v-model="form.options[idx]" type="text" required class="flex-1 rounded-md border-gray-300 shadow-sm">
                                <button type="button" @click="removeOption(idx)" class="text-red-500 hover:text-red-700" v-if="form.options.length > 2">?</button>
                            </div>
                            <button type="button" @click="addOption" class="text-indigo-600 text-sm font-medium hover:underline">+ Add Option</button>
                        </div>

                        <!-- True False -->
                        <div v-if="form.type === 'TRUE_FALSE'" class="space-y-3 bg-gray-50 p-4 rounded-md border">
                            <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                            <select v-model="form.answer_key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-6 py-2 rounded shadow-sm hover:bg-indigo-700">Save Question</button>
                        </div>
                    </form>
                </div>

                <!-- List of Questions -->
                <div v-for="(q, idx) in bank.questions" :key="q.id" class="bg-white p-6 shadow-sm sm:rounded-lg flex gap-4">
                    <div class="font-bold text-gray-400 text-xl">{{ idx + 1 }}.</div>
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900">{{ q.question_text }}</h4>
                        <div class="text-xs text-gray-500 mb-4">{{ q.type }} - {{ q.points }} pts</div>
                        
                        <!-- MC Options -->
                        <div v-if="q.type === 'MULTIPLE_CHOICE'" class="space-y-2">
                            <div v-for="(opt, oidx) in q.options" :key="oidx" class="flex items-center gap-2 p-2 rounded-md" :class="q.answer_key == oidx ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-transparent'">
                                <div class="w-4 h-4 rounded-full border" :class="q.answer_key == oidx ? 'bg-green-500 border-green-500' : 'border-gray-300'"></div>
                                <span :class="q.answer_key == oidx ? 'text-green-700 font-medium' : 'text-gray-700'">{{ opt }}</span>
                                <span v-if="q.answer_key == oidx" class="text-green-600 text-xs ml-auto">? Correct Answer</span>
                            </div>
                        </div>

                        <!-- TF Options -->
                        <div v-if="q.type === 'TRUE_FALSE'" class="p-3 bg-blue-50 text-blue-800 rounded-md border border-blue-200">
                            <strong>Answer: </strong> {{ q.answer_key }}
                        </div>
                    </div>
                    <div>
                        <Link :href="route('admin.question_banks.questions.destroy', {bank: bank.id, question: q.id})" method="delete" as="button" class="text-red-500 hover:text-red-700">Delete</Link>
                    </div>
                </div>

                <div v-if="bank.questions.length === 0" class="text-center py-12 text-gray-500 bg-white shadow-sm sm:rounded-lg">
                    This bank is empty. Click "Add Question" to start building your test.
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
