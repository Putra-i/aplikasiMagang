<template>
    <Head title="Daftar Magang" />
    <AppLayout title="Daftar Magang" :menu-items="menuItems">
        <div class="space-y-6 animate-fade-in-up">
            <div class="flex justify-between items-center">
                <p class="text-white/60 text-sm">Pilih perusahaan untuk mendaftar magang</p>
                <a :href="route('mahasiswa.magang.custom')" @click.prevent="$inertia.visit(route('mahasiswa.magang.custom'))" class="btn-primary text-sm">+ Pengajuan Tempat Baru</a>
            </div>
            <div v-if="$page.props.errors?.apply" class="glass-card border-red-500/30 bg-red-500/10 p-4 text-red-300 text-sm">{{ $page.props.errors.apply }}</div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a v-for="c in companies" :key="c.id" :href="route('mahasiswa.magang.company', c.id)" @click.prevent="$inertia.visit(route('mahasiswa.magang.company', c.id))"
                   class="glass-card p-5 hover:border-primary-500/40 hover:bg-white/5 transition-all group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500/20 to-accent-500/20 flex items-center justify-center shrink-0 text-xl font-bold text-primary-300">
                            {{ c.name.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-white group-hover:text-primary-300 transition truncate">{{ c.name }}</h3>
                            <p class="text-xs text-white/40 mt-1 line-clamp-2">{{ c.description || 'Tidak ada deskripsi' }}</p>
                            <p v-if="c.address" class="text-xs text-white/30 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ c.address }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div v-if="companies.length === 0" class="glass-card p-12 text-center">
                <p class="text-white/40">Belum ada perusahaan tersedia</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
defineProps({ companies: Array });
const menuItems = [
    { label: 'Dashboard', routeName: 'mahasiswa.dashboard', href: route('mahasiswa.dashboard'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>' },
    { label: 'Daftar Perusahaan', routeName: 'mahasiswa.magang', href: route('mahasiswa.magang'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' },
    { label: 'Pengajuan Surat Magang', routeName: 'mahasiswa.magang.custom', href: route('mahasiswa.magang.custom'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
    { label: 'Laporan Magang', routeName: 'mahasiswa.laporan', href: route('mahasiswa.laporan'), icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
];
</script>
