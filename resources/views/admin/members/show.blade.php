@extends('admin.layouts.app')

@section('title', 'Detail Member')
@section('header', 'Detail Member')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-circle mx-auto mb-3" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <h4>{{ $member->name }}</h4>
                <p class="text-muted">{{ $member->email }}</p>
                <hr>
                <div class="text-start">
                    <p><i class="fas fa-phone me-2"></i> {{ $member->phone ?? '-' }}</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> {{ $member->address ?? '-' }}</p>
                    <p><i class="fas fa-calendar-alt me-2"></i> Bergabung: {{ $member->created_at->format('d/m/Y') }}</p>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <h3>{{ $totalLoans }}</h3>
                        <small>Total Pinjaman</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning">{{ $activeLoans }}</h3>
                        <small>Aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Equipment</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($member->loans as $index => $loan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $loan->equipment->name ?? 'Equipment tidak ditemukan' }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                <td>{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($loan->status == 'checked_out')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                 </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('admin.members.edit', $member->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit Member
    </a>
</div>
@endsection