<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    Entitys: Array
});

const isEditing = ref(false);
const editId = ref(null);

const form = useForm({
    code: '',
    name: ''
});

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.Entitys.update', editId.value), {
            onSuccess: () => reset()
        });
    } else {
        form.post(route('admin.Entitys.store'), {
            onSuccess: () => reset()
        });
    }
};

const edit = (item) => {
    isEditing.value = true;
    editId.value = item.id;
    form.code = item.code;
    form.name = item.name;
};

const reset = () => {
    isEditing.value = false;
    editId.value = null;
    form.reset();
};

const destroy = (id) => {
    if (confirm('Are you sure you want to delete this Entity?')) {
        router.delete(route('admin.Entitys.destroy', id));
    }
};
</script>

<template>
    <Head title="Entitys" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Data: Entitys</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Form -->
                <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ isEditing ? 'Edit Entity' : 'Add New Entity' }}</h3>
                    <form @submit.prevent="submit" class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Code</label>
                            <input v-model="form.code" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <div v-if="form.errors.code" class="text-red-500 text-xs mt-1">{{ form.errors.code }}</div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="reset" v-if="isEditing" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow-sm hover:bg-gray-300">Cancel</button>
                            <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 disabled:opacity-50">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in Entitys" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="edit(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button @click="destroy(item.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="Entitys.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No data available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

