<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ question_banks: Array });
const form = useForm({ title: '', description: '' });
const isAdding = ref(false);

const submit = () => {
    form.post(route('admin.question_banks.store'), {
        onSuccess: () => {
            form.reset();
            isAdding.value = false;
        }
    });
};
</script>

<template>
    <Head title="Question Banks" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assessment: Question Banks</h2>
                <button @click="isAdding = !isAdding" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    {{ isAdding ? 'Cancel' : 'Add New Bank' }}
                </button>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Add Form -->
                <div v-if="isAdding" class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Question Bank</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input v-model="form.title" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700">Save</button>
                    </form>
                </div>

                <!-- List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Questions</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="bank in question_banks" :key="bank.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ bank.title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ bank.questions_count }} Questions</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('admin.question_banks.show', bank.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Manage Questions</Link>
                                    <Link :href="route('admin.question_banks.destroy', bank.id)" method="delete" as="button" class="text-red-600 hover:text-red-900">Delete</Link>
                                </td>
                            </tr>
                            <tr v-if="question_banks.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No question banks found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
