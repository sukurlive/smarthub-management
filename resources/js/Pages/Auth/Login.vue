<template>
  <div class="relative min-h-screen bg-slate-950 flex flex-col justify-center items-center px-4 overflow-hidden font-sans select-none">
    <!-- Abstract Ambient Glow Orbs -->
    <div class="absolute top-1/4 left-1/4 w-80 h-80 bg-cyan-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-pulse delay-700"></div>

    <!-- Login Container -->
    <div class="relative w-full max-w-md z-10">
      <div class="glass-card rounded-2xl p-8 border border-slate-800 shadow-2xl transition-smooth">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-xl mb-4 shadow-lg shadow-cyan-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
          </div>
          <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-cyan-400 to-indigo-300 bg-clip-text text-transparent">
            Smart-Hub Management
          </h1>
          <p class="text-slate-400 text-sm mt-1">Sistem Manajemen Peminjaman Studio Kreatif</p>
        </div>

        <!-- Error Banner -->
        <div v-if="errorMessage" class="mb-5 p-3 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 shrink-0 text-rose-400">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
          </svg>
          <span>{{ errorMessage }}</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-slate-300 text-xs font-semibold mb-2">Alamat Email</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
              </span>
              <input
                v-model="form.email"
                type="email"
                id="email"
                required
                placeholder="nama@perusahaan.com"
                class="w-full bg-slate-900/60 border border-slate-800 rounded-xl py-3 pl-10 pr-4 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-smooth"
              />
            </div>
            <p v-if="validationErrors.email" class="text-rose-400 text-[10px] mt-1">{{ validationErrors.email }}</p>
          </div>

          <!-- Password Input -->
          <div>
            <label for="password" class="block text-slate-300 text-xs font-semibold mb-2">Kata Sandi</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
              </span>
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                id="password"
                required
                placeholder="••••••••"
                class="w-full bg-slate-900/60 border border-slate-800 rounded-xl py-3 pl-10 pr-10 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-smooth"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300 transition-colors"
              >
                <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.815 7.815L21 21m-3.95-3.95-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </button>
            </div>
            <p v-if="validationErrors.password" class="text-rose-400 text-[10px] mt-1">{{ validationErrors.password }}</p>
          </div>

          <!-- Keep Signed In -->
          <div class="flex items-center justify-between text-xs">
            <label class="flex items-center text-slate-400 cursor-pointer">
              <input v-model="form.remember" type="checkbox" class="w-4 h-4 bg-slate-900 border border-slate-800 rounded focus:ring-0 focus:ring-offset-0 text-cyan-500 accent-cyan-500 mr-2" />
              <span>Ingat Saya</span>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-100 font-semibold py-3 px-4 rounded-xl text-sm transition-smooth shadow-lg shadow-cyan-500/10 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none flex items-center justify-center gap-2"
          >
            <span v-if="loading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            <span>{{ loading ? 'Memverifikasi...' : 'Masuk Aplikasi' }}</span>
          </button>
        </form>
      </div>

      <!-- Info Helper Box -->
      <div class="mt-6 text-center text-xs text-slate-500">
        <p>Login Demo:</p>
        <div class="flex justify-center gap-4 mt-2">
          <span>Admin: <strong class="text-slate-400">admin@example.com</strong></span>
          <span>Member: <strong class="text-slate-400">member@example.com</strong></span>
        </div>
        <p class="mt-1">Sandi: <strong class="text-slate-400">password</strong></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const loading = ref(false)
const showPassword = ref(false)
const errorMessage = ref('')
const validationErrors = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  // Clear previous errors
  errorMessage.value = ''
  validationErrors.email = ''
  validationErrors.password = ''

  // Client side validation
  if (!form.email) {
    validationErrors.email = 'Email wajib diisi.'
    return
  }
  if (!form.password) {
    validationErrors.password = 'Kata sandi wajib diisi.'
    return
  }

  loading.value = true

  try {
    const response = await axios.post('/login', form)
    const { token, user, redirect } = response.data

    if (response.data.success) {
      // Save Token to sessionStorage for API direct calls
      sessionStorage.setItem('auth_token', token)
      sessionStorage.setItem('user_role', user.role)
      sessionStorage.setItem('user_name', user.name)

      // Set auth header globally for future Axios requests
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

      // Redirect using browser location (full redirect to reload session state)
      window.location.href = redirect
    } else {
      errorMessage.value = response.data.message || 'Gagal masuk.'
    }
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errorMessage.value = error.response.data.message || 'Email atau password salah.'
    } else {
      errorMessage.value = 'Terjadi kesalahan sistem. Silakan coba kembali.'
    }
    console.error('Login error:', error)
  } finally {
    loading.value = false
  }
}
</script>
