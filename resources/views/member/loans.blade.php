@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-history me-2"></i>Riwayat Peminjaman Saya</h2>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    @if($loans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="loansTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Equipment</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $index => $loan)
                                    <tr>
                                        <td>{{ $loans->firstItem() + $index }} </td>
                                        <td>{{ $loan->equipment->name }} </td>
                                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }} </td>
                                        <td>
                                            {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}
                                         </td>
                                        <td>
                                            @if($loan->return_date)
                                                @php
                                                    $start = \Carbon\Carbon::parse($loan->loan_date);
                                                    $end = \Carbon\Carbon::parse($loan->return_date);
                                                    $days = $start->diffInDays($end);
                                                @endphp
                                                {{ $days }} hari
                                            @else
                                                -
                                            @endif
                                         </td>
                                        <td>
                                            @if($loan->status == 'checked_out')
                                                <span class="badge bg-warning">Sedang Dipinjam</span>
                                            @else
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @endif
                                         </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $loans->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center mb-0">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Belum ada riwayat peminjaman.
                            <br>
                            <a href="{{ route('member.catalog') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-microphone"></i> Pinjam Equipment Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#loansTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            paging: false,
            searching: true
        });
    });
</script>
@endpush