<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    course: any;
}>();
</script>

<template>
    <Head title="Assessment" />

    <div class="min-h-screen bg-slate-50 flex flex-col">
        <header class="bg-white shadow-sm shrink-0 border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Post-Test: {{ course.title }}</h1>
                    <p class="text-xs text-slate-500">Question 1 of 10</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/10">
                        14:59 Remaining
                    </span>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-medium text-slate-900">1. What is the primary purpose of our new company culture initiative?</h2>
                </div>

                <div class="space-y-4">
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-slate-300 hover:border-primary-500 hover:bg-primary-50 transition-colors">
                        <input type="radio" name="answer" value="A" class="h-4 w-4 mt-1 cursor-pointer border-slate-300 text-primary-600 focus:ring-primary-600" />
                        <span class="ml-3 flex flex-col">
                            <span class="block text-sm font-medium text-slate-900">A. To increase working hours</span>
                        </span>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border bg-primary-50 p-4 shadow-sm focus:outline-none border-primary-500 ring-1 ring-primary-500 transition-colors">
                        <input type="radio" name="answer" value="B" checked class="h-4 w-4 mt-1 cursor-pointer border-slate-300 text-primary-600 focus:ring-primary-600" />
                        <span class="ml-3 flex flex-col">
                            <span class="block text-sm font-medium text-primary-900">B. To foster a collaborative and innovative environment</span>
                        </span>
                    </label>

                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-slate-300 hover:border-primary-500 hover:bg-primary-50 transition-colors">
                        <input type="radio" name="answer" value="C" class="h-4 w-4 mt-1 cursor-pointer border-slate-300 text-primary-600 focus:ring-primary-600" />
                        <span class="ml-3 flex flex-col">
                            <span class="block text-sm font-medium text-slate-900">C. To reduce employee benefits</span>
                        </span>
                    </label>
                </div>
            </div>
            
            <div class="mt-8 flex justify-between">
                <Link :href="route('learner.learn', course.id)" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                    Exit Quiz
                </Link>
                <button type="button" class="inline-flex items-center rounded-md bg-primary-600 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    Next Question
                </button>
            </div>
        </main>
    </div>
</template>
