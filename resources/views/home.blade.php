@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Welcome Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">Selamat datang di Smart-Hub Management System. Anda dapat meminjam peralatan studio untuk kebutuhan kreatif Anda.</p>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Equipment</h6>
                                    <h2 class="mt-2 mb-0">{{ $totalEquipment }}</h2>
                                    <small>Semua peralatan</small>
                                </div>
                                <div>
                                    <i class="fas fa-microphone fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Tersedia</h6>
                                    <h2 class="mt-2 mb-0">{{ $availableEquipment }}</h2>
                                    <small>Equipment siap pakai</small>
                                </div>
                                <div>
                                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Loan Alert -->
            @if($activeLoan)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle"></i> Perhatian!</strong>
                    Anda masih memiliki pinjaman aktif: <strong>{{ $activeLoan->equipment->name }}</strong>
                    (Dipinjam sejak {{ \Carbon\Carbon::parse($activeLoan->loan_date)->format('d/m/Y') }})
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- My Recent Loans -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Peminjaman Terbaru Saya
                    </h5>
                </div>
                <div class="card-body">
                    @if($myLoans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Equipment</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myLoans as $index => $loan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $loan->equipment->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}
                                         </td>
                                        <td>
                                            @if($loan->status == 'checked_out')
                                                <span class="badge bg-warning">Sedang Dipinjam</span>
                                            @else
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @endif
                                         </td>
                                        <td>
                                            @if($loan->status == 'checked_out')
                                                <form action="{{ route('member.checkin', $loan->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Kembalikan
                                                    </button>
                                                </form>
                                            @endif
                                         </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada riwayat peminjaman</p>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-microphone fa-3x text-primary mb-3"></i>
                            <h5>Lihat Catalog Equipment</h5>
                            <p>Lihat semua equipment yang tersedia untuk dipinjam</p>
                            <a href="{{ route('member.catalog') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> Lihat Catalog
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                            <h5>Riwayat Peminjaman</h5>
                            <p>Lihat semua riwayat peminjaman Anda</p>
                            <a href="{{ route('member.loans') }}" class="btn btn-success">
                                <i class="fas fa-history"></i> Lihat Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush