<template>
    <Head title="Pengajuan Tempat Magang Baru" />
    <AppLayout title="Pengajuan Baru" :menu-items="menuItems">
        <div class="max-w-2xl mx-auto animate-fade-in-up">
            <a :href="route('mahasiswa.magang')" @click.prevent="$inertia.visit(route('mahasiswa.magang'))" class="text-primary-400 text-sm mb-4 inline-block">← Kembali</a>
            <div class="glass-card p-6">
                <h2 class="text-lg font-bold text-white mb-4">Form Pengajuan</h2>
                <form @submit.prevent="showConfirm = true" class="space-y-4">
                    <div><label class="block text-sm text-white/70 mb-1">Instansi / Perusahaan *</label><input v-model="form.custom_company_name" class="input-field" required /></div>
                    <div><label class="block text-sm text-white/70 mb-1">Alamat Lengkap *</label><textarea v-model="form.custom_company_address" class="input-field min-h-[80px]" required></textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm text-white/70 mb-1">Periode Mulai *</label><input v-model="form.period_start" type="date" class="input-field" required /></div>
                        <div><label class="block text-sm text-white/70 mb-1">Periode Selesai *</label><input v-model="form.period_end" type="date" class="input-field" required /></div>
                    </div>
                    <button type="submit" class="btn-primary w-full mt-4">Kirim Pengajuan</button>
                </form>
            </div>
        </div>
        <Modal :show="showConfirm" @close="showConfirm = false">
            <h3 class="text-lg font-bold text-white mb-2">Konfirmasi</h3>
            <p class="text-white/60 text-sm mb-4">Masukkan password untuk konfirmasi.</p>
            <input v-model="form.password" type="password" class="input-field mb-3" placeholder="Password" />
            <p v-if="form.errors.password" class="text-red-400 text-xs mb-3">{{ form.errors.password }}</p>
            <div class="flex gap-3 justify-end">
                <button @click="showConfirm = false" class="btn-ghost">Batal</button>
                <button @click="form.post(route('mahasiswa.magang.custom.submit'),{forceFormData:true,onSuccess:()=>showConfirm=false})" :disabled="form.processing" class="btn-primary">Konfirmasi</button>
            </div>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
const showConfirm = ref(false);
const form = useForm({ custom_company_name:'', custom_company_address:'', period_start:'', period_end:'', password:'' });
const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Pengajuan Surat Magang', routeName: 'mahasiswa.magang.custom', href: route('mahasiswa.magang.custom'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
