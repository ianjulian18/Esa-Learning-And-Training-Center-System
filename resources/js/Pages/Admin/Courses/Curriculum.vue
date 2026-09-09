<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    course: Object
});

const moduleForm = useForm({
    title: '',
    order: 1
});

const lessonForm = useForm({
    title: '',
    material_type: 'YOUTUBE',
    video_url: '',
    content: '',
    file: null,
    duration_minutes: '',
    order: 1
});

const handleFileUpload = (e) => {
    const target = e.target;
    if (target && target.files && target.files.length > 0) {
        lessonForm.file = target.files[0];
    }
};

const activeModuleId = ref(null);
const editingLessonId = ref(null);

const addModule = () => {
    moduleForm.order = (props.course.modules?.length || 0) + 1;
    moduleForm.post(route('admin.courses.modules.store', props.course.id), {
        preserveScroll: true,
        onSuccess: () => {
            moduleForm.reset();
            alert('Module added successfully!');
        }
    });
};

const deleteModule = (modId) => {
    if(confirm('Delete this module?')) {
        router.delete(route('admin.courses.modules.destroy', [props.course.id, modId]));
    }
};

const assessmentForm = useForm({
    type: 'POST_TEST',
    total_questions: 10,
    passing_grade: 70
});

const saveAssessment = () => {
    assessmentForm.post(route('admin.courses.assessments.store', props.course.id), {
        preserveScroll: true,
        onSuccess: () => alert('Assessment Saved')
    });
};

const editLesson = (modId, lesson) => {
    activeModuleId.value = modId;
    editingLessonId.value = lesson.id;
    
    let mType = 'YOUTUBE';
    let vUrl = '';
    let txtContent = '';
    
    if (lesson.materials && lesson.materials.length > 0) {
        const mat = lesson.materials[0];
        if (mat.type === 'VIDEO_UPLOAD') mType = 'VIDEO_UPLOAD';
        else if (mat.type === 'DOCUMENT_UPLOAD') mType = 'DOCUMENT_UPLOAD';
        else if (mat.type === 'TEXT') { mType = 'TEXT'; txtContent = mat.source_url; }
        else { mType = 'YOUTUBE'; vUrl = mat.source_url; }
    }

    lessonForm.title = lesson.title;
    lessonForm.material_type = mType;
    lessonForm.video_url = vUrl;
    lessonForm.content = txtContent;
    lessonForm.file = null;
    lessonForm.duration_minutes = lesson.materials?.[0]?.duration ? Math.round(lesson.materials[0].duration / 60) : '';
};

const cancelLessonForm = () => {
    lessonForm.reset();
    activeModuleId.value = null;
    editingLessonId.value = null;
};

const saveLesson = (modId) => {
    if (editingLessonId.value) {
        lessonForm.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.modules.lessons.update', [modId, editingLessonId.value]), {
            onSuccess: () => cancelLessonForm()
        });
    } else {
        const mod = props.course.modules.find(m => m.id === modId);
        lessonForm.order = (mod.lessons?.length || 0) + 1;
        lessonForm.post(route('admin.modules.lessons.store', modId), {
            preserveScroll: true,
            onSuccess: () => {
                cancelLessonForm();
                alert('Lesson added successfully!');
            }
        });
    }
};

const deleteLesson = (modId, lessonId) => {
    if(confirm('Delete this lesson?')) {
        router.delete(route('admin.modules.lessons.destroy', [modId, lessonId]));
    }
};
</script>

<template>
    <Head title="Curriculum Builder" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Curriculum: {{ course.title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
                
                <!-- Curriculum List -->
                <div class="flex-1 space-y-4">
                    <div v-for="(mod, mIdx) in course.modules" :key="mod.id" class="bg-white p-4 shadow rounded-lg border-l-4 border-indigo-500">
                        <div class="flex justify-between items-center mb-2 border-b pb-2">
                            <h3 class="text-lg font-bold">Module {{ mod.order }}: {{ mod.title }}</h3>
                            <div>
                                <button @click="activeModuleId = mod.id" class="text-sm bg-green-100 text-green-700 px-2 py-1 rounded mr-2 hover:bg-green-200">+ Add Lesson</button>
                                <button @click="deleteModule(mod.id)" class="text-sm text-red-500 hover:text-red-700">Delete</button>
                            </div>
                        </div>

                        <!-- Lessons -->
                        <div class="space-y-2 mt-2">
                            <div v-for="lesson in mod.lessons" :key="lesson.id" class="bg-gray-50 p-2 rounded flex justify-between items-center text-sm border">
                                <div class="flex items-center gap-2">
                                    <span class="bg-gray-200 px-2 rounded text-xs">{{ lesson.content_type }}</span>
                                    <span>{{ lesson.order }}. {{ lesson.title }}</span>
                                    <span v-if="lesson.duration" class="text-gray-500 text-xs">({{ lesson.duration }} min)</span>
                                </div>
                                <div>
                                    <button @click="editLesson(mod.id, lesson)" class="text-indigo-400 hover:text-indigo-600 text-xs mr-3 font-semibold">Edit</button>
                                    <button @click="deleteLesson(mod.id, lesson.id)" class="text-red-400 hover:text-red-600 font-bold">x</button>
                                </div>
                            </div>
                            <div v-if="!mod.lessons || mod.lessons.length === 0" class="text-sm text-gray-400 italic">No lessons yet.</div>
                        </div>

                        <!-- Add/Edit Lesson Form -->
                        <div v-if="activeModuleId === mod.id" class="mt-4 bg-gray-100 p-4 rounded-md border border-indigo-200">
                            <h4 class="text-sm font-semibold mb-2 text-indigo-900">{{ editingLessonId ? 'Edit Lesson' : 'New Lesson' }}</h4>
                            <form @submit.prevent="saveLesson(mod.id)" class="grid grid-cols-2 gap-2 text-sm">
                                <div class="col-span-2">
                                    <label>Title</label>
                                    <input v-model="lessonForm.title" type="text" required class="w-full rounded border-gray-300">
                                </div>
                                <div class="col-span-2">
                                    <label>Material Type</label>
                                    <select v-model="lessonForm.material_type" class="w-full rounded border-gray-300">
                                        <option value="YOUTUBE">YouTube Video Link</option>
                                        <option value="VIDEO_UPLOAD">Upload MP4 Video</option>
                                        <option value="DOCUMENT_UPLOAD">Upload PDF Document</option>
                                        <option value="TEXT">Text/Article</option>
                                    </select>
                                </div>
                                <div class="col-span-2" v-if="lessonForm.material_type === 'YOUTUBE'">
                                    <label>YouTube URL</label>
                                    <input v-model="lessonForm.video_url" type="url" class="w-full rounded border-gray-300">
                                </div>
                                <div class="col-span-2" v-if="['VIDEO_UPLOAD', 'DOCUMENT_UPLOAD'].includes(lessonForm.material_type)">
                                    <label>Upload File (Max 100MB)</label>
                                    <input type="file" :accept="lessonForm.material_type === 'VIDEO_UPLOAD' ? 'video/mp4' : 'application/pdf'" @change="handleFileUpload" class="w-full rounded border-gray-300 p-1 bg-white">
                                    <p v-if="lessonForm.errors.file" class="text-xs text-red-500 mt-1">{{ lessonForm.errors.file }}</p>
                                </div>
                                <div class="col-span-2" v-if="['YOUTUBE', 'VIDEO_UPLOAD'].includes(lessonForm.material_type)">
                                    <label>Duration (Minutes)</label>
                                    <input v-model="lessonForm.duration_minutes" type="number" class="w-full rounded border-gray-300">
                                </div>
                                <div class="col-span-2" v-if="lessonForm.material_type === 'TEXT'">
                                    <label>Content (Markdown/Text)</label>
                                    <textarea v-model="lessonForm.content" rows="4" class="w-full rounded border-gray-300"></textarea>
                                </div>
                                <div class="col-span-2 flex justify-end gap-2 mt-2">
                                    <button type="button" @click="cancelLessonForm" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                    <button type="submit" :disabled="lessonForm.processing" class="px-3 py-1 bg-indigo-600 text-white rounded">Save Lesson</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div v-if="!course.modules || course.modules.length === 0" class="bg-white p-6 text-center text-gray-500 shadow rounded-lg">
                        This course has no curriculum yet. Start by adding a module.
                    </div>
                </div>

                <!-- Assessment Settings Sidebar -->
                <div class="w-1/3 mt-6">
                    <div class="bg-white p-6 shadow sm:rounded-lg sticky top-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Course Assessment (Post Test)</h3>
                        <form @submit.prevent="saveAssessment">
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Type</label>
                                <select v-model="assessmentForm.type" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="POST_TEST">Post Test</option>
                                    <option value="PRE_TEST">Pre Test</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Total Questions to pull from Bank</label>
                                <input v-model="assessmentForm.total_questions" type="number" min="1" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium">Passing Grade (%)</label>
                                <input v-model="assessmentForm.passing_grade" type="number" min="1" max="100" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div class="mt-4">
                                <button type="submit" :disabled="assessmentForm.processing" class="w-full bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Module Sidebar -->
                <div class="w-1/3">
                    <div class="bg-white p-6 shadow sm:rounded-lg sticky top-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Module</h3>
                        <form @submit.prevent="addModule">
                            <div>
                                <label class="block text-sm font-medium">Module Title</label>
                                <input v-model="moduleForm.title" type="text" required class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div class="mt-4">
                                <button type="submit" :disabled="moduleForm.processing" class="w-full bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700">Add Module</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>





