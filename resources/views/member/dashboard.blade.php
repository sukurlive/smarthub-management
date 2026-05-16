@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar Info -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user-circle fa-4x text-primary mb-3"></i>
                    <h5>{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2">
                            <i class="fas fa-microphone me-2"></i>
                            <strong>Total Dipinjam:</strong> {{ $activeLoans->count() }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Sudah Kembali:</strong> {{ $myLoans->count() - $activeLoans->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Active Loans -->
            @if($activeLoans->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-spinner me-2"></i>Pinjaman Aktif</h5>
                    </div>
                    <div class="card-body">
                        @foreach($activeLoans as $loan)
                            <div class="alert alert-warning mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $loan->equipment->name }}</strong><br>
                                        <small>Dipinjam: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</small>
                                    </div>
                                    <form action="{{ route('member.checkin', $loan->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Kembalikan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Available Equipment Quick View -->
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-microphone me-2"></i>Equipment Tersedia</h5>
                    <a href="{{ route('member.catalog') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($availableEquipment->take(3) as $item)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-microphone-alt fa-2x text-success mb-2"></i>
                                        <h6>{{ $item->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                        <div class="mt-2">
                                            <a href="{{ route('member.borrow.form', $item->id) }}" class="btn btn-sm btn-primary">
                                                Pinjam
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection