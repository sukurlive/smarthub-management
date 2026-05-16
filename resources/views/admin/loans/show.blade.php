@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman')
@section('header', 'Detail Peminjaman')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID Peminjaman</th>
                        <td>#{{ $loan->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Member</th>
                        <td>{{ $loan->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email Member</th>
                        <td>{{ $loan->user->email }}</td>
                    </tr>
                    <tr>
                        <th>Nama Equipment</th>
                        <td>{{ $loan->equipment->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Kembali</th>
                        <td>{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y H:i:s') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($loan->status == 'checked_out')
                                <span class="badge bg-warning">Sedang Dipinjam</span>
                            @else
                                <span class="badge bg-success">Sudah Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $loan->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($loan->status == 'checked_out')
                        <form action="{{ route('admin.loans.checkin', $loan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Konfirmasi Check-in
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection