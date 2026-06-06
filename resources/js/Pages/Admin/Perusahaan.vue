<template>
    <Head title="Perusahaan" />
    <AppLayout title="Daftar Perusahaan" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="flex justify-end"><button @click="openAdd" class="btn-primary text-sm">+ Tambah Perusahaan</button></div>
            <DataTable :columns="cols" :data="companies" searchable :search-keys="['name','address']">
                <template #rowActions="{row}">
                    <div class="flex gap-2">
                        <button @click="openEdit(row)" class="btn-ghost btn-sm">Edit</button>
                        <button @click="confirmDelete=row" class="btn-danger btn-sm">Hapus</button>
                    </div>
                </template>
            </DataTable>
        </div>
        <Modal :show="showForm" @close="showForm=false" max-width="lg">
            <h3 class="text-lg font-bold text-white mb-4">{{ editing ? 'Edit' : 'Tambah' }} Perusahaan</h3>
            <form @submit.prevent="submitForm" class="space-y-3">
                <div><label class="block text-sm text-white/70 mb-1">Nama Perusahaan *</label><input v-model="form.name" class="input-field" required /></div>
                <div><label class="block text-sm text-white/70 mb-1">URL Logo</label><input v-model="form.logo_url" class="input-field" /></div>
                <div><label class="block text-sm text-white/70 mb-1">Deskripsi</label><textarea v-model="form.description" class="input-field min-h-[60px]"></textarea></div>
                <div><label class="block text-sm text-white/70 mb-1">Alamat</label><textarea v-model="form.address" class="input-field min-h-[60px]"></textarea></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-sm text-white/70 mb-1">Jam Operasional</label><input v-model="form.operational_hours" class="input-field" /></div>
                    <div><label class="block text-sm text-white/70 mb-1">Telepon</label><input v-model="form.phone" class="input-field" /></div>
                </div>
                <div><label class="block text-sm text-white/70 mb-1">Email</label><input v-model="form.email" type="email" class="input-field" /></div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" @click="showForm=false" class="btn-ghost">Batal</button>
                    <button type="submit" :disabled="form.processing" class="btn-primary">Simpan</button>
                </div>
            </form>
        </Modal>
        <Modal :show="!!confirmDelete" @close="confirmDelete=null">
            <div class="text-center">
                <h3 class="text-lg font-bold text-white mb-2">Hapus Perusahaan?</h3>
                <p class="text-white/60 text-sm mb-4">{{ confirmDelete?.name }} akan dihapus permanen.</p>
                <div class="flex gap-3 justify-center">
                    <button @click="confirmDelete=null" class="btn-ghost">Batal</button>
                    <button @click="doDelete" class="btn-danger">Hapus</button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
defineProps({ companies: Array });
const showForm = ref(false); const editing = ref(null); const confirmDelete = ref(null);
const form = useForm({ name:'',logo_url:'',description:'',address:'',operational_hours:'',phone:'',email:'' });
const openAdd = () => { editing.value=null; form.reset(); showForm.value=true; };
const openEdit = (c) => { editing.value=c; Object.keys(form.data()).forEach(k=>form[k]=c[k]||''); showForm.value=true; };
const submitForm = () => {
    if(editing.value) form.put(route('admin.perusahaan.update',editing.value.id),{onSuccess:()=>showForm.value=false});
    else form.post(route('admin.perusahaan.store'),{onSuccess:()=>{showForm.value=false;form.reset();}});
};
const doDelete = () => { router.delete(route('admin.perusahaan.destroy',confirmDelete.value.id)); confirmDelete.value=null; };
const cols = [{key:'name',label:'Nama'},{key:'address',label:'Alamat'},{key:'phone',label:'Telepon'},{key:'email',label:'Email'}];
const menuItems = [
    { label:'Dashboard', routeName:'admin.dashboard', href:route('admin.dashboard'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label:'Pengguna', routeName:'admin.pengguna', href:route('admin.pengguna'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Daftar Perusahaan', routeName:'admin.perusahaan', href:route('admin.perusahaan'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label:'Daftar Mahasiswa Magang', routeName:'admin.mahasiswa_magang', href:route('admin.mahasiswa_magang'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' },
    { label:'Surat Pengantar', routeName:'admin.surat_pengantar', href:route('admin.surat_pengantar'), icon:'<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
