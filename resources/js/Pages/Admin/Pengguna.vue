<template>
    <Head title="Pengguna" />
    <AppLayout title="Kelola Pengguna" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">

            <div class="flex justify-end">
                <button @click="showAdd=true" class="btn-primary text-sm">+ Tambah User</button>
            </div>
            <DataTable :columns="cols" :data="users" searchable :search-keys="['name','id_pengguna','role']">
                <template #cell-role="{value}">
                    <span class="badge badge-info capitalize">{{ value }}</span>
                </template>
            </DataTable>
        </div>
        <Modal :show="showAdd" @close="showAdd=false">
            <h3 class="text-lg font-bold text-white mb-4">Tambah User</h3>
            <form @submit.prevent="submitAdd" class="space-y-3">
                <div><label class="block text-sm text-white/70 mb-1">Nama Lengkap</label><input v-model="addForm.name" class="input-field" required /></div>
                <div><label class="block text-sm text-white/70 mb-1">NIP / NIM</label><input v-model="addForm.id_pengguna" type="tel" inputmode="numeric" pattern="[0-9]{8}" maxlength="8" class="input-field" required /><p v-if="addForm.errors.id_pengguna" class="text-red-400 text-xs mt-1">{{ addForm.errors.id_pengguna }}</p></div>
                <div><label class="block text-sm text-white/70 mb-1">Role</label>
                    <select v-model="addForm.role" class="input-field">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen Pembimbing</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="text-xs text-white/50 bg-white/5 p-2 rounded border border-white/10 mt-2">
                    Catatan: Password akan diset secara otomatis sama dengan ID Pengguna.
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" @click="showAdd=false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="addForm.processing" class="btn-primary">Simpan</button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
defineProps({ users: Array });
const showAdd = ref(false);
const addForm = useForm({ name:'', id_pengguna:'', role:'mahasiswa' });
const submitAdd = () => addForm.post(route('admin.pengguna.store'), { onSuccess:()=>{ showAdd.value=false; addForm.reset(); } });
const cols = [{ key:'name', label:'Nama' },{ key:'id_pengguna', label:'ID Pengguna' },{ key:'role', label:'Role' }];
const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Surat Pengantar', routeName:'admin.surat_pengantar', href:route('admin.surat_pengantar'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
