<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 flex font-sans overflow-x-hidden selection:bg-cyan-500 selection:text-slate-950">
    <!-- Sidebar for Desktop & Tablet landscape -->
    <aside class="hidden lg:flex flex-col w-64 bg-slate-900/60 border-r border-slate-800/80 backdrop-blur-md shrink-0">
      <!-- Sidebar Header -->
      <div class="h-20 flex items-center px-6 border-b border-slate-800/60 gap-3">
        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-lg shadow-lg shadow-cyan-500/10 text-white font-bold">
          SH
        </div>
        <div>
          <h2 class="text-sm font-bold tracking-wider text-slate-200">SMART-HUB</h2>
          <span class="text-[10px] text-cyan-400 font-semibold uppercase tracking-widest">{{ userRole }}</span>
        </div>
      </div>

      <!-- Navigation Links -->
      <nav class="flex-1 px-4 py-6 space-y-2">
        <Link
          v-for="link in navLinks"
          :key="link.href"
          :href="link.href"
          class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-smooth group"
          :class="isActive(link.href) 
            ? 'bg-gradient-to-r from-cyan-500/10 to-indigo-500/5 text-cyan-400 border-l-4 border-cyan-500 shadow-md shadow-cyan-500/5' 
            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'"
        >
          <component :is="link.icon" class="w-5 h-5 group-hover:scale-110 transition-transform" />
          <span>{{ link.label }}</span>
        </Link>
      </nav>

      <!-- Sidebar Footer User Profile -->
      <div class="p-4 border-t border-slate-800/60 bg-slate-900/40">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold uppercase">
            {{ userName.substring(0, 2) }}
          </div>
          <div class="overflow-hidden">
            <h4 class="text-xs font-semibold text-slate-200 truncate">{{ userName }}</h4>
            <p class="text-[10px] text-slate-500 truncate">{{ userEmail }}</p>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full flex items-center justify-center gap-2 bg-slate-800/60 hover:bg-rose-500/10 hover:text-rose-400 text-slate-300 py-2.5 rounded-xl text-xs font-semibold transition-smooth border border-slate-700/50 hover:border-rose-500/20 active:scale-[0.98]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
          </svg>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- Mobile Drawer Sidebar Backdrop -->
    <div
      v-if="isMobileMenuOpen"
      @click="isMobileMenuOpen = false"
      class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 lg:hidden"
    ></div>

    <!-- Mobile Drawer Sidebar -->
    <aside
      class="fixed top-0 bottom-0 left-0 w-72 bg-slate-900 border-r border-slate-800 z-50 transform transition-transform duration-300 lg:hidden flex flex-col"
      :class="isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800">
        <div class="flex items-center gap-3">
          <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-lg text-white font-bold">
            SH
          </div>
          <div>
            <h2 class="text-sm font-bold tracking-wider text-slate-200">SMART-HUB</h2>
            <span class="text-[10px] text-cyan-400 font-semibold uppercase tracking-widest">{{ userRole }}</span>
          </div>
        </div>
        <button @click="isMobileMenuOpen = false" class="text-slate-400 hover:text-white p-1">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <nav class="flex-1 px-4 py-6 space-y-2">
        <Link
          v-for="link in navLinks"
          :key="link.href"
          :href="link.href"
          @click="isMobileMenuOpen = false"
          class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-smooth"
          :class="isActive(link.href) 
            ? 'bg-gradient-to-r from-cyan-500/10 to-indigo-500/5 text-cyan-400 border-l-4 border-cyan-500' 
            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'"
        >
          <component :is="link.icon" class="w-5 h-5" />
          <span>{{ link.label }}</span>
        </Link>
      </nav>

      <div class="p-4 border-t border-slate-800 bg-slate-900/40">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold uppercase">
            {{ userName.substring(0, 2) }}
          </div>
          <div class="overflow-hidden">
            <h4 class="text-xs font-semibold text-slate-200 truncate">{{ userName }}</h4>
            <p class="text-[10px] text-slate-500 truncate">{{ userEmail }}</p>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-rose-500/10 hover:text-rose-400 text-slate-300 py-2.5 rounded-xl text-xs font-semibold transition-smooth border border-slate-700/50 hover:border-rose-500/20 active:scale-[0.98]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
          </svg>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Glassmorphic Top Navbar -->
      <header class="h-20 glass-card border-b border-slate-800/60 sticky top-0 z-30 flex items-center justify-between px-6 lg:px-8">
        <div class="flex items-center gap-4">
          <button
            @click="isMobileMenuOpen = true"
            class="p-2 -ml-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 lg:hidden transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
          </button>
          
          <h1 class="text-lg font-bold text-slate-200 tracking-tight capitalize">
            {{ activePageTitle }}
          </h1>
        </div>

        <!-- User Role Badge -->
        <div class="flex items-center gap-4">
          <div class="hidden sm:flex flex-col text-right">
            <span class="text-xs font-semibold text-slate-200">{{ userName }}</span>
            <span class="text-[9px] text-slate-500 font-semibold uppercase tracking-widest mt-0.5">{{ userRole }}</span>
          </div>
          <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-500 to-indigo-600 border border-slate-800/80 shadow-md shadow-cyan-500/5 flex items-center justify-center text-white font-semibold uppercase">
            {{ userName.substring(0, 2) }}
          </div>
        </div>
      </header>

      <!-- Main Layout Body -->
      <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
        <div class="max-w-7xl mx-auto space-y-6">
          <!-- Notification Alert for feedback -->
          <div 
            v-if="flashSuccess" 
            class="p-4 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-300 text-xs flex items-center justify-between animate-fade-in"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-400">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
              </svg>
              <span>{{ flashSuccess }}</span>
            </div>
            <button @click="clearFlashSuccess" class="text-teal-400 hover:text-white">&times;</button>
          </div>

          <div 
            v-if="flashError" 
            class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center justify-between animate-fade-in"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-rose-400">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
              </svg>
              <span>{{ flashError }}</span>
            </div>
            <button @click="clearFlashError" class="text-rose-400 hover:text-white">&times;</button>
          </div>

          <slot />
        </div>
      </main>

      <!-- Premium Footer -->
      <footer class="h-14 border-t border-slate-800/40 text-center flex items-center justify-center text-[10px] text-slate-500 bg-slate-900/10">
        © 2026 Smart-Hub Management System. Premium UI Hub.
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, markRaw } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import axios from 'axios'

// Define icons using SVG inline elements as Vue components
const HomeIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>`
}
const CubeIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>`
}
const ClipboardIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.3 8.35.134-.134m.743.743-.134-.134m.743.743-.134-.134m-.743-.743-.134.134m.743-.743-.134.134" /></svg>`
}

const page = usePage()
const isMobileMenuOpen = ref(false)

const userRole = computed(() => page.props.auth?.user?.role || sessionStorage.getItem('user_role') || 'member')
const userName = computed(() => page.props.auth?.user?.name || sessionStorage.getItem('user_name') || 'User')
const userEmail = computed(() => page.props.auth?.user?.email || 'user@example.com')

// Flash Messages
const flashSuccess = ref(page.props.flash?.success || '')
const flashError = ref(page.props.flash?.error || '')

const clearFlashSuccess = () => flashSuccess.value = ''
const clearFlashError = () => flashError.value = ''

onMounted(() => {
  // Sync Axios authorization header if token is in session but not set
  const token = sessionStorage.getItem('auth_token')
  if (token && !axios.defaults.headers.common['Authorization']) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }
})

// Build dynamic navigation links based on user role
const navLinks = computed(() => {
  const dashboardHref = userRole.value === 'admin' ? '/admin/dashboard' : '/member/dashboard'
  return [
    { label: 'Beranda', href: dashboardHref, icon: markRaw(HomeIcon) },
    { label: 'Data Master Alat', href: '/equipment', icon: markRaw(CubeIcon) },
    { label: 'Transaksi Pinjam', href: '/loans', icon: markRaw(ClipboardIcon) },
  ]
})

// Get active page title
const activePageTitle = computed(() => {
  const path = window.location.pathname
  if (path.includes('dashboard')) return 'Dashboard Overview'
  if (path.includes('equipment')) return 'Manajemen Data Master Peralatan'
  if (path.includes('loans')) return 'Manajemen Transaksi Peminjaman'
  return 'Smart-Hub'
})

const isActive = (href) => {
  return window.location.pathname === href
}

const handleLogout = async () => {
  try {
    // Delete session storage token
    sessionStorage.removeItem('auth_token')
    sessionStorage.removeItem('user_role')
    sessionStorage.removeItem('user_name')
    
    // Web standard session logout call
    router.post('/logout')
  } catch (error) {
    console.error('Logout error:', error)
    window.location.href = '/login'
  }
}
</script>

<style>
/* Transitions and custom animations */
.transition-smooth {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-4px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out forwards;
}
</style>
