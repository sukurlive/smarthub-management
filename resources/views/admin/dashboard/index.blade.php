@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard')

@section('content')
<div class="row">
    <!-- Card Total Equipment -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total Equipment</h6>
                <h2 class="mb-0">{{ $totalEquipment ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <!-- Card Available Equipment -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Tersedia</h6>
                <h2 class="mb-0">{{ $availableEquipment ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <!-- Card Active Loans -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Sedang Dipinjam</h6>
                <h2 class="mb-0">{{ $activeLoans ?? 0 }}</h2>
            </div>
        </div>
    </div>
    
    <!-- Card Total Members -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Total Member</h6>
                <h2 class="mb-0">{{ $totalMembers ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Recent Loans -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Peminjaman Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Member</th>
                        <th>Equipment</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans ?? [] as $index => $loan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $loan->user->name ?? 'Tidak diketahui' }}</td>
                        <td>{{ $loan->equipment->name ?? 'Tidak diketahui' }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                        <td>
                            @if(($loan->status ?? '') == 'checked_out')
                                <span class="badge bg-warning">Dipinjam</span>
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection