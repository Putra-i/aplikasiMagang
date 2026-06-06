<template>
    <aside :class="[
        'fixed top-0 left-0 z-50 h-full w-64 transition-transform duration-300 ease-in-out',
        'glass-card border-0 border-r border-white/10 rounded-none flex flex-col',
        isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]">
        <!-- Logo -->
        <div class="p-5 border-b border-white/10">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 flex items-center justify-center">
                    <img src="/TMJ.png" alt="Logo TMJ" class="max-w-full max-h-full object-contain">
                </div>
                <div>
                    <h2 class="font-bold text-white text-sm">SPM TMJ PNUP</h2>
                    <p class="text-xs text-white/50">Sistem Pendaftaran Magang</p>
                </div>
                <button @click="$emit('close')" class="lg:hidden ml-auto text-white/50 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Items -->
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <a
                v-for="item in menuItems"
                :key="item.route"
                :href="item.href"
                @click.prevent="navigate(item)"
                :class="[
                    'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200',
                    isActive(item)
                        ? 'bg-primary-500/10 text-primary-700 border border-primary-500/30 shadow-sm'
                        : 'text-white/70 hover:text-white hover:bg-black/5'
                ]"
            >
                <span v-html="item.icon" class="w-5 h-5 shrink-0"></span>
                <span>{{ item.label }}</span>
                <span v-if="item.badge" class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                    {{ item.badge }}
                </span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-3 border-t border-white/10">
            <button
                @click="$emit('logout')"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-400/80 hover:text-red-300 hover:bg-red-500/10 w-full transition-all duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    menuItems: { type: Array, default: () => [] },
    isOpen: { type: Boolean, default: false },
});

defineEmits(['close', 'logout']);

const page = usePage();

const isActive = (item) => {
    if (item.routeName) {
        return route().current(item.routeName);
    }
    return false;
};

const navigate = (item) => {
    if (item.action === 'logout') {
        return;
    }
    if (item.href) {
        router.visit(item.href);
    }
};
</script>
