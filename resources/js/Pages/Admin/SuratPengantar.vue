<template>
    <Head title="Pembuatan Surat Pengantar" />
    <AppLayout title="Surat Pengantar" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <p class="text-white/50 text-sm">Daftar pengajuan magang yang memerlukan Surat Pengantar</p>
            <div v-if="applications.length===0" class="glass-card p-12 text-center text-white/40">Tidak ada pengajuan yang perlu dibuatkan surat</div>
            <div v-for="app in applications" :key="app.id" class="glass-card p-5 hover:bg-white/5 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-white">{{ app.user?.name }}</h4>
                        <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name || 'Pengajuan Mandiri' }}</p>
                        <p class="text-xs text-white/30 mt-1">Periode: {{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span v-if="app.status === 'processing'" class="text-xs text-blue-600 bg-blue-500/10 px-2 py-1 rounded border border-blue-500/20">Sedang Diproses</span>
                        <button v-if="app.status === 'pending'" @click="prosesSurat(app.id)" class="btn-ghost btn-sm border-blue-500/30 text-blue-600 hover:bg-blue-500/10">Proses</button>
                        <button @click="openModal(app)" class="btn-primary btn-sm">Terbitkan Surat</button>
                    </div>
                </div>
            </div>
        </div>
        
        <Modal :show="!!selectedApp" @close="selectedApp = null">
            <h3 class="text-lg font-bold text-white mb-2">Terbitkan Surat Pengantar</h3>
            <p class="text-white/60 text-sm mb-4">Silakan unggah surat pengantar untuk mahasiswa <strong class="text-white">{{ selectedApp?.user?.name }}</strong>.</p>
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label class="block text-sm text-white/70 mb-1">Upload File Surat (PDF/JPG/PNG)</label>
                    <FileUpload accept=".pdf,.jpg,.jpeg,.png" @file-selected="f => form.letter_file = f" />
                    <p v-if="form.errors.letter_file" class="text-red-400 text-xs mt-1">{{ form.errors.letter_file }}</p>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="selectedApp = null" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="form.processing || !form.letter_file" class="btn-primary">
                        {{ form.processing ? 'Mengunggah...' : 'Upload & Terbitkan' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import FileUpload from '@/Components/FileUpload.vue';

defineProps({ applications: Array });

const selectedApp = ref(null);
const form = useForm({ letter_file: null });

const prosesSurat = (appId) => {
    if (confirm('Ubah status pengajuan ini menjadi Sedang Diproses?')) {
        router.patch(route('admin.surat_pengantar.proses', appId));
    }
};

const openModal = (app) => {
    selectedApp.value = app;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (!selectedApp.value) return;
    form.post(route('admin.surat_pengantar.terbitkan', selectedApp.value.id), {
        forceFormData: true,
        onSuccess: () => { selectedApp.value = null; }
    });
};

const formatDate = d => d ? new Date(d).toLocaleDateString('id-ID') : '-';
const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Surat Pengantar', routeName:'admin.surat_pengantar', href:route('admin.surat_pengantar'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
