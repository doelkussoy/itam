<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $app_name ?? 'ITAM Enterprise' }} | @yield('title', 'Dashboard')</title>
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  
  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <!-- Custom Modern Tech CSS -->
  <style>
    :root {
      --neon-cyan: #00f0ff;
      --neon-purple: #b026ff;
      --glass-blur: blur(12px);
      --tech-bg: radial-gradient(circle at top right, #1e293b, #0f172a);
      --tech-panel: rgba(30, 41, 59, 0.7);
      --tech-border: rgba(255, 255, 255, 0.1);
      --text-main: #e2e8f0;
      --text-muted: #94a3b8;
      --input-bg: rgba(0,0,0,0.2);
      --nav-bg: rgba(15, 23, 42, 0.8);
      --table-stripe: rgba(255,255,255,0.02);
      --theme-btn-bg: rgba(255,255,255,0.1);
    }
    
    body.light-mode {
      --tech-bg: #f8fafc;
      --tech-panel: rgba(255, 255, 255, 0.95);
      --tech-border: rgba(226, 232, 240, 0.8);
      --text-main: #1e293b;
      --text-muted: #64748b;
      --input-bg: #ffffff;
      --nav-bg: #ffffff;
      --table-stripe: rgba(241, 245, 249, 0.5);
      --theme-btn-bg: rgba(241, 245, 249, 1);
      --neon-cyan: #0284c7;
      --neon-purple: #7c3aed;
    }

    body {
      font-family: 'Inter', sans-serif !important;
      background: var(--tech-bg) !important;
      background-attachment: fixed !important;
      color: var(--text-main) !important;
    }

    /* Common Theme Classes */
    .theme-card {
      background: var(--tech-panel) !important;
      backdrop-filter: var(--glass-blur);
      -webkit-backdrop-filter: var(--glass-blur);
      border: 1px solid var(--tech-border) !important;
      border-radius: 16px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
    
    .theme-text {
      color: var(--text-main) !important;
    }
    
    .theme-text-white {
      color: #ffffff !important;
    }
    
    .theme-input {
      background-color: var(--input-bg) !important;
      border: 1px solid var(--tech-border) !important;
      color: var(--text-main) !important;
    }
    
    .theme-input:focus {
      background-color: var(--input-bg) !important;
      color: var(--text-main) !important;
      border-color: var(--neon-cyan) !important;
      box-shadow: 0 0 0 0.2rem rgba(2, 132, 199, 0.25) !important;
    }

    /* Glassmorphism Cards & Panels */
    .card, .info-box, .small-box {
      background: var(--tech-panel) !important;
      backdrop-filter: var(--glass-blur);
      -webkit-backdrop-filter: var(--glass-blur);
      border: 1px solid var(--tech-border);
      border-radius: 16px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover, .small-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 32px rgba(0, 240, 255, 0.15);
      border-color: rgba(0, 240, 255, 0.3);
    }

    /* Small Box Adjustments */
    .small-box .icon > i {
      color: rgba(255, 255, 255, 0.15);
      transition: all 0.3s ease;
    }
    .small-box:hover .icon > i {
      transform: scale(1.1);
      color: rgba(255, 255, 255, 0.3);
    }
    .small-box-footer {
      background: rgba(0,0,0,0.2) !important;
      border-bottom-left-radius: 16px;
      border-bottom-right-radius: 16px;
    }

    /* Sidebar and Navbar Tweaks */
    .main-sidebar, .main-header {
      background: var(--nav-bg) !important;
      backdrop-filter: var(--glass-blur);
      -webkit-backdrop-filter: var(--glass-blur);
      border-bottom: 1px solid var(--tech-border);
      border-right: 1px solid var(--tech-border);
    }
    
    .navbar-nav .nav-link {
      color: var(--text-main) !important;
    }
    .navbar-light .navbar-nav .nav-link:hover {
      color: var(--neon-cyan);
    }
    
    .nav-sidebar .nav-item > .nav-link {
      border-radius: 10px;
      transition: all 0.2s ease;
      margin-bottom: 4px;
      color: var(--text-main) !important;
    }
    
    .nav-sidebar .nav-item > .nav-link:hover {
      background: rgba(2, 132, 199, 0.1);
      color: var(--neon-cyan) !important;
    }
    
    .nav-sidebar .nav-item > .nav-link.active {
      background: linear-gradient(90deg, var(--neon-purple), var(--neon-cyan)) !important;
      color: #fff !important;
      box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
      border: none;
    }
    
    .nav-sidebar .nav-item > a.nav-link.active[href="#"] {
      background: rgba(255, 255, 255, 0.05) !important;
      color: var(--neon-cyan) !important;
      border-left: 3px solid var(--neon-cyan) !important;
      box-shadow: none !important;
      border-radius: 8px !important;
    }

    .nav-sidebar .menu-open > a.nav-link[href="#"] {
      background: rgba(255, 255, 255, 0.03) !important;
      border-radius: 8px 8px 0 0 !important;
    }
    
    .nav-header {
      color: var(--text-muted) !important;
    }
    
    .brand-link {
      border-bottom: 1px solid var(--tech-border) !important;
    }

    /* Typography Overrides */
    h1, h2, h3, h4, h5, h6, .brand-text {
      font-weight: 600;
      letter-spacing: 0.5px;
      color: var(--text-main);
    }
    
    /* Form Inputs & Buttons (Rounded) */
    .form-control, .custom-select, select.form-control {
      border-radius: 10px !important;
    }
    .btn {
      border-radius: 8px;
    }
    .action-btn {
      width: 35px !important;
      height: 35px !important;
      border-radius: 10px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      transition: all 0.3s ease;
    }
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    .btn-edit-tech {
      background: rgba(13, 202, 240, 0.15) !important; 
      color: #0dcaf0 !important; 
      border: 1px solid rgba(13, 202, 240, 0.3) !important;
    }
    .btn-delete-tech {
      background: rgba(220, 53, 69, 0.15) !important; 
      color: #dc3545 !important; 
      border: 1px solid rgba(220, 53, 69, 0.3) !important;
    }
    .btn-return-tech {
      background: rgba(40, 167, 69, 0.15) !important;
      color: #28a745 !important;
      border: 1px solid rgba(40, 167, 69, 0.3) !important;
    }
    
    /* Table Glass */
    .table, .theme-table {
      color: var(--text-main) !important;
    }
    .table-striped tbody tr:nth-of-type(odd), .theme-table-striped tbody tr:nth-of-type(odd) {
      background-color: var(--table-stripe) !important;
    }
    .table th, .table td {
      border-top: 1px solid var(--tech-border);
    }
    .card-header {
      border-bottom: 1px solid var(--tech-border);
      background-color: transparent;
    }
    
    /* Modals */
    .modal-content {
      background: var(--nav-bg) !important;
      color: var(--text-main) !important;
      border: 1px solid var(--tech-border) !important;
      backdrop-filter: var(--glass-blur);
    }
    
    /* Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: var(--tech-bg); 
    }
    ::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.2); 
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: var(--neon-cyan); 
    }

    /* Select2 Custom Theme integration */
    .select2-container--default .select2-selection--single {
      background-color: var(--input-bg) !important;
      border: 1px solid var(--tech-border) !important;
      border-radius: 10px !important;
      height: 38px !important;
      display: flex;
      align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--text-main) !important;
      line-height: normal !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px !important;
    }
    .select2-dropdown {
      background-color: var(--nav-bg) !important;
      border: 1px solid var(--tech-border) !important;
      backdrop-filter: var(--glass-blur);
      color: var(--text-main) !important;
    }
    .select2-container--default .select2-results__option--selected {
      background-color: rgba(255,255,255,0.1) !important;
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
      background-color: var(--neon-cyan) !important;
      color: #fff !important;
    }
    .select2-search--dropdown .select2-search__field {
      background-color: var(--input-bg) !important;
      border: 1px solid var(--tech-border) !important;
      color: var(--text-main) !important;
      border-radius: 6px;
    }
    
    /* Fix native select option colors in dark/light mode */
    select option {
      color: var(--text-main) !important;
      background-color: #1e293b !important;
    }
    body.light-mode select option {
      background-color: #ffffff !important;
    }
  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini {{ session('theme', 'dark') }}-mode">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto d-flex align-items-center" style="gap: 15px; padding-right: 15px;">
      <!-- Theme Switch -->
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center justify-content-center" href="{{ session('theme') == 'light' ? route('theme.switch', 'dark') : route('theme.switch', 'light') }}" style="background: var(--theme-btn-bg); color: var(--text-main); border-radius: 20px; padding: 6px 16px; height: 38px; gap: 6px; transition: all 0.3s;" title="Toggle Theme">
          <i class="fas {{ session('theme') == 'light' ? 'fa-moon' : 'fa-sun' }}"></i>
        </a>
      </li>
      <!-- Language Switch -->
      <li class="nav-item">
        <a class="nav-link theme-text d-flex align-items-center justify-content-center" href="{{ App::getLocale() == 'id' ? route('lang.switch', 'en') : route('lang.switch', 'id') }}" style="background: var(--theme-btn-bg); border-radius: 20px; padding: 6px 16px; height: 38px; gap: 6px; transition: all 0.3s;">
          <i class="fas fa-globe"></i> <span style="font-weight: 500; font-size: 14px;">{{ App::getLocale() == 'id' ? 'ID' : 'EN' }}</span>
        </a>
      </li>
      
      @auth
      <!-- User Info -->
      <li class="nav-item">
        <span class="nav-link theme-text d-flex align-items-center" style="height: 38px; gap: 8px; font-weight: 500; font-size: 14px; padding: 0 10px;">
          <i class="far fa-user" style="opacity: 0.8;"></i> {{ Auth::user()?->name ?? 'Guest' }}
        </span>
      </li>

      <!-- Logout Button -->
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}" class="m-0 h-100">
          @csrf
          <a href="#" class="nav-link text-danger d-flex align-items-center justify-content-center" onclick="event.preventDefault(); this.closest('form').submit();" style="background: rgba(220, 53, 69, 0.1); border-radius: 20px; padding: 6px 16px; height: 38px; gap: 6px; transition: all 0.3s;">
            <i class="fas fa-sign-out-alt"></i> <span style="font-weight: 500; font-size: 14px;">{{ __('messages.logout') }}</span>
          </a>
        </form>
      </li>
      @else
      <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-link theme-text d-flex align-items-center" style="height: 38px; gap: 8px; font-weight: 500; font-size: 14px; padding: 0 10px;">
          <i class="fas fa-sign-in-alt" style="opacity: 0.8;"></i> Login
        </a>
      </li>
      @endauth
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link" style="border-bottom: 1px solid var(--tech-border) !important; height: 65px; display: flex; align-items: center; padding: 0 10px; transition: all 0.3s ease; overflow: hidden;">
      <img src="{{ asset('logo.png') }}" alt="Logo" class="brand-image" style="max-height: 32px; max-width: 60px; object-fit: contain; margin-left: -5px; margin-right: 15px; background: transparent; box-shadow: none; border-radius: 0;">
      <span class="brand-text font-weight-bold" style="background: -webkit-linear-gradient(45deg, var(--neon-cyan), var(--neon-purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; white-space: normal; line-height: 1.1; font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word;">{{ $app_name ?? 'ITAM Enterprise' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-4">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt text-info"></i>
              <p>{{ __('messages.dashboard') }}</p>
            </a>
          </li>
          
          @role('Super Admin|Admin')
          <li class="nav-item {{ request()->routeIs('departments.*', 'positions.*', 'brands.*', 'locations.*', 'categories.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('departments.*', 'positions.*', 'brands.*', 'locations.*', 'categories.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-database text-warning"></i>
              <p>
                {{ __('messages.master_data') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-2">
              <li class="nav-item">
                <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-sm"></i>
                  <p>{{ __('messages.department') }}</p>
                </a>
              </li>
              <li class="nav-item"><a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}"><i class="far fa-circle nav-icon text-sm"></i><p>{{ __('messages.brand') }}</p></a></li>
              <li class="nav-item"><a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}"><i class="far fa-circle nav-icon text-sm"></i><p>{{ __('messages.location') }}</p></a></li>
              <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-sm"></i>
                  <p>{{ __('messages.category') }}</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users text-purple"></i>
              <p>{{ __('messages.employee') }}</p>
            </a>
          </li>
          @endrole

          <!-- Asset Menu with Submenus -->
          <li class="nav-item {{ request()->routeIs('assets.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-laptop text-success"></i>
              <p>
                {{ __('messages.it_assets') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-2">
              <li class="nav-item"><a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.index') && !request('category') && !request('status') ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>{{ __('messages.all_assets') }}</p></a></li>
              <li class="nav-item"><a href="{{ route('assets.index', ['category' => 'Komputer']) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == 'Komputer' ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>{{ __('messages.computer') }}</p></a></li>
              <li class="nav-item"><a href="{{ route('assets.index', ['category' => 'Laptop']) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == 'Laptop' ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>{{ __('messages.laptop') }}</p></a></li>
              <li class="nav-item"><a href="{{ route('assets.index', ['category' => 'Mini PC']) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == 'Mini PC' ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>Mini PC</p></a></li>
              <li class="nav-item"><a href="{{ route('assets.index', ['category' => 'Printer']) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == 'Printer' ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>{{ __('messages.printer') }}</p></a></li>
              <li class="nav-item"><a href="{{ route('assets.index', ['category' => 'Switch']) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == 'Switch' ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>Switch</p></a></li>
              <li class="nav-item"><a href="{{ route('assets.index', ['category' => 'Wifi']) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == 'Wifi' ? 'active' : '' }}"><i class="far fa-circle nav-icon text-xs"></i><p>Wifi</p></a></li>
            </ul>
          </li>
          
          <li class="nav-header mt-3 text-uppercase" style="opacity: 0.6; font-size: 0.75rem; letter-spacing: 1px;">{{ __('messages.operations') }}</li>
          
          <li class="nav-item">
            <a href="{{ route('assignments.index') }}" class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt text-warning"></i>
              <p>{{ __('messages.assignment') }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('maintenances.index') }}" class="nav-link {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tools text-danger"></i>
              <p>{{ __('messages.maintenance') }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-ticket-alt text-info"></i>
              <p>{{ __('messages.ticket') ?? 'Ticket' }}</p>
            </a>
          </li>

          <!-- Network Menu with Submenus -->
          <li class="nav-item {{ request()->routeIs('ips.*', 'vlans.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('ips.*', 'vlans.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-network-wired text-success"></i>
              <p>
                {{ __('messages.network_ip') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview pl-2">
              <li class="nav-item">
                <a href="{{ route('ips.index') }}" class="nav-link {{ request()->routeIs('ips.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-xs"></i>
                  <p>{{ __('messages.ip_address') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('vlans.index') }}" class="nav-link {{ request()->routeIs('vlans.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-xs"></i>
                  <p>{{ __('messages.vlan_config') }}</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('software_licenses.index') }}" class="nav-link {{ request()->routeIs('software_licenses.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-key text-warning"></i>
              <p>{{ __('messages.software_license') }}</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('password_vaults.index') }}" class="nav-link {{ request()->routeIs('password_vaults.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-lock text-info"></i>
              <p>{{ __('messages.password_vault') }}</p>
            </a>
          </li>
          
          @role('Super Admin|Admin')
          <li class="nav-header mt-3 text-uppercase" style="opacity: 0.6; font-size: 0.75rem; letter-spacing: 1px;">{{ __('messages.system') }}</li>
          
          <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-shield text-info"></i>
              <p>{{ __('messages.user_management') ?? 'User Management' }}</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs text-secondary"></i>
              <p>{{ __('messages.setting') ?? 'Setting' }}</p>
            </a>
          </li>
          @endrole
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: transparent;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold theme-text">@yield('title')</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid pb-4">
        @yield('content')
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="background-color: var(--nav-bg); backdrop-filter: blur(10px); border-top: 1px solid var(--tech-border); color: var(--text-muted);">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> <span class="text-info">1.0.0</span>
    </div>
    <strong>Copyright &copy; {{ date('Y') }} <span style="color: var(--neon-cyan);">{{ $app_name ?? 'ITAM Enterprise' }} ({{ $company_name ?? 'CBA Chemical Industry' }})</span>.</strong> Team IT Pabrik.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      // Initialize select2 with 'resolve' to respect inline width styles (like 200px)
      $('.select2').select2({
          width: 'resolve'
      });

      // Auto-focus the search field when select2 is opened
      $(document).on('select2:open', () => {
          document.querySelector('.select2-search__field').focus();
      });
  });

  $(document).on('click', '.btn-delete', function(e) {
      e.preventDefault();
      var form = $(this).closest('form');
      var message = $(this).data('confirm-message') || 'Apakah Anda yakin ingin menghapus data ini?';
      
      Swal.fire({
          title: 'Konfirmasi Hapus',
          text: message,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          background: 'rgba(30, 41, 59, 0.95)',
          color: '#f8f9fa',
          backdrop: `rgba(0,0,0,0.6)`
      }).then((result) => {
          if (result.isConfirmed) {
              form.submit();
          }
      });
  });
</script>
@stack('scripts')
</body>
</html>
