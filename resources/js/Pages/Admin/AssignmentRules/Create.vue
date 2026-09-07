<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    principals: Array,
    courses: Array,
    departments: Array,
    positions: Array,
});

const form = useForm({
    principal_id: '',
    course_id: '',
    department_id: '',
    position_ids: [],
    effective_date: '',
    expiry_date: ''
});

const submit = () => {
    form.post(route('admin.assignment_rules.store'));
};
</script>

<template>
    <Head title="Create Assignment Rule" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Assignment Rule</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="mb-6 text-gray-600">The Assignment Engine will automatically grant access to this course for users who match the criteria below.</p>
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Principal</label>
                                <select v-model="form.principal_id" required class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="" disabled>Select Principal</option>
                                    <option v-for="p in principals" :key="p.id" :value="p.id">{{ p.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Course</label>
                                <select v-model="form.course_id" required class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="" disabled>Select Target Course</option>
                                    <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.title }}</option>
                                </select>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium border-b pt-4 pb-2">Target Audience Criteria</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Department (Optional)</label>
                                <select v-model="form.department_id" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Any Department</option>
                                    <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Positions (Multiple allowed)</label>
                                <select v-model="form.position_ids" multiple required class="mt-1 block w-full rounded-md border-gray-300 h-32">
                                    <option v-for="pos in positions" :key="pos.id" :value="pos.id">{{ pos.name }}</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Hold CTRL/CMD to select multiple.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t pt-4">
                            <div>
                                <label class="block text-sm font-medium">Effective Date</label>
                                <input v-model="form.effective_date" type="date" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Expiry Date (Optional)</label>
                                <input v-model="form.expiry_date" type="date" class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <Link :href="route('admin.assignment_rules.index')" class="text-sm text-gray-600 mr-4">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Save & Activate Rule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
