<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'equipment'])->get();
        return response()->json([
            'success'   => true,
            'data'      => $loans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_id'  => 'required|exists:equipment,id',
            'loan_date'     => 'required|date'
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);
        
        if (!$equipment->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment tidak tersedia untuk dipinjam!.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $loan = Loan::create([
                'user_id'       => $request->user()->id,
                'equipment_id'  => $request->equipment_id,
                'loan_date'     => $request->loan_date,
                'status'        => 'checked_out'
            ]);

            $equipment->update(['status' => 'borrowed']);

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Equipment berhasil dipinjam!.',
                'data'      => $loan->load('equipment')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Equipment gagal dipinjam!.'
            ], 500);
        }
    }

    public function checkin($id, Request $request)
    {
        $loan = Loan::with('equipment')->findOrFail($id);
        
        if ($loan->status === 'checked_in') {
            return response()->json([
                'success' => false,
                'message' => 'Equipment sudah dikembalikan!.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $loan->checkin();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Equipment berhasil dikembalikan!.',
                'data' => $loan
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Equipment gagal dikembalikan!.'
            ], 500);
        }
    }

    public function myLoans(Request $request)
    {
        $loans = Loan::with('equipment')
            ->where('user_id', $request->user()->id)
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $loans
        ]);
    }
}