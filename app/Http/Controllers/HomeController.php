<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        $totalEquipment = Equipment::count();
        $availableEquipment = Equipment::where('status', 'available')->count();
        $myLoans = Loan::where('user_id', $user->id)
            ->with('equipment')
            ->latest()
            ->take(5)
            ->get();
        $activeLoan = Loan::where('user_id', $user->id)
            ->where('status', 'checked_out')
            ->first();
            
        return view('home', compact('totalEquipment', 'availableEquipment', 'myLoans', 'activeLoan'));
    }
    
    public function memberDashboard()
    {
        $user = auth()->user();
        $equipment = Equipment::all();
        $availableEquipment = Equipment::where('status', 'available')->get();
        $myLoans = Loan::where('user_id', $user->id)->with('equipment')->latest()->get();
        $activeLoans = Loan::where('user_id', $user->id)->where('status', 'checked_out')->with('equipment')->get();
        
        return view('member.dashboard', compact('equipment', 'availableEquipment', 'myLoans', 'activeLoans'));
    }
    
    public function catalog()
    {
        $equipment = Equipment::all();
        $availableEquipment = Equipment::where('status', 'available')->get();
        
        return view('member.catalog', compact('equipment', 'availableEquipment'));
    }
    
    public function loanHistory()
    {
        $loans = Loan::where('user_id', auth()->id())
            ->with('equipment')
            ->latest()
            ->paginate(10);
            
        return view('member.loans', compact('loans'));
    }
    
    public function borrowForm($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        if ($equipment->status !== 'available') {
            return redirect()->route('member.catalog')->with('error', 'Equipment tidak tersedia untuk dipinjam!');
        }
        
        return view('member.borrow', compact('equipment'));
    }
    
    public function borrowStore(Request $request, $id)
    {
        $request->validate(['loan_date' => 'required|date|after_or_equal:today']);
        
        $equipment = Equipment::findOrFail($id);
        
        if ($equipment->status !== 'available') {
            return redirect()->route('member.catalog')->with('error', 'Equipment tidak tersedia!');
        }
        
        $activeLoan = Loan::where('user_id', auth()->id())->where('status', 'checked_out')->first();
        
        if ($activeLoan) {
            return redirect()->route('home')->with('error', 'Anda masih memiliki pinjaman aktif!');
        }
        
        DB::beginTransaction();
        try {
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'equipment_id' => $equipment->id,
                'loan_date' => $request->loan_date,
                'status' => 'checked_out'
            ]);
            
            $equipment->update(['status' => 'borrowed']);
            DB::commit();
            
            return redirect()->route('home')->with('success', 'Berhasil meminjam equipment!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('member.catalog')->with('error', 'Gagal meminjam equipment!');
        }
    }
    
    public function checkin($id)
    {
        $loan = Loan::where('id', $id)->where('user_id', auth()->id())->with('equipment')->firstOrFail();
        
        if ($loan->status === 'checked_in') {
            return redirect()->route('home')->with('error', 'Equipment sudah dikembalikan!');
        }
        
        DB::beginTransaction();
        try {
            $loan->checkin();
            DB::commit();
            
            return redirect()->route('home')->with('success', 'Equipment berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('home')->with('error', 'Gagal mengembalikan equipment!');
        }
    }
}