<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ banks: Array });

const form = useForm({ title: '', description: '' });

const submit = () => {
    form.post(route('admin.question_banks.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Question Banks" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Question Banks</h2>
        </template>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-3 gap-6">
            <div class="col-span-2 space-y-4">
                <div v-for="bank in banks" :key="bank.id" class="bg-white p-4 shadow rounded-lg flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg">{{ bank.title }}</h3>
                        <p class="text-sm text-gray-500">{{ bank.description }}</p>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded mt-2 inline-block">{{ bank.questions_count }} Questions</span>
                    </div>
                    <Link :href="route('admin.question_banks.show', bank.id)" class="text-indigo-600 hover:text-indigo-800 font-medium">Manage Questions</Link>
                </div>
            </div>
            <div>
                <form @submit.prevent="submit" class="bg-white p-4 shadow rounded-lg">
                    <h3 class="font-bold mb-4">Create Bank</h3>
                    <input v-model="form.title" type="text" placeholder="Title" class="w-full rounded border-gray-300 mb-2" required>
                    <textarea v-model="form.description" placeholder="Description" class="w-full rounded border-gray-300 mb-4"></textarea>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded shadow hover:bg-indigo-700">Save</button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
