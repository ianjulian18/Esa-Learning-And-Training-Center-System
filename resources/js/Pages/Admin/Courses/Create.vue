<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    principals: Array
});

const form = useForm({
    code: '',
    title: '',
    description: '',
    duration: '',
    passing_grade: 80,
    principal_ids: []
});

const submit = () => {
    form.post(route('admin.courses.store'));
};
</script>

<template>
    <Head title="Create Course" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Course</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Course Code</label>
                                <input v-model="form.code" type="text" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Course Title</label>
                                <input v-model="form.title" type="text" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Description</label>
                            <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Duration (Minutes, optional)</label>
                                <input v-model="form.duration" type="number" class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Passing Grade (%)</label>
                                <input v-model="form.passing_grade" type="number" min="0" max="100" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <h3 class="text-lg font-medium mb-2">Assign to Principals</h3>
                            <p class="text-sm text-gray-500 mb-4">Select which Principals own this course. You can select multiple.</p>
                            <div class="grid grid-cols-3 gap-4">
                                <label v-for="prin in principals" :key="prin.id" class="flex items-center space-x-2">
                                    <input type="checkbox" :value="prin.id" v-model="form.principal_ids" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700">{{ prin.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-4 border-t">
                            <Link :href="route('admin.courses.index')" class="text-sm text-gray-600 mr-4">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
