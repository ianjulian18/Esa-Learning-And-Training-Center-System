<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ batches: Array });
const formCreate = useForm({ file: null });
const formUpdate = useForm({ file: null });

const submitCreate = () => { 
    formCreate.post(route('admin.imports.storeCreate'), {
        onSuccess: () => formCreate.reset()
    }); 
};

const submitUpdate = () => { 
    formUpdate.post(route('admin.imports.storeUpdate'), {
        onSuccess: () => formUpdate.reset()
    }); 
};
</script>

<template>
    <Head title="Bulk Import" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Identity: Bulk Import Users</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Information & Download Template -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 shadow-sm sm:rounded-lg flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Panduan Bulk Import</h3>
                        <p class="text-sm text-blue-700 mb-2">
                            Pastikan baris pertama Excel/CSV memiliki judul kolom persis seperti ini: <br/>
                            <code class="bg-blue-100 px-1 py-0.5 rounded font-mono text-xs text-blue-900">nik, name, email, nip, entity, principal, region, area, position, department, join_date</code>
                        </p>
                        <p class="text-xs text-blue-600 font-semibold mt-2">
                            ATURAN SISTEM MULTI-PRINCIPAL: <br/>
                            1. IMPORT CREATE: Hanya untuk memasukkan user baru. Ditolak jika user sudah ada.<br/>
                            2. IMPORT UPDATE: Hanya untuk update riwayat pekerjaan/pindah Principal user lama. Ditolak jika user belum ada.
                        </p>
                    </div>
                    <div>
                        <a :href="route('admin.imports.template')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download CSV Template
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Create Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-green-500">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Import Create (User Baru)</h3>
                        <p class="text-xs text-gray-500 mb-4">Gunakan fitur ini HANYA jika file CSV berisi pengguna yang sama sekali belum pernah masuk ke LMS.</p>
                        <form @submit.prevent="submitCreate" class="space-y-4">
                            <div>
                                <input type="file" accept=".csv" @input="formCreate.file = $event.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <button type="submit" :disabled="formCreate.processing" class="w-full bg-green-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-green-700 disabled:opacity-50">Jalankan Import Create</button>
                        </form>
                    </div>

                    <!-- Update Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-amber-500">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Import Update (Update Riwayat Pekerjaan)</h3>
                        <p class="text-xs text-gray-500 mb-4">Gunakan fitur ini HANYA jika Anda ingin memindahkan user lama ke Principal/Posisi baru.</p>
                        <form @submit.prevent="submitUpdate" class="space-y-4">
                            <div>
                                <input type="file" accept=".csv" @input="formUpdate.file = $event.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <button type="submit" :disabled="formUpdate.processing" class="w-full bg-amber-500 text-white px-6 py-2 rounded-md shadow-sm hover:bg-amber-600 disabled:opacity-50">Jalankan Import Update</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>

