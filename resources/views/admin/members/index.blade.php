@extends('admin.layouts.app')

@section('title', 'Kelola Member')
@section('header', 'Kelola Member')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Member</h6>
                        <h2 class="mb-0">{{ $totalMembers }}</h2>
                        <small>Pengguna terdaftar</small>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Aktif Meminjam</h6>
                        <h2 class="mb-0">{{ $activeMembers }}</h2>
                        <small>Sedang meminjam</small>
                    </div>
                    <div>
                        <i class="fas fa-spinner fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tidak Aktif</h6>
                        <h2 class="mb-0">{{ $inactiveMembers }}</h2>
                        <small>Tidak ada pinjaman</small>
                    </div>
                    <div>
                        <i class="fas fa-user-slash fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Member</h5>
        <div>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Member
            </a>
            <a href="{{ route('admin.members.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.members.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari member berdasarkan nama, email, atau telepon..." 
                       value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Total Pinjaman</th>
                        <th>Pinjaman Aktif</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $index => $member)
                    <tr>
                        <td>{{ $members->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2" style="width: 40px; height: 40px; background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $member->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->phone ?? '-' }}</td>
                        <td>{{ $member->loans->count() }}</td>
                        <td>
                            @if($member->loans->where('status', 'checked_out')->count() > 0)
                                <span class="badge bg-warning">
                                    {{ $member->loans->where('status', 'checked_out')->count() }}
                                </span>
                            @else
                                <span class="badge bg-success">0</span>
                            @endif
                         </td>
                        <td>{{ $member->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.members.show', $member->id) }}" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.members.edit', $member->id) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="resetPassword({{ $member->id }}, '{{ $member->name }}')" 
                                        class="btn btn-sm btn-secondary" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button onclick="confirmDelete({{ $member->id }}, '{{ $member->name }}')" 
                                        class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                         </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> Tidak ada data member
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Member?',
            text: `Apakah Anda yakin ingin menghapus member "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/members/${id}/destroy`;
            }
        });
    }
    
    function resetPassword(id, name) {
        Swal.fire({
            title: 'Reset Password?',
            text: `Reset password untuk member "${name}" menjadi "password123"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/members/${id}/reset-password`;
            }
        });
    }
</script>
@endpush