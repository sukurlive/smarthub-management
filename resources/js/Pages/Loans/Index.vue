<template>
  <AppLayout>
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
      <div>
        <h2 class="text-lg font-bold text-slate-200">Transaksi Peminjaman</h2>
        <p class="text-xs text-slate-500 mt-1">Daftar riwayat serta pembuatan peminjaman peralatan studio Smart-Hub.</p>
      </div>

      <!-- Add Loan Button -->
      <button
        @click="openBorrowModal"
        class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-100 font-bold px-4 py-2.5 rounded-xl text-xs transition-smooth shadow-lg shadow-cyan-500/10 active:scale-[0.98]"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Pinjam Alat</span>
      </button>
    </div>

    <!-- Loan List Layout (Responsive: Table for Desktop, Cards for Mobile/Tablet) -->
    <div class="space-y-6">
      <!-- Desktop & Tablet Landscape Table -->
      <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-900/30 backdrop-blur-md">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-900/60 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 border-b border-slate-800/80">
              <th v-if="userRole === 'admin'" class="p-4 pl-6">Peminjam</th>
              <th class="p-4" :class="userRole === 'admin' ? '' : 'pl-6'">Nama Peralatan</th>
              <th class="p-4">Tanggal Pinjam</th>
              <th class="p-4">Tanggal Pengembalian</th>
              <th class="p-4">Status</th>
              <th class="p-4 pr-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
            <tr v-for="loan in props.loans" :key="loan.id" class="hover:bg-slate-800/20 transition-colors">
              <td v-if="userRole === 'admin'" class="p-4 pl-6">
                <div>
                  <div class="font-semibold text-slate-200 text-xs">{{ loan.user?.name }}</div>
                  <div class="text-[10px] text-slate-500">{{ loan.user?.email }}</div>
                </div>
              </td>
              <td class="p-4 font-bold text-slate-200" :class="userRole === 'admin' ? '' : 'pl-6'">
                {{ loan.equipment?.name || 'Alat Dihapus' }}
              </td>
              <td class="p-4 text-xs text-slate-400">{{ formatDate(loan.loan_date) }}</td>
              <td class="p-4 text-xs text-slate-400">{{ formatDate(loan.return_date) }}</td>
              <td class="p-4">
                <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider border" :class="statusClass(loan.status)">
                  {{ statusLabel(loan.status) }}
                </span>
              </td>
              <td class="p-4 pr-6 text-right">
                <!-- Checkin Action (Only if status is checked_out) -->
                <button
                  v-if="loan.status === 'checked_out'"
                  @click="confirmCheckin(loan)"
                  class="px-3.5 py-1.5 rounded-xl bg-teal-500/10 hover:bg-teal-500 text-teal-400 hover:text-slate-950 text-xs font-bold border border-teal-500/20 hover:border-teal-400 transition-smooth active:scale-[0.95]"
                >
                  Kembalikan
                </button>
                <span v-else class="text-xs text-slate-500 font-medium">Selesai</span>
              </td>
            </tr>
            <tr v-if="props.loans.length === 0">
              <td :colspan="userRole === 'admin' ? 6 : 5" class="p-8 text-center text-slate-500">
                Belum ada transaksi peminjaman terdaftar.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile & Tablet Portrait Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
        <div
          v-for="loan in props.loans"
          :key="loan.id"
          class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-col justify-between gap-4 transition-smooth"
        >
          <div>
            <div class="flex justify-between items-start gap-2">
              <h3 class="font-bold text-slate-200 text-sm truncate">
                {{ loan.equipment?.name || 'Alat Dihapus' }}
              </h3>
              <span class="px-2 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider border shrink-0" :class="statusClass(loan.status)">
                {{ statusLabel(loan.status) }}
              </span>
            </div>

            <div class="mt-4 space-y-2 text-xs text-slate-400 border-t border-slate-800/60 pt-3">
              <div v-if="userRole === 'admin'" class="flex justify-between">
                <span class="text-slate-500 text-[10px]">Peminjam:</span>
                <span class="font-semibold text-slate-300 text-[11px]">{{ loan.user?.name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500 text-[10px]">Tgl Pinjam:</span>
                <span>{{ formatDate(loan.loan_date) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500 text-[10px]">Tgl Kembali:</span>
                <span>{{ formatDate(loan.return_date) }}</span>
              </div>
            </div>
          </div>

          <div v-if="loan.status === 'checked_out'" class="flex justify-end pt-3 border-t border-slate-800/60">
            <button
              @click="confirmCheckin(loan)"
              class="w-full bg-teal-500 text-slate-950 font-bold px-3 py-2.5 rounded-xl text-xs transition-smooth active:scale-[0.98]"
            >
              Kembalikan Alat
            </button>
          </div>
        </div>

        <div v-if="props.loans.length === 0" class="col-span-full py-8 text-center text-slate-500">
          Belum ada transaksi peminjaman terdaftar.
        </div>
      </div>
    </div>

    <!-- Borrow Modal -->
    <div v-if="showBorrowModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="glass-card w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl p-6 animate-fade-in">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-base font-bold text-slate-200">Form Peminjaman Baru</h3>
          <button @click="showBorrowModal = false" class="text-slate-400 hover:text-white">&times;</button>
        </div>

        <form @submit.prevent="submitBorrow" class="space-y-4">
          <!-- Select Equipment -->
          <div>
            <label for="equipment_id" class="block text-slate-400 text-xs font-semibold mb-2">Pilih Peralatan</label>
            <select
              v-model="borrowForm.equipment_id"
              id="equipment_id"
              required
              class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 px-3 text-sm text-slate-100 focus:outline-none focus:border-cyan-500 transition-smooth"
            >
              <option value="" disabled>-- Pilih peralatan tersedia --</option>
              <option v-for="item in props.availableEquipment" :key="item.id" :value="item.id">
                {{ item.name }}
              </option>
            </select>
            <p v-if="props.availableEquipment.length === 0" class="text-amber-400 text-[10px] mt-1.5">
              ⚠️ Tidak ada peralatan yang berstatus tersedia (available) saat ini.
            </p>
          </div>

          <!-- Date Picker -->
          <div>
            <label for="loan_date" class="block text-slate-400 text-xs font-semibold mb-2">Tanggal Pinjam</label>
            <input
              v-model="borrowForm.loan_date"
              type="date"
              id="loan_date"
              required
              class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 px-3 text-sm text-slate-100 focus:outline-none focus:border-cyan-500 transition-smooth"
            />
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-slate-850">
            <button
              type="button"
              @click="showBorrowModal = false"
              class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white transition-colors"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="loading || props.availableEquipment.length === 0"
              class="bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-100 font-bold px-4 py-2.5 rounded-xl text-xs transition-smooth shadow-lg shadow-cyan-500/10 active:scale-[0.98] disabled:opacity-50 flex items-center gap-2"
            >
              <span v-if="loading" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
              <span>Pinjam Alat</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirm Check-in Modal -->
    <div v-if="showCheckinModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="glass-card w-full max-w-sm rounded-2xl border border-slate-800 shadow-2xl p-6 animate-fade-in">
        <h3 class="text-base font-bold text-slate-200 mb-2">Konfirmasi Pengembalian</h3>
        <p class="text-xs text-slate-400 leading-relaxed mb-6">
          Apakah Anda yakin ingin mengembalikan peralatan <strong class="text-slate-200">"{{ selectedLoan?.equipment?.name }}"</strong>? Status alat akan kembali diubah menjadi tersedia.
        </p>

        <div class="flex justify-end gap-3">
          <button
            @click="showCheckinModal = false"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white transition-colors"
          >
            Batal
          </button>
          <button
            @click="submitCheckin"
            :disabled="loading"
            class="bg-teal-500 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs transition-smooth shadow-lg shadow-teal-500/10 active:scale-[0.98] disabled:opacity-50 flex items-center gap-2"
          >
            <span v-if="loading" class="w-3.5 h-3.5 border-2 border-slate-950/30 border-t-slate-950 rounded-full animate-spin"></span>
            <span>Kembalikan Sekarang</span>
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  loans: {
    type: Array,
    required: true
  },
  availableEquipment: {
    type: Array,
    required: true
  }
})

const page = usePage()
const userRole = computed(() => page.props.auth?.user?.role || sessionStorage.getItem('user_role') || 'member')

const showBorrowModal = ref(false)
const showCheckinModal = ref(false)
const selectedLoan = ref(null)
const loading = ref(false)

const borrowForm = reactive({
  equipment_id: '',
  loan_date: new Date().toISOString().substring(0, 10)
})

const statusLabel = (status) => {
  return status === 'checked_out' ? 'Sedang Dipinjam' : 'Sudah Dikembalikan'
}

const statusClass = (status) => {
  return status === 'checked_out'
    ? 'bg-amber-500/10 border-amber-500/30 text-amber-400'
    : 'bg-teal-500/10 border-teal-500/30 text-teal-400'
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })
}

const openBorrowModal = () => {
  borrowForm.equipment_id = ''
  borrowForm.loan_date = new Date().toISOString().substring(0, 10)
  showBorrowModal.value = true
}

const submitBorrow = () => {
  loading.value = true
  router.post('/loans', borrowForm, {
    onSuccess: () => {
      showBorrowModal.value = false
      loading.value = false
    },
    onError: () => {
      loading.value = false
    }
  })
}

const confirmCheckin = (loan) => {
  selectedLoan.value = loan
  showCheckinModal.value = true
}

const submitCheckin = () => {
  if (!selectedLoan.value) return
  loading.value = true
  router.post(`/loans/${selectedLoan.value.id}/checkin`, {}, {
    onSuccess: () => {
      showCheckinModal.value = false
      loading.value = false
      selectedLoan.value = null
    },
    onError: () => {
      loading.value = false
    }
  })
}
</script>
