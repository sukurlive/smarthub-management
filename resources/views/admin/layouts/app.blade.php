<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart-Hub Admin - @yield('title')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        .main-content {
            background-color: #f8f9fc;
            min-height: 100vh;
        }
        .topbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            padding: 15px 20px;
        }
        .card-stats {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
    </style>
    
    @stack('styles')
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-auto col-md-3 col-xl-2 px-0 sidebar">
            <div class="p-4 text-center">
                <h4 class="text-white mb-0">Smart-Hub</h4>
                <small class="text-white-50">Management System</small>
                <hr class="border-light">
            </div>
            <ul class="nav flex-column px-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" 
                       href="{{ route('admin.members.index') }}">
                        <i class="fas fa-users"></i> Member
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}" 
                       href="{{ route('admin.equipment.index') }}">
                        <i class="fas fa-microphone"></i> Equipment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}" 
                       href="{{ route('admin.loans.index') }}">
                        <i class="fas fa-calendar-check"></i> Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-xl-10 main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">@yield('header')</h4>
                    <div>
                        <span class="me-3">
                            <i class="fas fa-user-circle"></i> 
                            {{ Auth::user() ? Auth::user()->name : 'Admin' }}
                        </span>
                        <span class="badge bg-primary">Admin</span>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Auto hide alerts after 3 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.classList.add('fade');
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 3000);
</script>

@stack('scripts')
</body>
</html>