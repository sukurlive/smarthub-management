@extends('admin.layouts.app')

@section('title', 'Manajemen Peminjaman')
@section('header', 'Manajemen Peminjaman')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Daftar Peminjaman Equipment</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="loanTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                    <i class="fas fa-spinner"></i> Sedang Dipinjam ({{ $activeLoans->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                    <i class="fas fa-history"></i> Riwayat ({{ $historyLoans->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                    <i class="fas fa-list"></i> Semua ({{ $allLoans->count() }})
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="loanTabContent">
            <!-- Tab Active Loans -->
            <div class="tab-pane fade show active" id="active" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover" id="activeTable">
                        <thead>
                            <tr><th>No</th><th>Member</th><th>Equipment</th><th>Tanggal Pinjam</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($activeLoans as $index => $loan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->equipment->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.loans.show', $loan->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.loans.checkin', $loan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Check-in
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab History Loans -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover" id="historyTable">
                        <thead>
                            <tr><th>No</th><th>Member</th><th>Equipment</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th></tr>
                        </thead>
                        <tbody>
                            @foreach($historyLoans as $index => $loan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->equipment->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                <td>{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab All Loans -->
            <div class="tab-pane fade" id="all" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover" id="allTable">
                        <thead>
                            <tr><th>No</th><th>Member</th><th>Equipment</th><th>Tanggal Pinjam</th><th>Status</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($allLoans as $index => $loan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->equipment->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                <td>
                                    @if($loan->status == 'checked_out')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.loans.show', $loan->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#activeTable, #historyTable, #allTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
        });
    });
</script>
@endpush