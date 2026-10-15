@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-microphone me-2"></i>Catalog Equipment</h2>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            
            <!-- Filter tabs -->
            <ul class="nav nav-tabs mb-4" id="catalogTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                        <i class="fas fa-list"></i> Semua ({{ $equipment->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button">
                        <i class="fas fa-check-circle"></i> Tersedia ({{ $availableEquipment->count() }})
                    </button>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- All Equipment Tab -->
                <div class="tab-pane fade show active" id="all">
                    <div class="row">
                        @forelse($equipment as $item)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-microphone-alt fa-4x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">{{ $item->name }}</h5>
                                        <p class="card-text text-muted">
                                            {{ Str::limit($item->description, 100) ?: 'Tidak ada deskripsi' }}
                                        </p>
                                        <div class="mb-3">
                                            @if($item->status == 'available')
                                                <span class="badge bg-success">Tersedia</span>
                                            @elseif($item->status == 'borrowed')
                                                <span class="badge bg-warning">Dipinjam</span>
                                            @else
                                                <span class="badge bg-danger">Perbaikan</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        @if($item->status == 'available')
                                            <a href="{{ route('member.borrow.form', $item->id) }}" class="btn btn-primary w-100">
                                                <i class="fas fa-hand-peace"></i> Pinjam Sekarang
                                            </a>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times-circle"></i> Tidak Tersedia
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    Belum ada equipment tersedia.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Available Equipment Tab -->
                <div class="tab-pane fade" id="available">
                    <div class="row">
                        @forelse($availableEquipment as $item)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-microphone-alt fa-4x text-success"></i>
                                        </div>
                                        <h5 class="card-title">{{ $item->name }}</h5>
                                        <p class="card-text text-muted">
                                            {{ Str::limit($item->description, 100) ?: 'Tidak ada deskripsi' }}
                                        </p>
                                        <div class="mb-3">
                                            <span class="badge bg-success">Tersedia</span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ route('member.borrow.form', $item->id) }}" class="btn btn-success w-100">
                                            <i class="fas fa-hand-peace"></i> Pinjam Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    Tidak ada equipment yang tersedia saat ini.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection