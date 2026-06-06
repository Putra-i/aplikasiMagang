<template>
    <Head title="Laporan" />
    <AppLayout title="Laporan Magang" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a :href="route('mahasiswa.laporan.template')" class="glass-card p-5 flex items-center gap-4 hover:bg-white/5 transition group">
                    <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <div><p class="font-semibold text-white group-hover:text-primary-300">Template Laporan</p><p class="text-xs text-white/40">Download template</p></div>
                </a>
                <button @click="showUpload = true" class="glass-card p-5 flex items-center gap-4 hover:bg-white/5 transition group text-left w-full">
                    <div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center"><svg class="w-6 h-6 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg></div>
                    <div><p class="font-semibold text-white group-hover:text-accent-300">Upload Laporan</p><p class="text-xs text-white/40">Kirim file laporan</p></div>
                </button>
            </div>
            <div v-if="hasSupervisor" class="glass-card p-4 border-primary-500/20 bg-primary-500/5">
                <p class="text-sm text-white/70">Dosen Pembimbing: <span class="text-white font-medium">{{ supervisorName }}</span></p>
            </div>
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-white/10"><h3 class="font-semibold text-white">Riwayat Laporan</h3></div>
                <div v-if="reports.length === 0" class="p-8 text-center text-white/40">Belum ada laporan diupload</div>
                <div v-for="r in reports" :key="r.id" class="flex items-center gap-4 p-4 border-b border-white/5 hover:bg-white/5">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center"><svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <div class="flex-1"><p class="text-sm text-white">Laporan #{{ r.id }}</p><p class="text-xs text-white/40">{{ new Date(r.created_at).toLocaleDateString('id-ID') }}</p></div>
                    <span :class="['badge', statusClass(r.status)]">{{ statusLabel(r.status) }}</span>
                    <p v-if="r.revision_notes && r.status==='revision'" class="text-xs text-yellow-300 mt-1">Catatan: {{ r.revision_notes }}</p>
                </div>
            </div>
        </div>
        <Modal :show="showUpload" @close="showUpload = false">
            <h3 class="text-lg font-bold text-white mb-4">Upload Laporan</h3>
            <form @submit.prevent="submitUpload">
                <FileUpload accept=".pdf,.doc,.docx" hint="PDF, DOC. Maks 10MB" @file-selected="f => uploadForm.file = f" :has-error="!!uploadForm.errors.file" :error-message="uploadForm.errors.file" />
                <div class="flex gap-3 justify-end mt-4">
                    <button type="button" @click="showUpload = false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="uploadForm.processing" class="btn-primary">Upload</button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import FileUpload from '@/Components/FileUpload.vue';
defineProps({ reports: Array, hasSupervisor: Boolean, supervisorName: String });
const showUpload = ref(false);
const uploadForm = useForm({ file: null });
const submitUpload = () => uploadForm.post(route('mahasiswa.laporan.upload'), { forceFormData: true, onSuccess: () => showUpload.value = false });
const statusLabel = s => ({ uploaded:'Terkirim', reviewed:'Direview', approved:'Disetujui', revision:'Perlu Revisi' }[s]||s);
const statusClass = s => ({ uploaded:'badge-info', approved:'badge-approved', revision:'badge-pending', reviewed:'badge-info' }[s]||'badge-info');
const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Pengajuan Surat Magang', routeName: 'mahasiswa.magang.custom', href: route('mahasiswa.magang.custom'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
