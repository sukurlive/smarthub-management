<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Loan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEquipment = Equipment::count();
        $availableEquipment = Equipment::where('status', 'available')->count();
        $activeLoans = Loan::where('status', 'checked_out')->count();
        $totalMembers = User::where('role', 'member')->count();
        $recentLoans = Loan::with(['user', 'equipment'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalEquipment', 
            'availableEquipment', 
            'activeLoans', 
            'totalMembers',
            'recentLoans'
        ));
    }
}