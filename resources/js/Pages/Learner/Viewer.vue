<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import LearnerLayout from '@/Layouts/LearnerLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps<{
    course: any;
    enrollment: any;
    progressMap: Record<number, any>;
}>();

const activeModule = ref<any>(null);
const activeLesson = ref<any>(null);
const localProgressMap = ref<Record<number, any>>({});

onMounted(() => {
    localProgressMap.value = props.progressMap;
    // Set initial active lesson to the first uncompleted lesson
    let foundUncompleted = false;
    if (props.course.modules && props.course.modules.length > 0) {
        for (const module of props.course.modules) {
            for (const lesson of module.lessons) {
                const status = localProgressMap.value[lesson.id]?.status || 'NOT STARTED';
                if (status !== 'COMPLETED') {
                    activeModule.value = module;
                    activeLesson.value = lesson;
                    foundUncompleted = true;
                    break;
                }
            }
            if (foundUncompleted) break;
        }
        
        // If all completed, just show first lesson
        if (!foundUncompleted) {
            activeModule.value = props.course.modules[0];
            activeLesson.value = props.course.modules[0].lessons[0];
        }
    }
});

const selectLesson = (module: any, lesson: any) => {
    activeModule.value = module;
    activeLesson.value = lesson;
    
    // Ping backend to mark as IN PROGRESS if not started
    const currentStatus = localProgressMap.value[lesson.id]?.status;
    if (!currentStatus || currentStatus === 'NOT STARTED') {
        pingProgress(lesson.id, 'IN PROGRESS');
    }
};

const pingProgress = async (lessonId: number, status: string) => {
    try {
        const response = await axios.post(route('learner.progress', props.course.id), {
            lesson_id: lessonId,
            status: status
        });
        
        if (response.data.success) {
            localProgressMap.value[lessonId] = response.data.progress;
            
            // if completed, maybe auto-navigate
            if (status === 'COMPLETED') {
                autoNavigateNext();
            }
        }
    } catch (e) {
        console.error("Failed to ping progress", e);
    }
};

const autoNavigateNext = () => {
    // Find next lesson
    let foundCurrent = false;
    for (const module of props.course.modules) {
        for (const lesson of module.lessons) {
            if (foundCurrent) {
                selectLesson(module, lesson);
                return;
            }
            if (lesson.id === activeLesson.value.id) {
                foundCurrent = true;
            }
        }
    }
    
    // If no next lesson, we reached the end. Suggest assessment if course has one?
    // The component doesn't know if there is an assessment natively unless we check props.course.assessments_count
    // For now we just stay on the last lesson.
};

const markAsComplete = () => {
    if (activeLesson.value) {
        pingProgress(activeLesson.value.id, 'COMPLETED');
    }
};

const isLessonCompleted = (lessonId: number) => {
    return localProgressMap.value[lessonId]?.status === 'COMPLETED';
};

const totalLessons = computed(() => {
    let count = 0;
    props.course.modules.forEach((m:any) => count += m.lessons.length);
    return count;
});

const completedLessons = computed(() => {
    return Object.values(localProgressMap.value).filter(p => p.status === 'COMPLETED').length;
});

const progressPercentage = computed(() => {
    if (totalLessons.value === 0) return 0;
    return Math.round((completedLessons.value / totalLessons.value) * 100);
});

</script>

<template>
    <Head :title="course.title" />

    <LearnerLayout>
        <div class="flex h-[calc(100vh-4rem)]">
            <!-- Sidebar / Syllabus -->
            <div class="w-80 bg-white border-r border-slate-200 flex flex-col hidden md:flex h-full overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <Link :href="route('learner.courses')" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-900 mb-4 transition-colors">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Dashboard
                    </Link>
                    <h2 class="text-lg font-bold text-slate-900 line-clamp-2">{{ course.title }}</h2>
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                            <span>{{ completedLessons }} / {{ totalLessons }} completed</span>
                            <span>{{ progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-primary-600 h-1.5 rounded-full transition-all duration-500" :style="`width: ${progressPercentage}%`"></div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    <div v-for="(module, mIndex) in course.modules" :key="module.id">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2 uppercase tracking-wider">Module {{ mIndex + 1 }}: {{ module.title }}</h3>
                        <ul class="space-y-1">
                            <li v-for="(lesson, lIndex) in module.lessons" :key="lesson.id">
                                <button 
                                    @click="selectLesson(module, lesson)"
                                    class="w-full text-left flex items-start px-3 py-2 rounded-md text-sm transition-colors"
                                    :class="activeLesson?.id === lesson.id ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-600 hover:bg-slate-50'"
                                >
                                    <span class="mr-2 mt-0.5 text-slate-400" :class="isLessonCompleted(lesson.id) ? 'text-green-500' : ''">
                                        <svg v-if="isLessonCompleted(lesson.id)" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </span>
                                    <span class="flex-1 line-clamp-2 leading-tight">{{ lesson.title }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-200">
                    <Link :href="route('learner.assessment', course.id)" class="w-full flex justify-center items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-colors" :class="progressPercentage < 100 ? 'opacity-50 cursor-not-allowed' : ''">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Take Assessment
                    </Link>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col h-full bg-slate-50 overflow-hidden">
                <div v-if="activeLesson" class="flex-1 overflow-y-auto">
                    <!-- Video Area -->
                    <div v-if="activeLesson.video_url" class="bg-black w-full aspect-video md:aspect-auto md:h-96 relative flex items-center justify-center">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                            <svg class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm">Video Player Placeholder</p>
                            <p class="text-xs mt-1 text-slate-500">{{ activeLesson.video_url }}</p>
                        </div>
                    </div>

                    <!-- Text Content -->
                    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
                        <h1 class="text-3xl font-bold text-slate-900 mb-4">{{ activeLesson.title }}</h1>
                        
                        <div class="prose prose-slate max-w-none text-slate-700" v-if="activeLesson.content">
                            <!-- In a real app, use v-html with DOMPurify or a markdown renderer -->
                            <div class="whitespace-pre-line">{{ activeLesson.content }}</div>
                        </div>
                        <div v-else class="text-slate-500 italic mt-8">
                            No additional reading material for this lesson.
                        </div>

                        <div class="mt-12 pt-8 border-t border-slate-200 flex justify-end">
                            <button 
                                v-if="!isLessonCompleted(activeLesson.id)"
                                @click="markAsComplete" 
                                class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none transition-colors"
                            >
                                Mark as Complete & Next
                            </button>
                            <button 
                                v-else
                                @click="autoNavigateNext"
                                class="inline-flex justify-center items-center px-6 py-3 border border-slate-300 text-base font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-colors"
                            >
                                Go to Next Lesson
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="flex-1 flex items-center justify-center text-slate-500">
                    <p>Select a lesson to start learning.</p>
                </div>
            </div>
        </div>
    </LearnerLayout>
</template>
