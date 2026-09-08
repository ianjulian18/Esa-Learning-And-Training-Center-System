<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-slate-200">
                <Link :href="route('admin.reports.index')">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-primary-600" />
                </Link>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
                <!-- Main -->
                <div>
                    <h3 class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Main</h3>
                    <div class="space-y-1">
                        <Link :href="route('admin.dashboard')" :class="{'bg-primary-50 text-primary-600': $page.url === '/admin/dashboard', 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $page.url !== '/admin/dashboard'}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            Dashboard
                        </Link>
                    </div>
                </div>

                <!-- Master Data -->
                <div>
                    <h3 class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Organization</h3>
                    <div class="space-y-1">
                        <Link :href="route('admin.entities.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/entities'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/entities')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25c0-.414.336-.75.75-.75h4.5c.414 0 .75.336.75.75V21" /></svg>
                            Entities
                        </Link>
                        <Link :href="route('admin.principals.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/principals'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/principals')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg>
                            Principals
                        </Link>
                        <Link :href="route('admin.regions.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/regions'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/regions')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" /></svg>
                            Regions
                        </Link>
                        <Link :href="route('admin.areas.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/areas'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/areas')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            Areas
                        </Link>
                        <Link :href="route('admin.departments.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/departments'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/departments')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                            Departments
                        </Link>
                        <Link :href="route('admin.positions.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/positions'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/positions')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                            Positions
                        </Link>
                    </div>
                </div>

                <!-- Identity & Imports -->
                <div>
                    <h3 class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Identity & Imports</h3>
                    <div class="space-y-1">
                        <Link :href="route('admin.users.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/users'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/users')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            Users
                        </Link>
                        <Link :href="route('admin.imports.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/imports'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/imports')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l3 3m0 0l3-3m-3 3V2.25" /></svg>
                            Bulk Import
                        </Link>
                    </div>
                </div>

                <!-- Learning & Automation -->
                <div>
                    <h3 class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Learning</h3>
                    <div class="space-y-1">
                        <Link :href="route('admin.courses.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/courses'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/courses')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                            Courses & Modules
                        </Link>
                        <Link :href="route('admin.assignment_rules.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/assignment_rules'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/assignment_rules')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" /></svg>
                            Assignment Engine
                        </Link>
                    </div>
                </div>

                <!-- Assessment & Quality -->
                <div>
                    <h3 class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 mt-4">Assessment</h3>
                    <div class="space-y-1">
                        <Link :href="route('admin.question_banks.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/question_banks'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/question_banks')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            Question Banks
                        </Link>
                        <Link :href="route('admin.assessments.index')" :class="{'bg-primary-50 text-primary-600': $page.url.startsWith('/admin/assessments'), 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': !$page.url.startsWith('/admin/assessments')}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" /></svg>
                            Assessments
                        </Link>
                    </div>
                </div>

            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-slate-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Mobile menu button -->
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="md:hidden text-slate-500 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <div class="flex-1 flex justify-end">
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-600 bg-white hover:text-slate-800 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
                        </header>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>




