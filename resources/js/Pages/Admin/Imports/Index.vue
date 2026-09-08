<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({ batches: Array });

const formCreate = useForm({ file: null });
const formUpdate = useForm({ file: null });

const previewing = ref(false);
const showModal = ref(false);
const previewData = ref(null);
const currentType = ref(null); // 'CREATE' or 'UPDATE'

const submitPreview = async (type) => {
    const form = type === 'CREATE' ? formCreate : formUpdate;
    if (!form.file) return;

    previewing.value = true;
    currentType.value = type;

    const formData = new FormData();
    formData.append('file', form.file);
    formData.append('type', type);

    try {
        const response = await axios.post(route('admin.imports.preview'), formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        previewData.value = response.data;
        showModal.value = true;
    } catch (error) {
        alert(error.response?.data?.message || 'Terjadi kesalahan saat memproses file.');
    } finally {
        previewing.value = false;
    }
};

const confirmImport = () => {
    if (!previewData.value) return;
    previewing.value = true;

    const routeName = currentType.value === 'CREATE' ? 'admin.imports.storeCreate' : 'admin.imports.storeUpdate';
    
    router.post(route(routeName), {
        file_id: previewData.value.file_id,
        file_name: previewData.value.file_name
    }, {
        onSuccess: () => {
            showModal.value = false;
            previewData.value = null;
            formCreate.reset();
            formUpdate.reset();
        },
        onFinish: () => {
            previewing.value = false;
        }
    });
};

const closeModal = () => {
    showModal.value = false;
    previewData.value = null;
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
                            <code class="bg-blue-100 px-1 py-0.5 rounded font-mono text-xs text-blue-900">nik, name, email, nip, entity, principal, region, area, position, department, join_date, password</code>
                        </p>
                        <p class="text-xs text-blue-600 font-semibold mt-2">
                            ATURAN SISTEM MULTI-PRINCIPAL: <br/>
                            1. IMPORT CREATE: Hanya untuk memasukkan user baru. Ditolak jika user sudah ada.<br/>
                            2. IMPORT UPDATE: Hanya untuk update riwayat pekerjaan/pindah Principal user lama. Ditolak jika user belum ada.
                        </p>
                    </div>
                    <div>
                        <a :href="route('admin.imports.template')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Download CSV Template
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Create Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-green-500">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Import Create (User Baru)</h3>
                        <p class="text-xs text-gray-500 mb-4">Gunakan fitur ini HANYA jika file CSV berisi pengguna yang sama sekali belum pernah masuk ke LMS.</p>
                        <form @submit.prevent="submitPreview('CREATE')" class="space-y-4">
                            <div>
                                <input type="file" accept=".csv" @input="formCreate.file = $event.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <button type="submit" :disabled="previewing" class="w-full bg-green-600 text-white px-6 py-2 rounded-md shadow-sm hover:bg-green-700 disabled:opacity-50">
                                {{ previewing && currentType === 'CREATE' ? 'Memproses...' : 'Preview Import Create' }}
                            </button>
                        </form>
                    </div>

                    <!-- Update Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-amber-500">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Import Update (Update Riwayat Pekerjaan)</h3>
                        <p class="text-xs text-gray-500 mb-4">Gunakan fitur ini HANYA jika Anda ingin memindahkan user lama ke Principal/Posisi baru.</p>
                        <form @submit.prevent="submitPreview('UPDATE')" class="space-y-4">
                            <div>
                                <input type="file" accept=".csv" @input="formUpdate.file = $event.target.files[0]" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <button type="submit" :disabled="previewing" class="w-full bg-amber-500 text-white px-6 py-2 rounded-md shadow-sm hover:bg-amber-600 disabled:opacity-50">
                                {{ previewing && currentType === 'UPDATE' ? 'Memproses...' : 'Preview Import Update' }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Preview Modal -->
        <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                    Preview Data Import ({{ currentType }})
                                </h3>
                                <div class="mt-4 bg-gray-50 p-4 rounded-md shadow-inner flex justify-between">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-500 font-semibold">Total Baris</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ previewData.total }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-green-600 font-semibold">Valid (Siap Proses)</p>
                                        <p class="text-2xl font-bold text-green-700">{{ previewData.valid }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-red-600 font-semibold">Error (Ditolak)</p>
                                        <p class="text-2xl font-bold text-red-700">{{ previewData.errors }}</p>
                                    </div>
                                </div>

                                <div v-if="previewData.errors > 0" class="mt-4">
                                    <h4 class="text-sm font-bold text-red-800 mb-2">Detail Error (Max 50 ditampilkan)</h4>
                                    <div class="max-h-48 overflow-y-auto border border-red-200 rounded-md">
                                        <table class="min-w-full divide-y divide-red-200 text-xs text-left">
                                            <thead class="bg-red-50">
                                                <tr>
                                                    <th class="px-4 py-2 font-medium text-red-900">Baris</th>
                                                    <th class="px-4 py-2 font-medium text-red-900">Pesan Error</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-red-100">
                                                <tr v-for="err in previewData.error_details" :key="err.row">
                                                    <td class="px-4 py-2 text-red-800 font-bold">#{{ err.row }}</td>
                                                    <td class="px-4 py-2 text-red-600">{{ err.message }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 italic">*Catatan: Baris yang error akan ditolak secara otomatis, sedangkan baris yang valid akan tetap diproses jika Anda menekan Konfirmasi.</p>
                                </div>
                                <div v-else class="mt-4 p-4 bg-green-50 text-green-800 rounded-md text-sm border border-green-200">
                                    ? File sempurna! Semua baris tervalidasi dan siap untuk diimpor.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" :disabled="previewing" @click="confirmImport" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            {{ previewing ? 'Memproses...' : 'Konfirmasi & Proses' }}
                        </button>
                        <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>
