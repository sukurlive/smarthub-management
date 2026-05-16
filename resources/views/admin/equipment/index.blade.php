@extends('admin.layouts.app')

@section('title', 'Manajemen Equipment')
@section('header', 'Manajemen Equipment')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Equipment</h5>
        <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Equipment
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="equipmentTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Equipment</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipment as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ Str::limit($item->description, 50) }}</td>
                    <td>
                        @if($item->status == 'available')
                            <span class="badge bg-success">Tersedia</span>
                        @elseif($item->status == 'borrowed')
                            <span class="badge bg-warning">Dipinjam</span>
                        @else
                            <span class="badge bg-danger">Perbaikan</span>
                        @endif
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.equipment.show', $item->id) }}" 
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.equipment.edit', $item->id) }}" 
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete('{{ route('admin.equipment.destroy', $item->id) }}')" 
                                class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#equipmentTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            order: [[4, 'desc']]
        });
    });
</script>
@endpush