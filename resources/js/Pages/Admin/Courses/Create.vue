<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const form = useForm({
    code: '',
    title: '',
    description: '',
    duration: '',
    passing_grade: 80,
    status: 'DRAFT',
    thumbnail: null as File | null,
});

const thumbnailPreview = ref<string | null>(null);

const handleThumbnailChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.thumbnail = target.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbnailPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(target.files[0]);
    }
};

const submit = () => {
    form.post(route('admin.courses.store'));
};
</script>

<template>
    <Head title="Create Course" />

    <AdminLayout>
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        Create New Course
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <Link :href="route('admin.courses.index')" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                        Cancel
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-white shadow-sm ring-1 ring-slate-200 sm:rounded-xl">
                <div class="px-4 py-6 sm:p-8">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        
                        <div class="sm:col-span-2">
                            <label for="code" class="block text-sm font-medium leading-6 text-slate-900">Course Code</label>
                            <div class="mt-2">
                                <input type="text" id="code" v-model="form.code" required placeholder="e.g. OHS-101" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.code" class="mt-2 text-sm text-red-600">{{ form.errors.code }}</p>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="title" class="block text-sm font-medium leading-6 text-slate-900">Course Title</label>
                            <div class="mt-2">
                                <input type="text" id="title" v-model="form.title" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.title" class="mt-2 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium leading-6 text-slate-900">Description</label>
                            <div class="mt-2">
                                <textarea id="description" v-model="form.description" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"></textarea>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-slate-600">Write a few sentences about this course.</p>
                            <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="duration" class="block text-sm font-medium leading-6 text-slate-900">Duration (Minutes)</label>
                            <div class="mt-2">
                                <input type="number" id="duration" v-model="form.duration" min="1" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.duration" class="mt-2 text-sm text-red-600">{{ form.errors.duration }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="passing_grade" class="block text-sm font-medium leading-6 text-slate-900">Passing Grade (%)</label>
                            <div class="mt-2">
                                <input type="number" id="passing_grade" v-model="form.passing_grade" required min="0" max="100" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
                            </div>
                            <p v-if="form.errors.passing_grade" class="mt-2 text-sm text-red-600">{{ form.errors.passing_grade }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="status" class="block text-sm font-medium leading-6 text-slate-900">Status</label>
                            <div class="mt-2">
                                <select id="status" v-model="form.status" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="DRAFT">Draft</option>
                                    <option value="PUBLISHED">Published</option>
                                    <option value="ARCHIVED">Archived</option>
                                </select>
                            </div>
                            <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">{{ form.errors.status }}</p>
                        </div>

                        <div class="col-span-full">
                            <label for="thumbnail" class="block text-sm font-medium leading-6 text-slate-900">Thumbnail Image</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <div v-if="thumbnailPreview" class="h-24 w-32 object-cover rounded-md overflow-hidden bg-slate-100">
                                    <img :src="thumbnailPreview" class="h-full w-full object-cover" />
                                </div>
                                <div v-else class="h-24 w-32 rounded-md border border-dashed border-slate-300 flex items-center justify-center bg-slate-50">
                                    <svg class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="file" id="thumbnail" @change="handleThumbnailChange" accept="image/*" class="ml-5 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50" />
                            </div>
                            <p v-if="form.errors.thumbnail" class="mt-2 text-sm text-red-600">{{ form.errors.thumbnail }}</p>
                        </div>
                        
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-x-6 border-t border-slate-900/10 px-4 py-4 sm:px-8 bg-slate-50 rounded-b-xl">
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:opacity-50">
                        Save Course
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
