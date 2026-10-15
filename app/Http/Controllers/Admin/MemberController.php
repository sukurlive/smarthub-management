<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index()
    {
        $members = User::where('role', 'member')->latest()->paginate(10);
        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')
            ->whereHas('loans', function($q) {
                $q->where('status', 'checked_out');
            })->count();
        $inactiveMembers = $totalMembers - $activeMembers;
        
        return view('admin.members.index', compact('members', 'totalMembers', 'activeMembers', 'inactiveMembers'));
    }
    
    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('admin.members.create');
    }
    
    /**
     * Store a newly created member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil ditambahkan!');
    }
    
    /**
     * Display the specified member.
     */
    public function show($id)
    {
        $member = User::with(['loans' => function($q) {
            $q->with('equipment')->latest();
        }])->findOrFail($id);
        
        $activeLoans = $member->loans->where('status', 'checked_out')->count();
        $totalLoans = $member->loans->count();
        $recentLoans = $member->loans->take(5);
        
        return view('admin.members.show', compact('member', 'activeLoans', 'totalLoans', 'recentLoans'));
    }
    
    /**
     * Show the form for editing the specified member.
     */
    public function edit($id)
    {
        $member = User::findOrFail($id);
        return view('admin.members.edit', compact('member'));
    }
    
    /**
     * Update the specified member.
     */
    public function update(Request $request, $id)
    {
        $member = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $member->update($data);
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil diupdate!');
    }
    
    /**
     * Remove the specified member.
     */
    public function destroy($id)
    {
        $member = User::findOrFail($id);
        
        // Check if member has active loans
        $activeLoans = Loan::where('user_id', $id)->where('status', 'checked_out')->count();
        
        if ($activeLoans > 0) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Member masih memiliki pinjaman aktif!');
        }
        
        // Delete member's loans first
        Loan::where('user_id', $id)->delete();
        $member->delete();
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus!');
    }
    
    /**
     * Reset password for member.
     */
    public function resetPassword($id)
    {
        $member = User::findOrFail($id);
        $defaultPassword = 'password123';
        
        $member->update([
            'password' => Hash::make($defaultPassword)
        ]);
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Password member berhasil direset menjadi: ' . $defaultPassword);
    }
    
    /**
     * Ban member (set status inactive).
     */
    public function toggleStatus($id)
    {
        $member = User::findOrFail($id);
        $member->is_active = !$member->is_active;
        $member->save();
        
        $status = $member->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.members.index')
            ->with('success', "Member berhasil {$status}!");
    }
    
    /**
     * Search members.
     */
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $members = User::where('role', 'member')
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')
            ->whereHas('loans', function($q) {
                $q->where('status', 'checked_out');
            })->count();
        $inactiveMembers = $totalMembers - $activeMembers;
            
        return view('admin.members.index', compact('members', 'totalMembers', 'activeMembers', 'inactiveMembers', 'search'));
    }
    
    /**
     * Export members to CSV.
     */
    public function export()
    {
        $members = User::where('role', 'member')->get();
        
        $filename = "members_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, ['ID', 'Nama', 'Email', 'No. Telepon', 'Alamat', 'Tanggal Bergabung', 'Total Pinjaman', 'Pinjaman Aktif']);
        
        foreach ($members as $member) {
            fputcsv($handle, [
                $member->id,
                $member->name,
                $member->email,
                $member->phone ?? '-',
                $member->address ?? '-',
                $member->created_at->format('d/m/Y'),
                $member->loans->count(),
                $member->loans->where('status', 'checked_out')->count()
            ]);
        }
        
        fclose($handle);
        exit;
    }
}