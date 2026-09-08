<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({ assessments: Array });

// Fetch courses for the dropdown
const courses = ref([]);
onMounted(async () => {
    const res = await fetch(route('admin.courses.index'), { headers: { 'Accept': 'application/json' } });
    if(res.ok) {
        const data = await res.json();
        // Assuming course list returns JSON, or we can just pass courses as props. 
        // Wait, Inertia pass courses as props is better. But I don't have courses in AssessmentController.
        // Let's use a text input for Course ID for now, or assume the user adds assessments inside Course page.
    }
});

const form = useForm({
    course_id: '',
    type: 'POST_TEST',
    total_questions: 10,
    passing_grade: 80
});

const isAdding = ref(false);

const submit = () => {
    // We don't have a direct /admin/assessments store route without course. 
    // The route is POST /admin/courses/{course}/assessments.
    alert("To add an assessment to a course, please go to the Courses module, edit the Course, and add the Assessment from there (Architectural limitation).");
    isAdding.value = false;
};
</script>

<template>
    <Head title="Assessments" />
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assessment: Tests & Exams</h2>
                <button @click="isAdding = !isAdding" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Attach Assessment to Course</button>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div v-if="isAdding" class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-sm text-yellow-700">
                        Based on the architectural rules, Assessments are strictly tied to Courses. To create a new Assessment, please navigate to <strong>Courses & Modules</strong>, select a Course, and attach an Assessment there.
                    </p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Passing Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="a in assessments" :key="a.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ a.course.title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold">{{ a.type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ a.total_questions }} Questions</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ a.passing_grade }}%</td>
                            </tr>
                            <tr v-if="assessments.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No assessments found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
