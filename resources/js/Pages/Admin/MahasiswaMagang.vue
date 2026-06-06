<template>
    <Head title="Daftar Mahasiswa Magang" />
    <AppLayout title="Mahasiswa Magang" :menu-items="menuItems">
        <div class="space-y-4 animate-fade-in-up">
            <p class="text-white/50 text-sm">Daftar mahasiswa magang dan penugasan Dosen Pembimbing</p>
            <div v-if="applications.length===0" class="glass-card p-12 text-center text-white/40">Belum ada mahasiswa magang aktif</div>
            <div v-for="app in applications" :key="app.id" class="glass-card p-5 hover:bg-white/5 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-white">{{ app.user?.name }}</h4>
                        <p class="text-sm text-white/50">{{ app.company?.name || app.custom_company_name || '-' }}</p>
                        <p class="text-xs text-white/30 mt-1">Periode: {{ formatDate(app.period_start) }} - {{ formatDate(app.period_end) }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div v-if="app.supervisor" class="text-sm text-emerald-400">
                            Pembimbing: {{ app.supervisor.name }}
                        </div>
                        <div v-else class="flex gap-2 items-center">
                            <select v-model="selectedDosens[app.id]" class="input-field !py-1 text-sm max-w-[200px]">
                                <option value="" disabled>Pilih Dosen</option>
                                <option v-for="dosen in dosens" :key="dosen.id" :value="dosen.id">{{ dosen.name }}</option>
                            </select>
                            <button @click="assignDosen(app.id)" class="btn-primary btn-sm" :disabled="!selectedDosens[app.id]">Assign</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
const props = defineProps({ applications: Array, dosens: Array });
const selectedDosens = ref({});
const assignDosen = (appId) => {
    router.patch(route('admin.mahasiswa_magang.assign', appId), { dosen_id: selectedDosens.value[appId] });
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
