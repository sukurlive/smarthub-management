<?php

use App\Http\Controllers\Auth\InertiaLoginController;
use App\Models\Equipment;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

// Auth Routes
Route::get('/login', [InertiaLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [InertiaLoginController::class, 'login']);
Route::post('/logout', [InertiaLoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Route (Both Admin & Member)
    Route::get('/admin/dashboard', function () {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect()->to('/member/dashboard');
        }
        
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_equipment' => Equipment::count(),
                'available_equipment' => Equipment::where('status', 'available')->count(),
                'borrowed_equipment' => Equipment::where('status', 'borrowed')->count(),
                'maintenance_equipment' => Equipment::where('status', 'maintenance')->count(),
                'active_loans' => Loan::where('status', 'checked_out')->count(),
            ]
        ]);
    })->name('admin.dashboard');

    Route::get('/member/dashboard', function () {
        $user = Auth::user();
        if ($user->role !== 'member') {
            return redirect()->to('/admin/dashboard');
        }

        return Inertia::render('Dashboard', [
            'stats' => [
                'available_equipment' => Equipment::where('status', 'available')->count(),
                'my_active_loans' => Loan::where('user_id', $user->id)->where('status', 'checked_out')->count(),
                'my_total_loans' => Loan::where('user_id', $user->id)->count(),
            ]
        ]);
    })->name('member.dashboard');

    // Equipment Master Data (Both Admin and Member, but Create/Delete is Admin Only)
    Route::get('/equipment', function () {
        return Inertia::render('Equipment/Index', [
            'equipment' => Equipment::all()
        ]);
    })->name('equipment.index');

    // Admin-only Equipment Mutations
    Route::middleware(['admin'])->group(function () {
        Route::post('/equipment', function (Request $request) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:available,borrowed,maintenance'
            ]);
            
            Equipment::create($data);
            return redirect()->back()->with('success', 'Equipment berhasil ditambahkan.');
        });

        Route::delete('/equipment/{id}', function ($id) {
            $equipment = Equipment::findOrFail($id);
            $equipment->delete();
            return redirect()->back()->with('success', 'Equipment berhasil dihapus.');
        });
    });

    // Transactions / Loans (Both Admin and Member)
    Route::get('/loans', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $loans = Loan::with(['user', 'equipment'])->orderBy('created_at', 'desc')->get();
        } else {
            $loans = Loan::with(['equipment'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        return Inertia::render('Loans/Index', [
            'loans' => $loans,
            'availableEquipment' => Equipment::where('status', 'available')->get()
        ]);
    })->name('loans.index');

    // Borrow (Create Transaction)
    Route::post('/loans', function (Request $request) {
        $request->validate([
            'equipment_id'  => 'required|exists:equipment,id',
            'loan_date'     => 'required|date'
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);
        
        if (!$equipment->isAvailable()) {
            return redirect()->back()->with('error', 'Equipment tidak tersedia untuk dipinjam!');
        }

        \DB::beginTransaction();
        try {
            Loan::create([
                'user_id'       => Auth::id(),
                'equipment_id'  => $request->equipment_id,
                'loan_date'     => $request->loan_date,
                'status'        => 'checked_out'
            ]);

            $equipment->update(['status' => 'borrowed']);
            \DB::commit();

            return redirect()->back()->with('success', 'Equipment berhasil dipinjam.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error', 'Gagal meminjam equipment.');
        }
    });

    // Checkin (Update Transaction & Validation)
    Route::post('/loans/{id}/checkin', function ($id) {
        $loan = Loan::findOrFail($id);
        
        if ($loan->status === 'checked_in') {
            return redirect()->back()->with('error', 'Equipment sudah dikembalikan!');
        }

        \DB::beginTransaction();
        try {
            $loan->checkin();
            \DB::commit();
            
            if (class_exists(\App\Events\LoanCheckedIn::class)) {
                event(new \App\Events\LoanCheckedIn($loan));
            }

            return redirect()->back()->with('success', 'Equipment berhasil dikembalikan.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengembalikan equipment.');
        }
    });
});