<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps<{
    course: any;
}>();

// Module Form
const showModuleForm = ref(false);
const editingModule = ref<any>(null);
const moduleForm = useForm({
    title: '',
    description: ''
});

const openNewModuleForm = () => {
    editingModule.value = null;
    moduleForm.reset();
    showModuleForm.value = true;
};

const openEditModuleForm = (module: any) => {
    editingModule.value = module;
    moduleForm.title = module.title;
    moduleForm.description = module.description || '';
    showModuleForm.value = true;
};

const saveModule = () => {
    if (editingModule.value) {
        moduleForm.put(route('admin.courses.modules.update', [props.course.id, editingModule.value.id]), {
            onSuccess: () => { showModuleForm.value = false; }
        });
    } else {
        moduleForm.post(route('admin.courses.modules.store', props.course.id), {
            onSuccess: () => { showModuleForm.value = false; }
        });
    }
};

const deleteModule = (module: any) => {
    if(confirm(`Are you sure you want to delete module: ${module.title}?`)) {
        router.delete(route('admin.courses.modules.destroy', [props.course.id, module.id]));
    }
};

// Lesson Form
const showLessonForm = ref(false);
const activeModuleForLesson = ref<any>(null);
const editingLesson = ref<any>(null);
const lessonForm = useForm({
    title: '',
    content: '',
    video_url: '',
    duration_minutes: ''
});

const openNewLessonForm = (module: any) => {
    activeModuleForLesson.value = module;
    editingLesson.value = null;
    lessonForm.reset();
    showLessonForm.value = true;
};

const openEditLessonForm = (module: any, lesson: any) => {
    activeModuleForLesson.value = module;
    editingLesson.value = lesson;
    lessonForm.title = lesson.title;
    lessonForm.content = lesson.content || '';
    lessonForm.video_url = lesson.video_url || '';
    lessonForm.duration_minutes = lesson.duration_minutes || '';
    showLessonForm.value = true;
};

const saveLesson = () => {
    if (editingLesson.value) {
        lessonForm.put(route('admin.courses.modules.lessons.update', [props.course.id, activeModuleForLesson.value.id, editingLesson.value.id]), {
            onSuccess: () => { showLessonForm.value = false; }
        });
    } else {
        lessonForm.post(route('admin.courses.modules.lessons.store', [props.course.id, activeModuleForLesson.value.id]), {
            onSuccess: () => { showLessonForm.value = false; }
        });
    }
};

const deleteLesson = (module: any, lesson: any) => {
    if(confirm(`Are you sure you want to delete lesson: ${lesson.title}?`)) {
        router.delete(route('admin.courses.modules.lessons.destroy', [props.course.id, module.id, lesson.id]));
    }
};

</script>

<template>
    <Head :title="`Builder: ${course.title}`" />

    <AdminLayout>
        <div v-if="$page.props.flash?.success" class="mb-4 bg-green-50 p-4 rounded-md flex items-center shadow-sm">
            <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
        </div>

        <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        Course Builder: {{ course.title }}
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0 space-x-3">
                    <Link :href="route('admin.courses.index')" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                        Back to Courses
                    </Link>
                    <button @click="openNewModuleForm" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Add Module
                    </button>
                </div>
            </div>

            <!-- Modules List -->
            <div class="space-y-6">
                <div v-if="course.modules.length === 0" class="text-center bg-white rounded-lg shadow-sm ring-1 ring-slate-200 py-12">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">No modules</h3>
                    <p class="mt-1 text-sm text-slate-500">Get started by creating a new module.</p>
                    <div class="mt-6">
                        <button @click="openNewModuleForm" type="button" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            Add Module
                        </button>
                    </div>
                </div>

                <div v-for="(module, index) in course.modules" :key="module.id" class="bg-white rounded-lg shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-slate-900">
                                Module {{ index + 1 }}: {{ module.title }}
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-slate-500" v-if="module.description">{{ module.description }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="openEditModuleForm(module)" class="text-sm text-primary-600 hover:text-primary-900">Edit</button>
                            <span class="text-slate-300">|</span>
                            <button @click="deleteModule(module)" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-200 px-4 py-4 sm:px-6">
                        <div class="space-y-4">
                            <!-- Lessons inside Module -->
                            <div v-for="(lesson, lIndex) in module.lessons" :key="lesson.id" class="flex justify-between items-center bg-slate-50 border border-slate-200 rounded-md p-3 hover:bg-slate-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        <svg class="h-6 w-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0l6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 014.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 00-.38 1.21 12.035 12.035 0 007.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 011.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 01-2.25 2.25h-2.25z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-slate-900">Lesson {{ lIndex + 1 }}: {{ lesson.title }}</h4>
                                        <p class="text-xs text-slate-500">
                                            <span v-if="lesson.duration_minutes">{{ lesson.duration_minutes }} min</span>
                                            <span v-if="lesson.duration_minutes && lesson.video_url"> &bull; </span>
                                            <span v-if="lesson.video_url">Video Attached</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <button @click="openEditLessonForm(module, lesson)" class="text-sm text-primary-600 hover:text-primary-900">Edit</button>
                                    <button @click="deleteLesson(module, lesson)" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                </div>
                            </div>
                            
                            <div v-if="module.lessons.length === 0" class="text-center py-4 text-sm text-slate-500">
                                No lessons in this module yet.
                            </div>
                            
                            <button @click="openNewLessonForm(module)" class="mt-2 w-full flex justify-center items-center px-4 py-2 border border-dashed border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Add Lesson
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Modal -->
        <div v-if="showModuleForm" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div>
                            <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-title">
                                {{ editingModule ? 'Edit Module' : 'Add New Module' }}
                            </h3>
                            <form @submit.prevent="saveModule" class="mt-4">
                                <div class="space-y-4">
                                    <div>
                                        <label for="module-title" class="block text-sm font-medium leading-6 text-slate-900">Title</label>
                                        <div class="mt-2">
                                            <input type="text" id="module-title" v-model="moduleForm.title" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                                        </div>
                                        <p v-if="moduleForm.errors.title" class="mt-2 text-sm text-red-600">{{ moduleForm.errors.title }}</p>
                                    </div>
                                    <div>
                                        <label for="module-desc" class="block text-sm font-medium leading-6 text-slate-900">Description</label>
                                        <div class="mt-2">
                                            <textarea id="module-desc" v-model="moduleForm.description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                    <button type="submit" :disabled="moduleForm.processing" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:col-start-2 disabled:opacity-50">
                                        Save
                                    </button>
                                    <button @click="showModuleForm = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:col-start-1 sm:mt-0">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Modal -->
        <div v-if="showLessonForm" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                        <div>
                            <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-title">
                                {{ editingLesson ? 'Edit Lesson' : 'Add New Lesson' }} 
                                <span class="text-sm font-normal text-slate-500">in {{ activeModuleForLesson?.title }}</span>
                            </h3>
                            <form @submit.prevent="saveLesson" class="mt-4">
                                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label for="lesson-title" class="block text-sm font-medium leading-6 text-slate-900">Title</label>
                                        <div class="mt-2">
                                            <input type="text" id="lesson-title" v-model="lessonForm.title" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="lesson-video" class="block text-sm font-medium leading-6 text-slate-900">Video URL (YouTube/MP4)</label>
                                        <div class="mt-2">
                                            <input type="url" id="lesson-video" v-model="lessonForm.video_url" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="lesson-duration" class="block text-sm font-medium leading-6 text-slate-900">Duration (Minutes)</label>
                                        <div class="mt-2">
                                            <input type="number" id="lesson-duration" v-model="lessonForm.duration_minutes" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="lesson-content" class="block text-sm font-medium leading-6 text-slate-900">Content (Markdown/Text)</label>
                                        <div class="mt-2">
                                            <textarea id="lesson-content" v-model="lessonForm.content" rows="6" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                    <button type="submit" :disabled="lessonForm.processing" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:col-start-2 disabled:opacity-50">
                                        Save Lesson
                                    </button>
                                    <button @click="showLessonForm = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:col-start-1 sm:mt-0">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>
