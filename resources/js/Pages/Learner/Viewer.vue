<script setup>
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    enrollment: Object,
    progress: Array
});

const course = props.enrollment.course;
const activeLesson = ref(null);

// Find first uncompleted lesson or default to first lesson
const initLesson = () => {
    let firstUncompleted = null;
    let found = false;
    for (let mod of course.modules) {
        for (let les of mod.lessons) {
            if (!firstUncompleted) firstUncompleted = les; // save first overall
            const prog = props.progress.find(p => p.lesson_id === les.id);
            if (!prog || !prog.is_completed) {
                activeLesson.value = les;
                found = true;
                break;
            }
        }
        if(found) break;
    }
    if (!found) activeLesson.value = firstUncompleted;
};

initLesson();

const canViewLesson = (lessonId) => {
    let previousCompleted = true;
    for (let mod of course.modules) {
        for (let les of mod.lessons) {
            if (les.id === lessonId) return previousCompleted;
            const p = props.progress.find(x => x.lesson_id === les.id);
            if (!p || !p.is_completed) {
                previousCompleted = false;
            }
        }
    }
    return false;
};

const selectLesson = (les) => {
    if (canViewLesson(les.id) || isCompleted(les.id)) {
        activeLesson.value = les;
    } else {
        alert('You must complete previous lessons first.');
    }
};

const isCompleted = (lessonId) => {
    const p = props.progress.find(x => x.lesson_id === lessonId);
    return p ? p.is_completed : false;
};

const markComplete = async () => {
    if (!activeLesson.value) return;
    
    try {
        const res = await fetch(route('learner.progress.update'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                course_id: course.id,
                lesson_id: activeLesson.value.id,
                last_watched_position: 100,
                is_completed: 1
            })
        });
        
        if (res.ok) {
            // Locally update UI progress array
            const existing = props.progress.find(p => p.lesson_id === activeLesson.value.id);
            if(existing) existing.is_completed = true;
            else props.progress.push({ lesson_id: activeLesson.value.id, is_completed: true });
            
            alert('Lesson marked as complete!');
        }
    } catch (e) {
        console.error(e);
    }
};

</script>

<template>
    <Head :title="course.title" />
    <LearnerLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('learner.my-courses')" class="text-gray-500 hover:text-gray-700">? Back</Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ course.title }}</h2>
            </div>
        </template>

        <div class="py-6 h-[calc(100vh-140px)] flex">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex gap-6 h-full">
                
                <!-- Main Content Area -->
                <div class="flex-1 bg-white shadow sm:rounded-lg overflow-hidden flex flex-col">
                    <div v-if="activeLesson" class="flex-1 bg-black flex items-center justify-center relative">
                        <div v-if="activeLesson.materials?.[0]?.type === 'VIDEO_UPLOAD'" class="w-full h-full">
                            <video controls class="w-full h-full bg-black">
                                <source :src="activeLesson.materials[0].source_url" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div v-else-if="activeLesson.materials?.[0]?.type === 'VIDEO'" class="w-full h-full">
                            <iframe v-if="activeLesson.materials[0].source_url" :src="activeLesson.materials[0].source_url" class="w-full h-full border-0" allowfullscreen></iframe>
                            <div v-else class="text-white text-center p-8">No Video URL provided</div>
                        </div>
                        <div v-else-if="['DOCUMENT', 'DOCUMENT_UPLOAD'].includes(activeLesson.materials?.[0]?.type)" class="w-full h-full bg-gray-200">
                             <iframe v-if="activeLesson.materials[0].source_url" :src="activeLesson.materials[0].source_url" class="w-full h-full border-0"></iframe>
                             <div v-else class="text-gray-500 text-center p-8">No Document URL provided</div>
                        </div>
                        <div v-else-if="activeLesson.materials?.[0]?.type === 'TEXT'" class="w-full h-full bg-white p-8 overflow-y-auto prose max-w-none">
                            <h2 class="text-2xl font-bold mb-4">{{ activeLesson.title }}</h2>
                            <div class="whitespace-pre-wrap">{{ activeLesson.materials[0].source_url }}</div>
                        </div>
                        <div v-else class="w-full h-full bg-white flex items-center justify-center text-gray-500">
                            No content available for this lesson.
                        </div>
                    </div>
                    
                    <div v-if="activeLesson" class="p-6 border-t border-gray-200 flex justify-between items-center bg-gray-50">
                        <div>
                            <h3 class="font-bold text-lg">{{ activeLesson.title }}</h3>
                            <p class="text-sm text-gray-500">{{ activeLesson.materials?.[0]?.type || 'UNKNOWN' }}</p>
                        </div>
                        <div>
                            <button v-if="!isCompleted(activeLesson.id)" @click="markComplete" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">Mark Complete</button>
                            <span v-else class="text-green-600 font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Completed
                            </span>
                        </div>
                    </div>
                <div v-if="course.assessments && course.assessments.length > 0" class="mt-4 p-4 bg-indigo-50 rounded border border-indigo-200">
                    <h4 class="font-bold text-indigo-900 mb-2">Final Assessment</h4>
                    <p class="text-xs text-indigo-700 mb-3">You must complete all lessons to unlock the final assessment.</p>
                    <Link :href="route('learner.courses.assessments.show', [course.id, course.assessments[0].id])" 
                          class="block text-center w-full bg-indigo-600 text-white font-bold py-2 rounded shadow hover:bg-indigo-700">
                        Take Assessment
                    </Link>
                </div>
                </div>

                <!-- Syllabus Sidebar -->
                <div class="w-80 bg-white shadow sm:rounded-lg flex flex-col overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800">Course Syllabus</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div v-for="mod in course.modules" :key="mod.id">
                            <h4 class="font-bold text-sm text-gray-900 mb-2">Module {{ mod.order }}: {{ mod.title }}</h4>
                            <ul class="space-y-1">
                                <li v-for="les in mod.lessons" :key="les.id">
                                    <button @click="selectLesson(les)" 
                                            :disabled="!canViewLesson(les.id) && !isCompleted(les.id)"
                                            :class="['w-full text-left disabled:opacity-50 disabled:cursor-not-allowed px-3 py-2 rounded text-sm flex items-start gap-2 transition', 
                                                     activeLesson?.id === les.id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-gray-50 text-gray-600']">
                                        <div class="mt-0.5">
                                            <svg v-if="isCompleted(les.id)" class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <svg v-else-if="les.content_type==='VIDEO'" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span>{{ les.title }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                <div v-if="course.assessments && course.assessments.length > 0" class="mt-4 p-4 bg-indigo-50 rounded border border-indigo-200">
                    <h4 class="font-bold text-indigo-900 mb-2">Final Assessment</h4>
                    <p class="text-xs text-indigo-700 mb-3">You must complete all lessons to unlock the final assessment.</p>
                    <Link :href="route('learner.courses.assessments.show', [course.id, course.assessments[0].id])" 
                          class="block text-center w-full bg-indigo-600 text-white font-bold py-2 rounded shadow hover:bg-indigo-700">
                        Take Assessment
                    </Link>
                </div>
                </div>

            </div>
        </div>
    </LearnerLayout>
</template>



