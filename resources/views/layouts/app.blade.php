<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Task Manager') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B2B;
            --orange-dark: #e5561e;
            --orange-light: rgba(255, 107, 43, 0.1);
            --sidebar-bg: #1a1d27;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 68px;
            --topnav-height: 62px;
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: #f5f6fa; }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            transition: width 0.28s ease;
            z-index: 1050;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }
        .sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 18px;
            height: var(--topnav-height);
            color: var(--orange);
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            white-space: nowrap;
            flex-shrink: 0;
        }
        .sidebar-brand i { font-size: 22px; flex-shrink: 0; }
        .sidebar-brand .brand-text {
            transition: opacity 0.2s, width 0.28s;
            overflow: hidden;
        }
        .sidebar.collapsed .sidebar-brand .brand-text { opacity: 0; width: 0; }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 10px 0;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 11px 20px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            transition: color 0.18s, background 0.18s, border-left-color 0.18s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link i { font-size: 19px; flex-shrink: 0; }
        .sidebar-nav .nav-link .link-text {
            transition: opacity 0.18s, width 0.28s;
            overflow: hidden;
        }
        .sidebar.collapsed .sidebar-nav .nav-link .link-text { opacity: 0; width: 0; }
        .sidebar-nav .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .sidebar-nav .nav-link.active {
            color: var(--orange);
            border-left-color: var(--orange);
            background: var(--orange-light);
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 8px 0;
        }

        /* ── Main wrapper ────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.28s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-wrapper.sidebar-collapsed { margin-left: var(--sidebar-collapsed-width); }

        /* ── Top Navbar ──────────────────────────── */
        .top-navbar {
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            height: var(--topnav-height);
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 1040;
            flex-shrink: 0;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: #6b7280;
            font-size: 23px;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 6px;
            line-height: 1;
            transition: color 0.18s, background 0.18s;
        }
        .toggle-btn:hover { color: var(--orange); background: var(--orange-light); }

        .search-wrap {
            flex: 1;
            position: relative;
            max-width: 380px;
        }
        .search-wrap .bi-search {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
        }
        .search-wrap input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            background: #f9fafb;
            color: #374151;
            outline: none;
            transition: border-color 0.18s;
        }
        .search-wrap input:focus { border-color: var(--orange); background: #fff; }

        .nav-actions { display: flex; align-items: center; gap: 12px; margin-left: auto; }

        .notif-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            color: #6b7280;
            font-size: 20px;
            transition: color 0.18s, background 0.18s;
        }
        .notif-wrap:hover { color: var(--orange); background: var(--orange-light); }
        .notif-badge {
            position: absolute;
            top: 4px; right: 4px;
            width: 15px; height: 15px;
            background: var(--orange);
            border-radius: 50%;
            font-size: 9px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--orange);
            cursor: pointer;
        }
        .dropdown-toggle::after { display: none; }

        .user-dropdown-menu {
            min-width: 230px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.14);
            padding: 0;
            overflow: hidden;
            margin-top: 8px !important;
        }
        .dropdown-user-header {
            padding: 14px 16px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .dropdown-user-header .u-name {
            font-weight: 700;
            color: #111827;
            font-size: 14px;
            line-height: 1.3;
        }
        .dropdown-user-header .u-email {
            font-size: 12px;
            color: #6b7280;
            margin-top: 1px;
        }
        .user-dropdown-menu .dropdown-item {
            padding: 10px 16px;
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .user-dropdown-menu .dropdown-item:hover { background: #f5f6fa; color: var(--orange); }
        .user-dropdown-menu .dropdown-item.text-danger:hover { background: #fff1f1; color: #dc2626; }
        .user-dropdown-menu .dropdown-divider { margin: 4px 0; border-color: #e5e7eb; }

        /* ── Page Content ────────────────────────── */
        .page-content { padding: 28px 24px; flex: 1; }

        /* ── Footer ──────────────────────────────── */
        .page-footer {
            padding: 14px 24px;
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }

        /* ── Guest Layout (login/register) ───────── */
        .guest-body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1d27 0%, #2d3348 100%);
            display: flex;
            flex-direction: column;
        }
        .guest-navbar {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .guest-brand {
            color: var(--orange);
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .guest-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 13px;
            margin-left: 20px;
            transition: color 0.18s;
        }
        .guest-links a:hover { color: var(--orange); }
        .guest-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }
        .guest-card {
            background: #fff;
            border-radius: 16px;
            padding: 36px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
                width: var(--sidebar-width);
            }
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
            .main-wrapper, .main-wrapper.sidebar-collapsed {
                margin-left: 0;
            }
            .search-wrap { display: none; }
            .page-content { padding: 20px 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth

{{-- ═══════════════════════════════════════════════════════════
     AUTHENTICATED LAYOUT — sidebar + top navbar
═══════════════════════════════════════════════════════════ --}}

<div class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <i class="bi bi-check2-square"></i>
        <span class="brand-text">Task Manager</span>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tasks.index') }}"
               class="nav-link {{ request()->routeIs('tasks.index') || request()->routeIs('tasks.show') || request()->routeIs('tasks.edit') ? 'active' : '' }}">
                <i class="bi bi-list-task"></i>
                <span class="link-text">All Tasks</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tasks.create') }}"
               class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i>
                <span class="link-text">Create Task</span>
            </a>
        </li>
        @if(in_array(Auth::user()->role, ['Admin', 'Super Admin']))
        <li>
            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span class="link-text">Users</span>
            </a>
        </li>
        @endif
        <li>
            <a href="#" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span class="link-text">Settings</span>
            </a>
        </li>

        <li class="sidebar-divider"></li>

        <li>
            <a href="#" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span class="link-text">Logout</span>
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </li>
    </ul>
</div>

<div class="main-wrapper" id="mainWrapper">

    {{-- Top Navbar --}}
    <nav class="top-navbar">
        <button class="toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search tasks, users...">
        </div>

        <div class="nav-actions">
            <div class="notif-wrap">
                <i class="bi bi-bell"></i>
                <span class="notif-badge">3</span>
            </div>

            <div class="dropdown">
                <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->image_url }}" alt="{{ Auth::user()->name }}" class="user-avatar">
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                    <div class="dropdown-user-header">
                        <div class="u-name">{{ Auth::user()->name }}</div>
                        <div class="u-email">{{ Auth::user()->email }}</div>
                    </div>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">
                            <i class="bi bi-person-circle"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.password') }}">
                            <i class="bi bi-key-fill"></i> Reset Password
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('nav-logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="nav-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success') || session('error'))
    <div style="padding: 16px 24px 0;">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
    @endif

    {{-- Page Content --}}
    <div class="page-content">
        @yield('content')
    </div>

    <footer class="page-footer">
        &copy; 2026 Task Manager. All rights reserved.
    </footer>
</div>

@else

{{-- ═══════════════════════════════════════════════════════════
     GUEST LAYOUT — centered card (login / register)
═══════════════════════════════════════════════════════════ --}}

<div class="guest-body">
    <nav class="guest-navbar">
        <a href="{{ url('/') }}" class="guest-brand">
            <i class="bi bi-check2-square" style="font-size:22px;"></i>
            Task Manager
        </a>
        <div class="guest-links">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </nav>
    <div class="guest-content">
        <div class="guest-card">
            @yield('content')
        </div>
    </div>
</div>

@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    const toggleBtn = document.getElementById('sidebarToggle');
    if (!sidebar || !toggleBtn) return;

    const KEY = 'sidebar_collapsed';
    if (localStorage.getItem(KEY) === '1') {
        sidebar.classList.add('collapsed');
        mainWrapper.classList.add('sidebar-collapsed');
    }

    toggleBtn.addEventListener('click', function () {
        const collapsed = sidebar.classList.toggle('collapsed');
        mainWrapper.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem(KEY, collapsed ? '1' : '0');
    });
})();
</script>
@stack('scripts')
</body>
</html>
