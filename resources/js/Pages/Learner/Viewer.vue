<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    course: any;
}>();

const currentLesson = ref(null);
const currentModule = ref(null);
</script>

<template>
    <Head :title="course.title" />

    <div class="h-screen flex flex-col bg-slate-900 text-white">
        <!-- Topbar -->
        <header class="h-14 flex items-center justify-between px-4 border-b border-slate-700 bg-slate-900 shrink-0">
            <div class="flex items-center space-x-4">
                <Link :href="route('learner.courses')" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h1 class="text-sm font-semibold truncate">{{ course.title }}</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center text-xs space-x-2">
                    <span class="text-slate-400">Your Progress:</span>
                    <span class="font-bold">0%</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Video Area -->
            <main class="flex-1 relative bg-black flex items-center justify-center">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-500">
                    <svg class="h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>Select a lesson from the sidebar to start watching.</p>
                </div>
                
                <!-- Video Controls Overlay Mockup -->
                <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-black/80 to-transparent flex items-end px-4 pb-4">
                    <div class="w-full flex items-center space-x-4">
                        <button class="text-white hover:text-primary-400">
                            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="flex-1 h-1 bg-slate-600 rounded-full cursor-pointer relative">
                            <div class="absolute top-0 left-0 h-full bg-primary-500 rounded-full" style="width: 30%"></div>
                        </div>
                        <span class="text-xs text-white">03:45 / 12:00</span>
                    </div>
                </div>
            </main>

            <!-- Sidebar Syllabus -->
            <aside class="w-80 bg-slate-800 border-l border-slate-700 flex flex-col shrink-0">
                <div class="p-4 border-b border-slate-700">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider">Course Content</h2>
                </div>
                
                <div class="flex-1 overflow-y-auto">
                    <!-- Module Mockup -->
                    <div class="border-b border-slate-700">
                        <button class="w-full flex items-center justify-between p-4 bg-slate-800 hover:bg-slate-700 transition-colors">
                            <div class="flex flex-col items-start text-left">
                                <span class="text-xs font-semibold text-primary-400">Module 1</span>
                                <span class="text-sm font-medium text-white">Introduction</span>
                            </div>
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="bg-slate-900 py-2">
                            <!-- Lesson Mockup -->
                            <button class="w-full flex items-center px-4 py-2 hover:bg-slate-800 transition-colors group">
                                <div class="shrink-0 mr-3 text-slate-500 group-hover:text-white">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <span class="text-sm text-slate-300 group-hover:text-white">Welcome to the Course</span>
                                </div>
                            </button>
                            <!-- Quiz Mockup -->
                            <Link :href="route('learner.assessment', course.id)" class="w-full flex items-center px-4 py-2 hover:bg-slate-800 transition-colors group">
                                <div class="shrink-0 mr-3 text-primary-500 group-hover:text-primary-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <span class="text-sm text-primary-400 group-hover:text-primary-300 font-medium">Post-Test Assessment</span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>
