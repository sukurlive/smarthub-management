<template>
  <AppLayout>
    <!-- Welcome Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-slate-800 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 shadow-xl">
      <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl -z-10"></div>
      
      <div>
        <div class="flex items-center gap-3">
          <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-100">
            Selamat Datang Kembali, {{ userName }}!
          </h2>
          <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest text-cyan-400 bg-cyan-950/60 border border-cyan-800/80">
            {{ userRole }}
          </span>
        </div>
        <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-xl">
          Kelola inventaris peralatan studio kreatif Anda secara cerdas, aman, dan real-time dari satu dashboard interaktif.
        </p>
      </div>

      <!-- Action Quick Link Button -->
      <Link
        href="/loans"
        class="shrink-0 inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-5 py-3 rounded-xl text-xs transition-smooth shadow-lg shadow-cyan-500/10 active:scale-[0.98]"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Buat Peminjaman</span>
      </Link>
    </div>

    <!-- Statistical Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Admin View Stats -->
      <template v-if="userRole === 'admin'">
        <!-- Total Equipment Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px]">
          <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Total Peralatan</span>
            <h3 class="text-3xl font-extrabold text-slate-100 mt-2 tracking-tight">{{ stats.total_equipment }}</h3>
            <p class="text-[10px] text-slate-400 mt-1">Unit inventaris terdaftar</p>
          </div>
          <div class="w-12 h-12 bg-slate-800/80 rounded-xl flex items-center justify-center text-slate-300 border border-slate-700/60 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
          </div>
        </div>

        <!-- Available Equipment Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px]">
          <div>
            <span class="text-xs font-semibold text-teal-400/90 uppercase tracking-widest">Alat Tersedia</span>
            <h3 class="text-3xl font-extrabold text-teal-300 mt-2 tracking-tight">{{ stats.available_equipment }}</h3>
            <p class="text-[10px] text-teal-500/80 mt-1">Siap untuk dipinjam member</p>
          </div>
          <div class="w-12 h-12 bg-teal-500/10 rounded-xl flex items-center justify-center text-teal-400 border border-teal-500/20 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
          </div>
        </div>

        <!-- Borrowed Equipment Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px]">
          <div>
            <span class="text-xs font-semibold text-amber-400/90 uppercase tracking-widest">Alat Dipinjam</span>
            <h3 class="text-3xl font-extrabold text-amber-300 mt-2 tracking-tight">{{ stats.borrowed_equipment }}</h3>
            <p class="text-[10px] text-amber-500/80 mt-1">Sedang dipakai oleh member</p>
          </div>
          <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 border border-amber-500/20 shadow-inner">
            <svg xmlns="http://www/w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
          </div>
        </div>

        <!-- Maintenance Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px]">
          <div>
            <span class="text-xs font-semibold text-rose-400/90 uppercase tracking-widest">Alat Maintenance</span>
            <h3 class="text-3xl font-extrabold text-rose-300 mt-2 tracking-tight">{{ stats.maintenance_equipment }}</h3>
            <p class="text-[10px] text-rose-500/80 mt-1">Sedang perbaikan/perawatan</p>
          </div>
          <div class="w-12 h-12 bg-rose-500/10 rounded-xl flex items-center justify-center text-rose-400 border border-rose-500/20 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.67 2.67 0 1 1 13.5 17.25l-5.83-5.83m.02-1.04a4 4 0 1 1 5.66-5.66l-5.66 5.66Zm-5.44 4.87a2.2 2.2 0 0 0 3.12 0l.29-.3a2.2 2.2 0 0 0 0-3.12l-.3-.29a2.2 2.2 0 0 0-3.12 0l-.29.3a2.2 2.2 0 0 0 0 3.12l.3.29Z" />
            </svg>
          </div>
        </div>

        <!-- Active Loans Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px] sm:col-span-2 lg:col-span-1">
          <div>
            <span class="text-xs font-semibold text-cyan-400/90 uppercase tracking-widest">Transaksi Aktif</span>
            <h3 class="text-3xl font-extrabold text-cyan-300 mt-2 tracking-tight">{{ stats.active_loans }}</h3>
            <p class="text-[10px] text-cyan-500/80 mt-1">Peminjaman belum dikembalikan</p>
          </div>
          <div class="w-12 h-12 bg-cyan-500/10 rounded-xl flex items-center justify-center text-cyan-400 border border-cyan-500/20 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.35.134-.134m.743.743-.134-.134m.743.743-.134-.134m-.743-.743-.134.134m.743-.743-.134.134" />
            </svg>
          </div>
        </div>
      </template>

      <!-- Member View Stats -->
      <template v-else>
        <!-- Available Equipment Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px]">
          <div>
            <span class="text-xs font-semibold text-teal-400/90 uppercase tracking-widest">Katalog Alat Tersedia</span>
            <h3 class="text-3xl font-extrabold text-teal-300 mt-2 tracking-tight">{{ stats.available_equipment }}</h3>
            <p class="text-[10px] text-teal-500/80 mt-1">Alat studio siap dipinjam</p>
          </div>
          <div class="w-12 h-12 bg-teal-500/10 rounded-xl flex items-center justify-center text-teal-400 border border-teal-500/20 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
          </div>
        </div>

        <!-- My Active Loans Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px]">
          <div>
            <span class="text-xs font-semibold text-amber-400/90 uppercase tracking-widest">Peminjaman Aktif Saya</span>
            <h3 class="text-3xl font-extrabold text-amber-300 mt-2 tracking-tight">{{ stats.my_active_loans }}</h3>
            <p class="text-[10px] text-amber-500/80 mt-1">Alat sedang Anda gunakan</p>
          </div>
          <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 border border-amber-500/20 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
          </div>
        </div>

        <!-- My Total Loans Card -->
        <div class="glass-card rounded-2xl p-6 border border-slate-800 flex items-center justify-between transition-smooth hover:border-slate-700/80 hover:translate-y-[-2px] sm:col-span-2 lg:col-span-1">
          <div>
            <span class="text-xs font-semibold text-cyan-400/90 uppercase tracking-widest">Total Transaksi Saya</span>
            <h3 class="text-3xl font-extrabold text-cyan-300 mt-2 tracking-tight">{{ stats.my_total_loans }}</h3>
            <p class="text-[10px] text-cyan-500/80 mt-1">Seluruh riwayat peminjaman</p>
          </div>
          <div class="w-12 h-12 bg-cyan-500/10 rounded-xl flex items-center justify-center text-cyan-400 border border-cyan-500/20 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.35.134-.134m.743.743-.134-.134m.743.743-.134-.134m-.743-.743-.134.134m.743-.743-.134.134" />
            </svg>
          </div>
        </div>
      </template>
    </div>

    <!-- Quick Actions Card (Mobile Responsive Layout) -->
    <div class="glass-card rounded-2xl border border-slate-800 p-6 sm:p-8">
      <h3 class="text-sm font-bold tracking-wider text-slate-400 uppercase mb-4">Akses Cepat Modul</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Link 
          href="/equipment" 
          class="flex items-center justify-between p-4 rounded-xl bg-slate-900/60 hover:bg-slate-900 border border-slate-800/80 hover:border-cyan-500/30 group transition-smooth"
        >
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-slate-200 group-hover:text-cyan-400 transition-colors">Data Master Peralatan</h4>
              <p class="text-[10px] text-slate-500 mt-0.5">Lihat catalog, tambahkan alat baru, atau hapus item.</p>
            </div>
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-600 group-hover:text-cyan-400 group-hover:translate-x-1 transition-smooth">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
          </svg>
        </Link>

        <Link 
          href="/loans" 
          class="flex items-center justify-between p-4 rounded-xl bg-slate-900/60 hover:bg-slate-900 border border-slate-800/80 hover:border-indigo-500/30 group transition-smooth"
        >
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.35.134-.134m.743.743-.134-.134m.743.743-.134-.134m-.743-.743-.134.134m.743-.743-.134.134" />
              </svg>
            </div>
            <div>
              <h4 class="text-sm font-bold text-slate-200 group-hover:text-indigo-400 transition-colors">Transaksi Peminjaman</h4>
              <p class="text-[10px] text-slate-500 mt-0.5">Lakukan peminjaman equipment dan check-in pengembalian.</p>
            </div>
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-600 group-hover:text-indigo-400 group-hover:translate-x-1 transition-smooth">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
          </svg>
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  stats: {
    type: Object,
    required: true
  }
})

const page = usePage()
const userName = computed(() => page.props.auth?.user?.name || sessionStorage.getItem('user_name') || 'User')
const userRole = computed(() => page.props.auth?.user?.role || sessionStorage.getItem('user_role') || 'member')
</script>
