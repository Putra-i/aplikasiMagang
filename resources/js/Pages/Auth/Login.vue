<template>
    <Head title="Login" />
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl"></div>
        </div>
        <div class="glass-card p-8 w-full max-w-md relative animate-fade-in-up">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="w-12 h-14 flex items-center justify-center">
                        <img src="/PNUP.png" alt="Logo PNUP" class="max-w-full max-h-full object-contain">
                    </div>
                    
                    <div class="w-12 h-14 flex items-center justify-center">
                        <img src="/TMJ.png" alt="Logo TMJ" class="max-w-full max-h-full object-contain">
                    </div>
                </div>
               <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Sistem Pendaftaran Magang</h1>

                <div class="mt-0.3 text-center">
                    <p class="text-[#2C5EAD] text-sm font-semibold tracking-wide">
                        Teknik Multimedia dan Jaringan
                    </p>
                    <p class="text-slate-500 text-xs mt-0.5 font-medium">
                        Politeknik Negeri Ujung Pandang
                    </p>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm">{{ $page.props.flash.success }}</div>

            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-1.5">NIP / NIM</label>
                        <input v-model="form.id_pengguna" type="text" class="input-field" required />
                        <p v-if="form.errors.id_pengguna" class="text-red-400 text-xs mt-1">{{ form.errors.id_pengguna }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-1.5">Password</label>
                        <input v-model="form.password" type="password" class="input-field"/>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.remember" type="checkbox" class="w-4 h-4 rounded bg-white/10 border-white/20 text-primary-500 focus:ring-primary-500" />
                            <span class="text-sm text-white/60">Ingat saya</span>
                        </label>
                    </div>
                    <button type="submit" :disabled="form.processing" class="btn-primary w-full flex items-center justify-center gap-2">
                        <div v-if="form.processing" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <span>{{ form.processing ? 'Masuk...' : 'Masuk' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
const form = useForm({ id_pengguna: '', password: '', remember: false });
const submit = () => form.post(route('login'));
</script>
