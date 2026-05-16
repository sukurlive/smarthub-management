@extends('admin.layouts.app')

@section('title', 'Detail Equipment')
@section('header', 'Detail Equipment')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Equipment</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Nama Equipment</th>
                        <td>{{ $equipment->name }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $equipment->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($equipment->status == 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($equipment->status == 'borrowed')
                                <span class="badge bg-warning">Dipinjam</span>
                            @else
                                <span class="badge bg-danger">Perbaikan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $equipment->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $equipment->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('admin.equipment.edit', $equipment->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="confirmDelete('{{ route('admin.equipment.destroy', $equipment->id) }}')" 
                                class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Peminjaman Equipment -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Peminjaman</h5>
            </div>
            <div class="card-body">
                @if($equipment->loans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Member</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($equipment->loans as $loan)
                                <tr>
                                    <td>{{ $loan->user->name }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada riwayat peminjaman</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection