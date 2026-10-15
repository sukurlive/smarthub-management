<template>
  <AppLayout>
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/40 border border-slate-800 p-6 rounded-2xl">
      <div>
        <h2 class="text-lg font-bold text-slate-200">Katalog & Inventaris Alat</h2>
        <p class="text-xs text-slate-500 mt-1">Daftar lengkap seluruh peralatan studio kreativitas Smart-Hub.</p>
      </div>

      <!-- Add Equipment Trigger (Admin Only) -->
      <button
        v-if="userRole === 'admin'"
        @click="openCreateModal"
        class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs transition-smooth shadow-lg shadow-cyan-500/10 active:scale-[0.98]"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>Tambah Peralatan</span>
      </button>
    </div>

    <!-- Equipment List Layout (Responsive: Table for Desktop, Cards for Tablet/Mobile) -->
    <div class="space-y-6">
      <!-- Desktop & Tablet Landscape Table -->
      <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-900/30 backdrop-blur-md">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-900/60 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 border-b border-slate-800/80">
              <th class="p-4 pl-6">Nama Peralatan</th>
              <th class="p-4">Deskripsi</th>
              <th class="p-4">Status Alat</th>
              <th v-if="userRole === 'admin'" class="p-4 pr-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 text-sm text-slate-300">
            <tr v-for="item in props.equipment" :key="item.id" class="hover:bg-slate-800/20 transition-colors">
              <td class="p-4 pl-6 font-bold text-slate-200">{{ item.name }}</td>
              <td class="p-4 text-slate-400 max-w-xs truncate">{{ item.description || '-' }}</td>
              <td class="p-4">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider border" :class="statusClass(item.status)">
                  {{ statusLabel(item.status) }}
                </span>
              </td>
              <td v-if="userRole === 'admin'" class="p-4 pr-6 text-right">
                <button
                  @click="confirmDelete(item)"
                  class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 hover:border-rose-500/30 transition-smooth active:scale-[0.95]"
                  title="Hapus Peralatan"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 9m-4.78 0L9 9m4.78-3L12 4.453 10.22 6M19 6h-3.07a2 2 0 0 0-1.93-1.543H10a2 2 0 0 0-1.93 1.543H5m14 0v13.5A2.25 2.25 0 0 1 16.75 21H7.25A2.25 2.25 0 0 1 5 18.75V6h14Z" />
                  </svg>
                </button>
              </td>
            </tr>
            <tr v-if="props.equipment.length === 0">
              <td colspan="4" class="p-8 text-center text-slate-500">Tidak ada peralatan studio terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile & Tablet Portrait Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
        <div
          v-for="item in props.equipment"
          :key="item.id"
          class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-col justify-between gap-4 transition-smooth"
        >
          <div>
            <div class="flex justify-between items-start gap-2">
              <h3 class="font-bold text-slate-200 text-sm truncate">{{ item.name }}</h3>
              <span class="px-2 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-wider border shrink-0" :class="statusClass(item.status)">
                {{ statusLabel(item.status) }}
              </span>
            </div>
            <p class="text-xs text-slate-400 mt-2 line-clamp-2">{{ item.description || '-' }}</p>
          </div>
          
          <div v-if="userRole === 'admin'" class="flex justify-end pt-3 border-t border-slate-800/60">
            <button
              @click="confirmDelete(item)"
              class="flex items-center gap-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-3 py-2 rounded-xl text-xs border border-rose-500/20 transition-smooth active:scale-[0.95]"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 9m-4.78 0L9 9m4.78-3L12 4.453 10.22 6M19 6h-3.07a2 2 0 0 0-1.93-1.543H10a2 2 0 0 0-1.93 1.543H5m14 0v13.5A2.25 2.25 0 0 1 16.75 21H7.25A2.25 2.25 0 0 1 5 18.75V6h14Z" />
              </svg>
              <span>Hapus Alat</span>
            </button>
          </div>
        </div>

        <div v-if="props.equipment.length === 0" class="col-span-full py-8 text-center text-slate-500">
          Tidak ada peralatan studio terdaftar.
        </div>
      </div>
    </div>

    <!-- Create Equipment Modal (Admin Only) -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="glass-card w-full max-w-md rounded-2xl border border-slate-800 shadow-2xl p-6 animate-fade-in">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-base font-bold text-slate-200">Tambah Peralatan Baru</h3>
          <button @click="showCreateModal = false" class="text-slate-400 hover:text-white">&times;</button>
        </div>

        <form @submit.prevent="submitCreate" class="space-y-4">
          <div>
            <label for="name" class="block text-slate-400 text-xs font-semibold mb-2">Nama Alat</label>
            <input
              v-model="createForm.name"
              type="text"
              id="name"
              required
              placeholder="Contoh: Kamera Canon EOS R"
              class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 px-3 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 transition-smooth"
            />
          </div>

          <div>
            <label for="description" class="block text-slate-400 text-xs font-semibold mb-2">Deskripsi (Opsional)</label>
            <textarea
              v-model="createForm.description"
              id="description"
              rows="3"
              placeholder="Contoh: Lensa prime 50mm, 2 baterai..."
              class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 px-3 text-sm text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 transition-smooth resize-none"
            ></textarea>
          </div>

          <div>
            <label for="status" class="block text-slate-400 text-xs font-semibold mb-2">Status Awal</label>
            <select
              v-model="createForm.status"
              id="status"
              class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 px-3 text-sm text-slate-100 focus:outline-none focus:border-cyan-500 transition-smooth"
            >
              <option value="available">Tersedia</option>
              <option value="borrowed">Dipinjam</option>
              <option value="maintenance">Maintenance</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-slate-850">
            <button
              type="button"
              @click="showCreateModal = false"
              class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white transition-colors"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs transition-smooth shadow-lg shadow-cyan-500/10 active:scale-[0.98] disabled:opacity-50 flex items-center gap-2"
            >
              <span v-if="loading" class="w-3.5 h-3.5 border-2 border-slate-950/30 border-t-slate-950 rounded-full animate-spin"></span>
              <span>Simpan Alat</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirm Delete Modal (Admin Only) -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="glass-card w-full max-w-sm rounded-2xl border border-slate-800 shadow-2xl p-6 animate-fade-in">
        <h3 class="text-base font-bold text-slate-200 mb-2">Konfirmasi Hapus</h3>
        <p class="text-xs text-slate-400 leading-relaxed mb-6">
          Apakah Anda yakin ingin menghapus <strong class="text-slate-200">"{{ selectedItem?.name }}"</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="flex justify-end gap-3">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white transition-colors"
          >
            Batal
          </button>
          <button
            @click="submitDelete"
            :disabled="loading"
            class="bg-rose-500 hover:bg-rose-600 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-smooth shadow-lg shadow-rose-500/10 active:scale-[0.98] disabled:opacity-50 flex items-center gap-2"
          >
            <span v-if="loading" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            <span>Hapus Permanen</span>
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
  equipment: {
    type: Array,
    required: true
  }
})

const page = usePage()
const userRole = computed(() => page.props.auth?.user?.role || sessionStorage.getItem('user_role') || 'member')

const showCreateModal = ref(false)
const showDeleteModal = ref(false)
const selectedItem = ref(null)
const loading = ref(false)

const createForm = reactive({
  name: '',
  description: '',
  status: 'available'
})

const statusLabel = (status) => {
  if (status === 'available') return 'Tersedia'
  if (status === 'borrowed') return 'Dipinjam'
  if (status === 'maintenance') return 'Perbaikan'
  return status
}

const statusClass = (status) => {
  if (status === 'available') return 'bg-teal-500/10 border-teal-500/30 text-teal-400'
  if (status === 'borrowed') return 'bg-amber-500/10 border-amber-500/30 text-amber-400'
  if (status === 'maintenance') return 'bg-rose-500/10 border-rose-500/30 text-rose-400'
  return 'bg-slate-500/10 border-slate-500/30 text-slate-400'
}

const openCreateModal = () => {
  createForm.name = ''
  createForm.description = ''
  createForm.status = 'available'
  showCreateModal.value = true
}

const submitCreate = () => {
  loading.value = true
  router.post('/equipment', createForm, {
    onSuccess: () => {
      showCreateModal.value = false
      loading.value = false
    },
    onError: () => {
      loading.value = false
    }
  })
}

const confirmDelete = (item) => {
  selectedItem.value = item
  showDeleteModal.value = true
}

const submitDelete = () => {
  if (!selectedItem.value) return
  loading.value = true
  router.delete(`/equipment/${selectedItem.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
      loading.value = false
      selectedItem.value = null
    },
    onError: () => {
      loading.value = false
    }
  })
}
</script>
