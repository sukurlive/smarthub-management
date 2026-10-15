<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Events\LoanCheckedIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $activeLoans = Loan::with(['user', 'equipment'])
            ->where('status', 'checked_out')
            ->latest()
            ->get();
            
        $historyLoans = Loan::with(['user', 'equipment'])
            ->where('status', 'checked_in')
            ->latest()
            ->get();
            
        $allLoans = Loan::with(['user', 'equipment'])
            ->latest()
            ->get();
            
        return view('admin.loans.index', compact('activeLoans', 'historyLoans', 'allLoans'));
    }

    public function show($id)
    {
        $loan = Loan::with(['user', 'equipment'])->findOrFail($id);
        return view('admin.loans.show', compact('loan'));
    }

    public function checkin($id)
    {
        $loan = Loan::with('equipment')->findOrFail($id);
        
        if ($loan->status === 'checked_in') {
            return redirect()->route('admin.loans.index')
                ->with('error', 'Equipment sudah dikembalikan!');
        }

        DB::beginTransaction();
        try {
            $loan->checkin();
            DB::commit();
            
            // Trigger event untuk email notification
            event(new LoanCheckedIn($loan));
            
            return redirect()->route('admin.loans.index')
                ->with('success', 'Check-in berhasil! Email konfirmasi telah dikirim.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.loans.index')
                ->with('error', 'Gagal melakukan check-in!');
        }
    }
}