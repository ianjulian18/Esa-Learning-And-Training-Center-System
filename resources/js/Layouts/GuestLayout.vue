<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    isAdminLogin?: boolean;
}>();
</script>

<template>
    <div
        :class="[
            'flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0',
            isAdminLogin ? 'bg-slate-900 text-white' : 'bg-gray-100 dark:bg-gray-900 text-gray-900'
        ]"
    >
        <div>
            <Link href="/">
                <ApplicationLogo :class="['h-20 w-20 fill-current', isAdminLogin ? 'text-indigo-400' : 'text-gray-500']" />
            </Link>
        </div>

        <h2 v-if="isAdminLogin" class="mt-6 text-2xl font-bold text-indigo-400">Administrator Portal</h2>
        <h2 v-else class="mt-6 text-2xl font-bold text-gray-700">Student & Learning Portal</h2>

        <div
            :class="[
                'mt-6 w-full overflow-hidden px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg',
                isAdminLogin ? 'bg-slate-800 border border-slate-700' : 'bg-white dark:bg-gray-800'
            ]"
        >
            <slot />
        </div>
    </div>
</template>
