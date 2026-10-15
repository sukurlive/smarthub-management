@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-hand-peace me-2"></i>Form Peminjaman Equipment</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Equipment yang akan dipinjam:</strong><br>
                        <h4 class="mt-2">{{ $equipment->name }}</h4>
                        <p class="mb-0">{{ $equipment->description }}</p>
                    </div>
                    
                    <form action="{{ route('member.borrow.store', $equipment->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="loan_date" class="form-label">Tanggal Peminjaman <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('loan_date') is-invalid @enderror" 
                                   id="loan_date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required>
                            @error('loan_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya menyetujui ketentuan peminjaman yang berlaku
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('member.catalog') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Konfirmasi Pinjam
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection