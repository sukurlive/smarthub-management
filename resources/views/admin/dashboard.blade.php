@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Equipment</h5>
                    <h2>{{ $totalEquipment }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Available</h5>
                    <h2>{{ $availableEquipment }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Active Loans</h5>
                    <h2>{{ $activeLoans }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Members</h5>
                    <h2>{{ $totalMembers }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            Recent Loans
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Equipment</th>
                        <th>Loan Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLoans as $loan)
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->equipment->name }}</td>
                        <td>{{ $loan->loan_date }}</td>
                        <td>
                            <span class="badge badge-{{ $loan->status === 'checked_out' ? 'warning' : 'success' }}">
                                {{ $loan->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection