<template>
    <Head :title="'Laporan - '+student.name" />
    <AppLayout :title="'Laporan: '+student.name" :menu-items="menuItems">
        <div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
            <a :href="route('dosen.laporan')" @click.prevent="$inertia.visit(route('dosen.laporan'))" class="text-primary-400 text-sm inline-block">← Kembali</a>
            <div class="glass-card p-5 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xl font-bold">{{ student.name.charAt(0) }}</div>
                <div>
                    <h3 class="font-semibold text-white text-lg">{{ student.name }}</h3>
                    <p class="text-sm text-white/50">{{ student.id_pengguna }} · {{ student.jurusan }} · {{ student.prodi }}</p>
                    <p v-if="application" class="text-xs text-white/30 mt-1">Magang di: {{ application.company?.name || application.custom_company_name }}</p>
                </div>
            </div>
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-white/10"><h3 class="font-semibold text-white">Laporan</h3></div>
                <div v-if="reports.length===0" class="p-8 text-center text-white/40">Belum ada laporan</div>
                <div v-for="r in reports" :key="r.id" class="p-4 border-b border-white/5 hover:bg-white/5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center"><svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                            <div>
                                <p class="text-sm text-white">Laporan #{{ r.id }}</p>
                                <p class="text-xs text-white/40">{{ new Date(r.created_at).toLocaleDateString('id-ID') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span :class="['badge',r.status==='approved'?'badge-approved':r.status==='revision'?'badge-pending':'badge-info']">{{ statusLabel(r.status) }}</span>
                            <a :href="'/storage/'+r.file_path" target="_blank" class="btn-ghost btn-sm">Lihat</a>
                            <button v-if="r.status!=='approved'" @click="$inertia.patch(route('dosen.laporan.approve',r.id))" class="btn-primary btn-sm">ACC</button>
                            <button v-if="r.status!=='approved'" @click="reviseTarget=r" class="btn-ghost btn-sm border-yellow-500/30 text-yellow-600">Revisi</button>
                        </div>
                    </div>
                    <p v-if="r.revision_notes" class="text-xs text-yellow-300/70 mt-2 ml-13">Catatan: {{ r.revision_notes }}</p>
                </div>
            </div>
        </div>
        <Modal :show="!!reviseTarget" @close="reviseTarget=null">
            <h3 class="text-lg font-bold text-white mb-3">Catatan Revisi</h3>
            <textarea v-model="revisionNotes" class="input-field min-h-[100px] mb-4" placeholder="Tuliskan catatan revisi..."></textarea>
            <div class="flex gap-3 justify-end">
                <button @click="reviseTarget=null" class="btn-ghost">Batal</button>
                <button @click="doRevise" class="btn-primary" :disabled="!revisionNotes">Kirim Revisi</button>
            </div>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
defineProps({ student: Object, reports: Array, application: Object });
const reviseTarget=ref(null); const revisionNotes=ref('');
const doRevise=()=>{router.patch(route('dosen.laporan.revise',reviseTarget.value.id),{revision_notes:revisionNotes.value});reviseTarget.value=null;revisionNotes.value='';};
const statusLabel=s=>({uploaded:'Terkirim',reviewed:'Direview',approved:'Disetujui',revision:'Perlu Revisi'}[s]||s);
const menuItems = [
    {label:'Dashboard',routeName:'dosen.dashboard',href:route('dosen.dashboard'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>'},
    {label:'Laporan Magang',routeName:'dosen.laporan',href:route('dosen.laporan'),icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'},
];
</script>
