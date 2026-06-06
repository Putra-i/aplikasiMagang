<template>
    <Head title="Dashboard Mahasiswa" />
    <AppLayout title="Dashboard" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Status Magang</p>
                            <p class="text-white font-semibold">{{ activeApplication ? statusLabel(activeApplication.status) : 'Belum mendaftar' }}</p>
                        </div>
                    </div>
                    <div v-if="activeApplication" class="mt-4 pt-4 border-t border-white/10 space-y-2">
                        <p class="text-sm text-white/60">Perusahaan: <span class="text-white">{{ activeApplication.company?.name || activeApplication.custom_company_name || '-' }}</span></p>
                        <p v-if="activeApplication.supervisor" class="text-sm text-white/60">Dosen Pembimbing: <span class="text-white">{{ activeApplication.supervisor.name }}</span></p>
                    </div>
                </div>
                <div class="glass-card p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-sm">Laporan</p>
                            <p class="text-white font-semibold">{{ latestReport ? reportStatusLabel(latestReport.status) : 'Belum diupload' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6">
                <h3 class="font-semibold text-white mb-4">Alur & Informasi Magang</h3>
                <ul class="list-disc pl-5 text-sm text-white/80 space-y-2">
                    <li>Pilih tempat magang yang bekerja sama dengan JTIK melalui menu <strong class="text-white">Daftar Perusahaan</strong></li>
                    <li>Gunakan menu <strong class="text-white">Pengajuan Surat Magang</strong> jika Anda ingin mendaftar di perusahaan yang belum terdaftar.</li>
                    <li>Setelah pengajuan Anda disetujui, Admin akan menerbitkan Surat Pengantar yang bisa Anda unduh di sini, dan menetapkan Dosen Pembimbing untuk Anda.</li>
                    <li>Lakukan kegiatan magang sesuai jadwal dan pastikan Anda mengunggah <strong class="text-white">Laporan Magang</strong> secara berkala.</li>
                </ul>
            </div>

            <div v-if="activeApplication && activeApplication.status !== 'rejected'" class="glass-card p-6">
                <h3 class="font-semibold text-white mb-6">Progres Pengajuan Magang</h3>
                <div class="relative flex flex-col sm:flex-row justify-between">
                    <!-- connecting line (horizontal) -->
                    <div class="hidden sm:block absolute top-4 left-[15%] right-[15%] h-0.5 bg-white/10 z-0"></div>
                    
                    <!-- Step 1 -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/3 mb-6 sm:mb-0 relative">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm bg-primary-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)] ring-4 ring-slate-900/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="sm:text-center">
                            <p class="text-sm font-bold text-primary-700">Pengajuan Terkirim</p>
                            <p class="text-xs text-white/50 hidden sm:block">Berhasil dikirim</p>
                        </div>
                        <!-- vertical line mobile -->
                        <div class="sm:hidden absolute left-4 top-8 bottom-[-24px] w-0.5 bg-white/10 -z-10"></div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/3 mb-6 sm:mb-0 relative">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors ring-4 ring-slate-900/50', ['processing', 'approved_admin', 'approved'].includes(activeApplication.status) ? 'bg-primary-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' : (activeApplication.status === 'pending' ? 'bg-primary-500/20 text-primary-600 border border-primary-500/30' : 'bg-white/10 text-white/40')]">
                            <svg v-if="['processing', 'approved_admin', 'approved'].includes(activeApplication.status)" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span v-else>2</span>
                        </div>
                        <div class="sm:text-center">
                            <p :class="['text-sm font-bold', ['processing', 'approved_admin', 'approved'].includes(activeApplication.status) ? 'text-primary-700' : 'text-white/80']">Diproses Admin</p>
                            <p class="text-xs text-white/50 hidden sm:block">
                                {{ activeApplication.status === 'pending' ? 'Menunggu verifikasi' : (activeApplication.status === 'processing' ? 'Sedang dibuatkan surat' : 'Selesai diproses') }}
                            </p>
                        </div>
                        <!-- vertical line mobile -->
                        <div class="sm:hidden absolute left-4 top-8 bottom-[-24px] w-0.5 bg-white/10 -z-10"></div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-row sm:flex-col items-center gap-4 sm:gap-2 z-10 w-full sm:w-1/3 relative">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors ring-4 ring-slate-900/50', !!activeApplication.letter_file ? 'bg-accent-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'bg-white/10 text-white/40']">
                            <svg v-if="!!activeApplication.letter_file" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span v-else>3</span>
                        </div>
                        <div class="sm:text-center">
                            <p :class="['text-sm font-bold', !!activeApplication.letter_file ? 'text-accent-600' : 'text-white/50']">Surat Diterbitkan</p>
                            <p class="text-xs text-white/50 hidden sm:block">Siap diunduh</p>
                            <div v-if="activeApplication.letter_file" class="mt-2 sm:mt-3">
                                <a :href="'/storage/' + activeApplication.letter_file" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold bg-accent-500/10 border border-accent-500/20 text-accent-600 px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg hover:bg-accent-500/20 transition">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh Surat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
const props = defineProps({ activeApplication: Object, latestReport: Object });
const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Pengajuan Surat Magang', routeName: 'mahasiswa.magang.custom', href: route('mahasiswa.magang.custom'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
const statusLabel = (s) => ({ pending: 'Menunggu Verifikasi', processing: 'Sedang Diproses', approved_admin: 'Disetujui Admin', approved: 'Aktif Magang', rejected: 'Ditolak' }[s] || s);
const reportStatusLabel = (s) => ({ uploaded: 'Terkirim', reviewed: 'Sedang Direview', approved: 'Disetujui', revision: 'Perlu Revisi' }[s] || s);
</script>
