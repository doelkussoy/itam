<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $app_name ?? 'ITAM Enterprise' }} | @yield('title', 'Dashboard')</title>
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

  <!-- Google Fonts: DM Sans + JetBrains Mono (Hallmark Tally) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

  <!-- ═══════════════════════════════════════════════════════
       Hallmark · Tally Theme — Admin Adaptation
       genre: modern-minimal · theme: Pastel
       paper-band: light · accent-hue: cool-indigo
       ══════════════════════════════════════════════════════ -->
  <style>
    /* ── Design Tokens ──────────────────────────────────── */
    :root {
      --color-paper-0: oklch(98.4% 0.005 258);
      --color-paper-1: oklch(96.2% 0.010 258);
      --color-paper-2: oklch(93.0% 0.015 258);
      --color-paper-3: oklch(89.0% 0.020 258);
      --color-ink-0:   oklch(18.0% 0.030 258);
      --color-ink-1:   oklch(35.0% 0.025 258);
      --color-ink-2:   oklch(52.0% 0.018 258);
      --color-ink-3:   oklch(70.0% 0.012 258);

      --color-accent:       oklch(54.0% 0.220 268);
      --color-accent-soft:  oklch(72.0% 0.140 268);
      --color-accent-tint:  oklch(94.0% 0.040 268);
      --color-companion:    oklch(82.0% 0.180 130);
      --color-warning:      oklch(74.0% 0.180 50);
      --color-success:      oklch(68.0% 0.150 145);
      --color-danger:       oklch(58.0% 0.200 25);
      --color-focus:        oklch(54.0% 0.220 268);

      --font-display: 'DM Sans', system-ui, sans-serif;
      --font-body:    'DM Sans', system-ui, sans-serif;
      --font-mono:    'JetBrains Mono', ui-monospace, monospace;

      --space-xs:  4px;
      --space-sm:  8px;
      --space-md:  16px;
      --space-lg:  24px;
      --space-xl:  32px;
      --space-2xl: 48px;

      --radius-sm:   6px;
      --radius-md:   10px;
      --radius-lg:   16px;
      --radius-xl:   20px;
      --radius-pill: 999px;

      --rule-soft: 1px solid color-mix(in oklch, var(--color-ink-0) 9%, transparent);

      --shadow-sm:         0 1px 2px rgba(30, 30, 80, 0.06);
      --shadow-card:       0 1px 3px rgba(30,30,80,0.06), 0 4px 16px rgba(79,70,229,0.07);
      --shadow-card-hover: 0 4px 24px rgba(79,70,229,0.16);
      --shadow-nav:        0 1px 0 rgba(255,255,255,0.7) inset, 0 8px 30px -12px rgba(20,30,80,0.18);

      --dur-fast:    150ms;
      --dur-normal:  250ms;
      --ease-out:    cubic-bezier(0.16, 1, 0.3, 1);
      --ease-in-out: cubic-bezier(0.45, 0, 0.55, 1);

      /* ── Legacy Variables Mapping ───────────────────────── */
      --tech-bg: var(--color-paper-0);
      --tech-panel: var(--color-paper-0);
      --tech-border: color-mix(in oklch, var(--color-ink-0) 12%, transparent);
      --glass-blur: 0px;
      --neon-cyan: var(--color-accent);
      --neon-purple: oklch(65% 0.12 280);
      --text-main: var(--color-ink-0);
      --text-muted: var(--color-ink-2);
      --input-bg: var(--color-paper-1);
    }

    /* ── Base ───────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      font-family: var(--font-body) !important;
      color: var(--color-ink-0) !important;
      -webkit-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
    }

    body {
      background:
        radial-gradient(1100px 560px at 10% -8%, color-mix(in oklch, var(--color-accent-tint) 80%, transparent), transparent 60%),
        radial-gradient(800px 480px at 90% 6%,  color-mix(in oklch, var(--color-companion) 28%, transparent),    transparent 55%),
        var(--color-paper-0) !important;
      background-attachment: fixed !important;
    }

    ::selection { background: var(--color-accent); color: var(--color-paper-0); }

    :focus-visible {
      outline: 2px solid var(--color-focus);
      outline-offset: 3px;
      border-radius: var(--radius-sm);
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: var(--font-display) !important;
      font-weight: 600;
      letter-spacing: -0.02em;
      color: var(--color-ink-0) !important;
    }

    /* ── Scrollbar ──────────────────────────────────────── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--color-paper-1); }
    ::-webkit-scrollbar-thumb {
      background: color-mix(in oklch, var(--color-accent) 30%, var(--color-paper-2));
      border-radius: var(--radius-pill);
    }
    ::-webkit-scrollbar-thumb:hover { background: var(--color-accent-soft); }

    /* ── Top Navbar ─────────────────────────────────────── */
    .main-header.navbar {
      background: color-mix(in oklch, var(--color-paper-0) 85%, transparent) !important;
      -webkit-backdrop-filter: blur(20px);
      backdrop-filter: blur(20px);
      border-bottom: var(--rule-soft) !important;
      box-shadow: var(--shadow-sm) !important;
      min-height: 60px;
      padding: 0 var(--space-md);
    }

    .main-header .navbar-nav .nav-link {
      color: var(--color-ink-1) !important;
      font-size: 0.875rem;
      font-weight: 500;
      transition: color var(--dur-fast) var(--ease-out);
    }
    .main-header .navbar-nav .nav-link:hover {
      color: var(--color-accent) !important;
    }

    /* Navbar right-side pill buttons */
    .nav-pill-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      height: 36px;
      border-radius: var(--radius-pill);
      font-size: 0.8125rem;
      font-weight: 500;
      background: var(--color-paper-1);
      border: var(--rule-soft);
      color: var(--color-ink-1) !important;
      transition: background var(--dur-fast) var(--ease-out), color var(--dur-fast) var(--ease-out), border-color var(--dur-fast) var(--ease-out);
      text-decoration: none;
    }
    .nav-pill-btn:hover {
      background: var(--color-accent-tint);
      color: var(--color-accent) !important;
      border-color: color-mix(in oklch, var(--color-accent) 25%, transparent);
      text-decoration: none;
    }
    .nav-pill-btn.danger {
      background: oklch(97% 0.015 25);
      color: var(--color-danger) !important;
      border-color: color-mix(in oklch, var(--color-danger) 20%, transparent);
    }
    .nav-pill-btn.danger:hover {
      background: oklch(94% 0.030 25);
    }

    /* ── Main Sidebar ───────────────────────────────────── */
    .main-sidebar, .main-sidebar::before {
      background: var(--color-paper-0) !important;
      border-right: var(--rule-soft) !important;
      box-shadow: 2px 0 8px rgba(30,30,80,0.04) !important;
    }

    /* Override AdminLTE dark-sidebar class */
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
    .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
      box-shadow: none !important;
    }

    .brand-link {
      background: var(--color-paper-0) !important;
      border-bottom: var(--rule-soft) !important;
      height: 60px;
      display: flex;
      align-items: center;
      padding: 0 var(--space-md);
      transition: background var(--dur-fast) var(--ease-out);
    }

    .brand-link:hover { background: var(--color-paper-1) !important; }

    .brand-text {
      font-family: var(--font-display) !important;
      font-weight: 700;
      font-size: 0.95rem;
      letter-spacing: -0.02em;
      color: var(--color-ink-0) !important;
      -webkit-text-fill-color: unset !important;
      background: none !important;
      -webkit-background-clip: unset !important;
    }

    /* Sidebar nav items */
    .nav-sidebar .nav-item > .nav-link {
      border-radius: var(--radius-md) !important;
      margin: 2px 8px !important;
      padding: 9px 14px !important;
      color: var(--color-ink-1) !important;
      font-size: 0.875rem;
      font-weight: 500;
      transition: background var(--dur-fast) var(--ease-out), color var(--dur-fast) var(--ease-out);
    }
    .nav-sidebar .nav-item > .nav-link:hover {
      background: var(--color-paper-2) !important;
      color: var(--color-ink-0) !important;
    }
    .nav-sidebar .nav-item > .nav-link.active {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
    }
    .nav-sidebar .nav-item > .nav-link .nav-icon {
      color: inherit !important;
      opacity: 0.7;
      width: 1.4em;
    }
    .nav-sidebar .nav-item > .nav-link.active .nav-icon {
      opacity: 1;
    }

    /* Sidebar submenu */
    .nav-treeview > .nav-item > .nav-link {
      border-radius: var(--radius-md) !important;
      margin: 1px 8px 1px 24px !important;
      padding: 7px 12px !important;
      color: var(--color-ink-2) !important;
      font-size: 0.8125rem;
    }
    .nav-treeview > .nav-item > .nav-link:hover {
      background: var(--color-paper-2) !important;
      color: var(--color-ink-0) !important;
    }
    .nav-treeview > .nav-item > .nav-link.active {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
    }
    .nav-sidebar .nav-item > .nav-link .far.fa-circle {
      color: inherit !important;
    }

    .nav-header {
      color: var(--color-ink-3) !important;
      font-family: var(--font-mono) !important;
      font-size: 0.6875rem !important;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding: 12px 16px 4px !important;
    }

    /* Treeview arrow */
    .nav-sidebar .right.fa-angle-left { color: var(--color-ink-3) !important; }

    /* ── Content Wrapper ────────────────────────────────── */
    .content-wrapper {
      background: transparent !important;
    }

    .content-header h1 {
      font-size: 1.5rem !important;
      font-weight: 700;
      letter-spacing: -0.03em;
      color: var(--color-ink-0) !important;
    }

    /* ── Cards ──────────────────────────────────────────── */
    .card, .info-box, .small-box {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-lg) !important;
      box-shadow: var(--shadow-card) !important;
      backdrop-filter: none;
      -webkit-backdrop-filter: none;
      transition: box-shadow var(--dur-normal) var(--ease-out), transform var(--dur-normal) var(--ease-out);
    }
    .card:hover, .small-box:hover {
      box-shadow: var(--shadow-card-hover) !important;
      transform: translateY(-2px);
    }

    .card-header {
      background: transparent !important;
      border-bottom: var(--rule-soft) !important;
      padding: var(--space-md) var(--space-lg);
    }
    .card-title {
      color: var(--color-ink-0) !important;
      font-weight: 600;
      font-size: 0.9375rem;
    }
    .card-body { color: var(--color-ink-1) !important; }

    /* ── Small Stat Boxes ───────────────────────────────── */
    .small-box { overflow: hidden; }
    .small-box > .inner { padding: var(--space-lg); }
    .small-box > .inner h3 {
      font-size: 2.25rem !important;
      font-weight: 700;
      letter-spacing: -0.04em;
      color: var(--color-ink-0) !important;
    }
    .small-box > .inner p {
      font-size: 0.8125rem;
      font-weight: 500;
      color: var(--color-ink-2) !important;
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }
    .small-box > .icon > i {
      font-size: 70px !important;
      color: color-mix(in oklch, var(--color-ink-0) 6%, transparent) !important;
    }
    .small-box-footer {
      background: var(--color-paper-2) !important;
      color: var(--color-ink-2) !important;
      font-size: 0.8125rem;
      font-weight: 500;
      border-bottom-left-radius: var(--radius-lg) !important;
      border-bottom-right-radius: var(--radius-lg) !important;
      padding: 10px var(--space-lg);
      transition: background var(--dur-fast), color var(--dur-fast);
    }
    .small-box-footer:hover {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
    }

    /* Accent left borders for stat boxes */
    .small-box.accent-indigo  { border-left: 3px solid var(--color-accent) !important; }
    .small-box.accent-lime    { border-left: 3px solid var(--color-companion) !important; }
    .small-box.accent-success { border-left: 3px solid var(--color-success) !important; }
    .small-box.accent-warning { border-left: 3px solid var(--color-warning) !important; }
    .small-box.accent-soft    { border-left: 3px solid var(--color-accent-soft) !important; }
    .small-box.accent-danger  { border-left: 3px solid var(--color-danger) !important; }

    /* ── Tables ─────────────────────────────────────────── */
    .table, .theme-table {
      color: var(--color-ink-1) !important;
    }
    .table thead th {
      font-family: var(--font-mono) !important;
      font-size: 0.6875rem;
      font-weight: 500;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: var(--color-ink-2) !important;
      border-bottom: var(--rule-soft) !important;
      border-top: none !important;
      padding: 10px 12px;
    }
    .table tbody td {
      border-top: var(--rule-soft) !important;
      padding: 10px 12px;
      color: var(--color-ink-1) !important;
      font-size: 0.875rem;
      vertical-align: middle;
    }
    .table tbody tr:hover td {
      background: var(--color-paper-1) !important;
    }
    .table-striped tbody tr:nth-of-type(odd) {
      background: transparent !important;
    }

    /* ── Forms ──────────────────────────────────────────── */
    .form-control, .custom-select, select.form-control {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-md) !important;
      color: var(--color-ink-0) !important;
      font-family: var(--font-body) !important;
      font-size: 0.875rem;
      transition: border-color var(--dur-fast), box-shadow var(--dur-fast);
    }
    .form-control:focus, .custom-select:focus, select.form-control:focus {
      background: var(--color-paper-0) !important;
      color: var(--color-ink-0) !important;
      border-color: var(--color-accent) !important;
      box-shadow: 0 0 0 3px color-mix(in oklch, var(--color-accent) 15%, transparent) !important;
    }
    .form-control::placeholder { color: var(--color-ink-3) !important; }
    label { color: var(--color-ink-1) !important; font-size: 0.8125rem; font-weight: 500; }

    .input-group-text {
      background: var(--color-paper-1) !important;
      border: var(--rule-soft) !important;
      color: var(--color-ink-2) !important;
    }

    /* ── Buttons ────────────────────────────────────────── */
    .btn {
      font-family: var(--font-body) !important;
      font-weight: 500;
      border-radius: var(--radius-pill) !important;
      transition: all var(--dur-fast) var(--ease-out);
      font-size: 0.875rem;
      letter-spacing: 0.01em;
    }

    /* Primary = dark pill */
    .btn-primary {
      background: var(--color-ink-0) !important;
      border-color: var(--color-ink-0) !important;
      color: var(--color-paper-0) !important;
      box-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .btn-primary:hover {
      background: var(--color-accent) !important;
      border-color: var(--color-accent) !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px color-mix(in oklch, var(--color-accent) 30%, transparent);
    }
    .btn-primary:active { transform: translateY(0); }

    /* Secondary */
    .btn-secondary, .btn-default {
      background: var(--color-paper-1) !important;
      border: var(--rule-soft) !important;
      color: var(--color-ink-1) !important;
    }
    .btn-secondary:hover, .btn-default:hover {
      background: var(--color-paper-2) !important;
      color: var(--color-ink-0) !important;
    }

    /* Danger */
    .btn-danger {
      background: oklch(95% 0.025 25) !important;
      border-color: color-mix(in oklch, var(--color-danger) 25%, transparent) !important;
      color: var(--color-danger) !important;
    }
    .btn-danger:hover {
      background: var(--color-danger) !important;
      border-color: var(--color-danger) !important;
      color: white !important;
    }

    /* Success */
    .btn-success {
      background: oklch(95% 0.025 145) !important;
      border-color: color-mix(in oklch, var(--color-success) 25%, transparent) !important;
      color: oklch(38% 0.120 145) !important;
    }
    .btn-success:hover {
      background: var(--color-success) !important;
      border-color: var(--color-success) !important;
      color: white !important;
    }

    /* Warning */
    .btn-warning {
      background: oklch(96% 0.035 50) !important;
      border-color: color-mix(in oklch, var(--color-warning) 30%, transparent) !important;
      color: oklch(42% 0.140 50) !important;
    }
    .btn-warning:hover {
      background: var(--color-warning) !important;
      border-color: var(--color-warning) !important;
      color: white !important;
    }

    /* Info */
    .btn-info {
      background: var(--color-accent-tint) !important;
      border-color: color-mix(in oklch, var(--color-accent) 25%, transparent) !important;
      color: var(--color-accent) !important;
    }
    .btn-info:hover {
      background: var(--color-accent) !important;
      border-color: var(--color-accent) !important;
      color: white !important;
    }

    /* Action icon buttons */
    .action-btn {
      width: 32px !important;
      height: 32px !important;
      border-radius: var(--radius-md) !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      transition: all var(--dur-fast) var(--ease-out);
      padding: 0 !important;
      font-size: 0.875rem !important;
    }
    .action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* ── Badges ─────────────────────────────────────────── */
    .badge {
      font-family: var(--font-mono) !important;
      font-size: 0.6875rem !important;
      font-weight: 500;
      letter-spacing: 0.04em;
      padding: 4px 10px !important;
      border-radius: var(--radius-pill) !important;
    }
    .badge-primary   { background: var(--color-accent-tint) !important; color: var(--color-accent) !important; }
    .badge-success   { background: oklch(93% 0.050 145) !important; color: oklch(35% 0.120 145) !important; }
    .badge-danger    { background: oklch(94% 0.030 25) !important;  color: oklch(45% 0.180 25) !important; }
    .badge-warning   { background: oklch(95% 0.050 50) !important;  color: oklch(42% 0.160 50) !important; }
    .badge-info      { background: var(--color-accent-tint) !important; color: var(--color-accent) !important; }
    .badge-secondary { background: var(--color-paper-2) !important; color: var(--color-ink-2) !important; }

    /* ── Modals ─────────────────────────────────────────── */
    .modal-content {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-xl) !important;
      box-shadow: 0 20px 60px rgba(20,30,80,0.18) !important;
    }
    .modal-header {
      border-bottom: var(--rule-soft) !important;
      padding: var(--space-lg);
    }
    .modal-footer {
      border-top: var(--rule-soft) !important;
      padding: var(--space-md) var(--space-lg);
    }
    .modal-title { color: var(--color-ink-0) !important; font-weight: 600; }
    .close { color: var(--color-ink-2) !important; }
    .close:hover { color: var(--color-ink-0) !important; }

    /* ── Alerts ─────────────────────────────────────────── */
    .alert {
      border-radius: var(--radius-md) !important;
      border: none !important;
      font-size: 0.875rem;
    }
    .alert-success { background: oklch(93% 0.050 145) !important; color: oklch(35% 0.120 145) !important; }
    .alert-danger  { background: oklch(94% 0.030 25) !important;  color: oklch(40% 0.180 25) !important; }
    .alert-warning { background: oklch(95% 0.050 50) !important;  color: oklch(38% 0.160 50) !important; }
    .alert-info    { background: var(--color-accent-tint) !important; color: var(--color-accent) !important; }

    /* ── Pagination ─────────────────────────────────────── */
    .pagination .page-link {
      color: var(--color-ink-1) !important;
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-md) !important;
      font-size: 0.875rem;
      transition: all var(--dur-fast);
    }
    .pagination .page-link:hover {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
      border-color: color-mix(in oklch, var(--color-accent) 25%, transparent) !important;
    }
    .pagination .page-item.active .page-link {
      background: var(--color-accent) !important;
      border-color: var(--color-accent) !important;
      color: white !important;
    }

    /* ── Select2 ────────────────────────────────────────── */
    .select2-container--default .select2-selection--single {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-md) !important;
      height: 38px !important;
      display: flex;
      align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--color-ink-0) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 36px !important;
    }
    .select2-dropdown {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-md) !important;
      box-shadow: var(--shadow-card-hover) !important;
      color: var(--color-ink-0) !important;
    }
    .select2-container--default .select2-results__option--selected {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
    }
    .select2-container--default .select2-results__option--highlighted {
      background: var(--color-accent) !important;
      color: white !important;
    }
    .select2-search--dropdown .select2-search__field {
      background: var(--color-paper-1) !important;
      border: var(--rule-soft) !important;
      color: var(--color-ink-0) !important;
      border-radius: var(--radius-sm) !important;
    }
    select option {
      background: var(--color-paper-0) !important;
      color: var(--color-ink-0) !important;
    }

    /* ── Footer ─────────────────────────────────────────── */
    .main-footer {
      background: var(--color-paper-0) !important;
      border-top: var(--rule-soft) !important;
      color: var(--color-ink-3) !important;
      font-size: 0.8125rem;
    }
    .main-footer strong { color: var(--color-ink-1) !important; }

    /* ── Eyebrow / Mono labels ──────────────────────────── */
    .eyebrow, .mono-label {
      font-family: var(--font-mono) !important;
      font-size: 0.6875rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--color-ink-2) !important;
    }

    /* ── Utility helpers ────────────────────────────────── */
    .theme-text  { color: var(--color-ink-0) !important; }
    .theme-muted { color: var(--color-ink-2) !important; }
    .theme-card  {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-lg) !important;
      box-shadow: var(--shadow-card) !important;
    }
    .theme-input {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      color: var(--color-ink-0) !important;
    }
    .theme-input:focus {
      border-color: var(--color-accent) !important;
      box-shadow: 0 0 0 3px color-mix(in oklch, var(--color-accent) 15%, transparent) !important;
    }

    /* ── Category mini-cards (dashboard) ────────────────── */
    .cat-mini-card {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-lg) !important;
      box-shadow: var(--shadow-card) !important;
      transition: all var(--dur-normal) var(--ease-out);
    }
    .cat-mini-card:hover {
      box-shadow: var(--shadow-card-hover) !important;
      transform: translateY(-2px);
      border-color: color-mix(in oklch, var(--color-accent) 30%, transparent) !important;
    }
    .cat-mini-card h3, .cat-mini-card .cat-count {
      color: var(--color-accent) !important;
      font-weight: 700;
      font-size: 1.5rem;
    }
    .cat-mini-card span, .cat-mini-card .cat-label {
      color: var(--color-ink-2) !important;
      font-size: 0.75rem;
      font-weight: 500;
    }

    /* Remove old neon-cyan/purple text fills */
    .text-info { color: var(--color-accent) !important; }
    .text-purple { color: var(--color-accent-soft) !important; }
    .text-cyan { color: var(--color-accent) !important; }
    .text-success { color: var(--color-success) !important; }
    .text-warning { color: var(--color-warning) !important; }
    .text-danger { color: var(--color-danger) !important; }
    .text-muted { color: var(--color-ink-3) !important; }

    /* ── Breadcrumbs ────────────────────────────────────── */
    .breadcrumb {
      background: transparent !important;
      padding: 0;
    }
    .breadcrumb-item a { color: var(--color-accent) !important; }
    .breadcrumb-item.active { color: var(--color-ink-2) !important; }
    .breadcrumb-item + .breadcrumb-item::before { color: var(--color-ink-3) !important; }

    /* ── Dropdown menus ─────────────────────────────────── */
    .dropdown-menu {
      background: var(--color-paper-0) !important;
      border: var(--rule-soft) !important;
      border-radius: var(--radius-lg) !important;
      box-shadow: var(--shadow-card-hover) !important;
    }
    .dropdown-item {
      color: var(--color-ink-1) !important;
      font-size: 0.875rem;
      border-radius: var(--radius-sm) !important;
    }
    .dropdown-item:hover {
      background: var(--color-paper-1) !important;
      color: var(--color-ink-0) !important;
    }

    /* ── iCheck fix ─────────────────────────────────────── */
    .icheck-primary label { color: var(--color-ink-1) !important; }

    /* ══════════════════════════════════════════════════════
       Hallmark Tally · Dark Mode Variant
       paper-band: dark-navy · accent: cool-indigo (same)
       ═════════════════════════════════════════════════════ */
    body.dark-mode {
      --color-paper-0: oklch(14.0% 0.015 258);
      --color-paper-1: oklch(18.0% 0.018 258);
      --color-paper-2: oklch(22.0% 0.020 258);
      --color-paper-3: oklch(27.0% 0.022 258);
      --color-ink-0:   oklch(95.0% 0.005 258);
      --color-ink-1:   oklch(80.0% 0.010 258);
      --color-ink-2:   oklch(60.0% 0.015 258);
      --color-ink-3:   oklch(42.0% 0.018 258);

      /* Accent stays indigo — slightly brighter for dark bg */
      --color-accent:       oklch(62.0% 0.220 268);
      --color-accent-soft:  oklch(74.0% 0.140 268);
      --color-accent-tint:  oklch(22.0% 0.060 268);
      --color-companion:    oklch(70.0% 0.180 130);
      --color-success:      oklch(65.0% 0.150 145);
      --color-danger:       oklch(62.0% 0.200 25);
      --color-warning:      oklch(72.0% 0.180 50);

      --rule-soft:          1px solid color-mix(in oklch, var(--color-ink-0) 10%, transparent);
      --shadow-card:        0 1px 3px rgba(0,0,0,0.30), 0 4px 16px rgba(0,0,0,0.25);
      --shadow-card-hover:  0 4px 24px rgba(0,0,0,0.40);

      background:
        radial-gradient(1100px 560px at 10% -8%, color-mix(in oklch, oklch(22% 0.060 268) 60%, transparent), transparent 60%),
        radial-gradient(800px 480px at 90% 6%, color-mix(in oklch, oklch(22% 0.040 130) 40%, transparent), transparent 55%),
        var(--color-paper-0) !important;
    }

    /* Dark mode component overrides */
    body.dark-mode .main-header.navbar {
      background: color-mix(in oklch, var(--color-paper-1) 90%, transparent) !important;
    }
    body.dark-mode .main-sidebar,
    body.dark-mode .main-sidebar::before {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode .brand-link {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode .brand-link:hover {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .card,
    body.dark-mode .info-box,
    body.dark-mode .small-box {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode .card-header { background: transparent !important; }
    body.dark-mode .small-box-footer {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .modal-content {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode .nav-pill-btn {
      background: var(--color-paper-2);
      border-color: color-mix(in oklch, var(--color-ink-0) 10%, transparent);
    }
    body.dark-mode .nav-pill-btn:hover {
      background: var(--color-accent-tint);
    }
    body.dark-mode .form-control,
    body.dark-mode .custom-select,
    body.dark-mode select.form-control {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .form-control:focus,
    body.dark-mode .custom-select:focus {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .input-group-text {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .select2-container--default .select2-selection--single,
    body.dark-mode .select2-dropdown,
    body.dark-mode .select2-search--dropdown .select2-search__field {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode select option {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode .main-footer {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode .dropdown-menu {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .dropdown-item:hover {
      background: var(--color-paper-3) !important;
    }
    body.dark-mode .badge-success { background: oklch(20% 0.050 145) !important; color: oklch(72% 0.120 145) !important; }
    body.dark-mode .badge-danger  { background: oklch(20% 0.040 25) !important;  color: oklch(72% 0.180 25) !important; }
    body.dark-mode .badge-warning { background: oklch(20% 0.050 50) !important;  color: oklch(76% 0.160 50) !important; }
    body.dark-mode .badge-secondary { background: var(--color-paper-3) !important; color: var(--color-ink-2) !important; }
    body.dark-mode .btn-secondary, body.dark-mode .btn-default {
      background: var(--color-paper-2) !important;
    }
    body.dark-mode .btn-danger {
      background: oklch(20% 0.040 25) !important;
      color: oklch(72% 0.180 25) !important;
    }
    body.dark-mode .btn-danger:hover {
      background: var(--color-danger) !important;
      color: white !important;
    }
    body.dark-mode .btn-success {
      background: oklch(20% 0.050 145) !important;
      color: oklch(72% 0.120 145) !important;
    }
    body.dark-mode .btn-success:hover {
      background: var(--color-success) !important;
      color: white !important;
    }
    body.dark-mode .btn-warning {
      background: oklch(20% 0.050 50) !important;
      color: oklch(76% 0.160 50) !important;
    }
    body.dark-mode .btn-warning:hover {
      background: var(--color-warning) !important;
      color: white !important;
    }
    body.dark-mode .btn-info {
      background: var(--color-accent-tint) !important;
      color: var(--color-accent) !important;
    }
    body.dark-mode .cat-mini-card {
      background: var(--color-paper-1) !important;
    }
    body.dark-mode ::-webkit-scrollbar-track {
      background: var(--color-paper-0);
    }
    /* Dark mode SweetAlert */
    body.dark-mode .swal2-popup {
      background: var(--color-paper-1) !important;
      color: var(--color-ink-0) !important;
    }
  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini {{ session('theme', 'light') }}-mode" style="min-height: 100vh;">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left: toggle -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: var(--color-ink-1);">
          <i class="fas fa-bars" style="font-size: 1rem;"></i>
        </a>
      </li>
    </ul>

    <!-- Right: controls -->
    <ul class="navbar-nav ml-auto" style="gap: 8px; padding-right: 12px; display: flex; align-items: center; flex-direction: row;">

      <!-- Theme Switch -->
      <li class="nav-item">
        <a class="nav-pill-btn"
           href="{{ session('theme') == 'dark' ? route('theme.switch', 'light') : route('theme.switch', 'dark') }}"
           title="Toggle Theme">
          <i class="fas {{ session('theme') == 'dark' ? 'fa-sun' : 'fa-moon' }}" style="font-size: 0.8rem;"></i>
        </a>
      </li>

      <!-- Language Switch -->
      <li class="nav-item">
        <a class="nav-pill-btn"
           href="{{ App::getLocale() == 'id' ? route('lang.switch', 'en') : route('lang.switch', 'id') }}">
          <i class="fas fa-globe" style="font-size: 0.8rem;"></i>
          <span>{{ App::getLocale() == 'id' ? 'ID' : 'EN' }}</span>
        </a>
      </li>

      @auth
      <!-- User Info -->
      <li class="nav-item">
        <span style="display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 12px; font-size: 0.8125rem; font-weight: 500; color: var(--color-ink-1);">
          <i class="far fa-user" style="font-size: 0.8rem; color: var(--color-ink-3);"></i>
          {{ Auth::user()?->name ?? __('messages.guest') ?? 'Guest' }}
        </span>
      </li>


      @else
      <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-pill-btn">
          <i class="fas fa-sign-in-alt" style="font-size: 0.8rem;"></i>
          <span>{{ __('messages.login') ?? 'Login' }}</span>
        </a>
      </li>
      @endauth
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar -->
  <aside class="main-sidebar sidebar-light-primary elevation-0">
    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="brand-link" style="text-decoration: none;">
      <img src="{{ asset('logo.png') }}" alt="Logo"
           style="max-height: 30px; max-width: 52px; object-fit: contain; margin-right: 12px;">
      <span class="brand-text">{{ $app_name ?? 'ITAM Enterprise' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="background: transparent;">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          @can('menu_dashboard')
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>{{ __('messages.dashboard') }}</p>
            </a>
          </li>
          @endcan

          
          @canany(['menu_departments', 'menu_brands', 'menu_locations', 'menu_categories'])
          <li class="nav-header">{{ __('messages.master_data') }}</li>

          <li class="nav-item {{ request()->routeIs('departments.*', 'positions.*', 'brands.*', 'locations.*', 'categories.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('departments.*', 'positions.*', 'brands.*', 'locations.*', 'categories.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-database"></i>
              <p>
                {{ __('messages.master_data') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('menu_departments')
              <li class="nav-item">
                <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ __('messages.department') }}</p>
                </a>
              </li>
              @endcan
              @can('menu_brands')
              <li class="nav-item">
                <a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ __('messages.brand') }}</p>
                </a>
              </li>
              @endcan
              @can('menu_locations')
              <li class="nav-item">
                <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ __('messages.location') }}</p>
                </a>
              </li>
              @endcan
              @can('menu_categories')
              <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ __('messages.category') }}</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endcanany

          @can('menu_employees')
          <li class="nav-item">
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>{{ __('messages.employee') }}</p>
            </a>
          </li>
          @endcan

          <li class="nav-header">{{ __('messages.it_assets') }}</li>

          <!-- Asset Menu -->
          @can('menu_assets')
          <li class="nav-item {{ request()->routeIs('assets.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-laptop"></i>
              <p>
                {{ __('messages.it_assets') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.index') && !request('category') && !request('status') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>{{ __('messages.all_assets') }}</p></a></li>
              @php
                  $topCategories = \App\Models\Category::withCount('assets')
                      ->having('assets_count', '>', 0)
                      ->orderBy('assets_count', 'desc')
                      ->limit(6)
                      ->get();
              @endphp
              @foreach($topCategories as $topCat)
              <li class="nav-item">
                  <a href="{{ route('assets.index', ['category' => $topCat->name]) }}" class="nav-link {{ request()->routeIs('assets.index') && request('category') == $topCat->name ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{ $topCat->name }}</p>
                  </a>
              </li>
              @endforeach
            </ul>
          </li>

          @endcan

          <li class="nav-header">{{ __('messages.operations') }}</li>

          @can('menu_assignments')
          <li class="nav-item">
            <a href="{{ route('assignments.index') }}" class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>{{ __('messages.assignment') }}</p>
            </a>
          </li>
          @endcan
          @can('menu_maintenances')
          <li class="nav-item">
            <a href="{{ route('maintenances.index') }}" class="nav-link {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tools"></i>
              <p>{{ __('messages.maintenance') }}</p>
            </a>
          </li>
          @endcan
          @can('menu_tickets')
          <li class="nav-item">
            <a href="{{ route('tickets.index') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>{{ __('messages.ticket') ?? 'Ticket' }}</p>
            </a>
          </li>
          @endcan

          <!-- Network -->
          @canany(['menu_ips', 'menu_vlans'])
          <li class="nav-item {{ request()->routeIs('ips.*', 'vlans.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('ips.*', 'vlans.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-network-wired"></i>
              <p>
                {{ __('messages.network_ip') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('menu_ips')
              <li class="nav-item">
                <a href="{{ route('ips.index') }}" class="nav-link {{ request()->routeIs('ips.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ __('messages.ip_address') }}</p>
                </a>
              </li>
              @endcan
              @can('menu_vlans')
              <li class="nav-item">
                <a href="{{ route('vlans.index') }}" class="nav-link {{ request()->routeIs('vlans.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{ __('messages.vlan_config') }}</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          @endcanany

          @can('menu_software_licenses')
          <li class="nav-item">
            <a href="{{ route('software_licenses.index') }}" class="nav-link {{ request()->routeIs('software_licenses.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-key"></i>
              <p>{{ __('messages.software_license') }}</p>
            </a>
          </li>
          @endcan

          @can('menu_password_vaults')
          <li class="nav-item">
            <a href="{{ route('password_vaults.index') }}" class="nav-link {{ request()->routeIs('password_vaults.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-lock"></i>
              <p>{{ __('messages.password_vault') }}</p>
            </a>
          </li>
          @endcan

          
          @canany(['menu_users', 'menu_settings', 'menu_roles'])
          <li class="nav-header">{{ __('messages.system') }}</li>

          @can('menu_users')
          <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>{{ __('messages.user_management') ?? 'Login Management' }}</p>
            </a>
          </li>
          @endcan
          @can('menu_settings')
          <li class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>{{ __('messages.setting') ?? 'Setting' }}</p>
            </a>
          </li>
          @endcan

          @can('menu_roles')
          <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Hak Akses</p>
            </a>
          </li>
          @endcan
          @endcanany

          <!-- Logout -->
          <li class="nav-item mt-3">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
              @csrf
              <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>{{ __('messages.logout') ?? 'Logout' }}</p>
              </a>
            </form>
          </li>

        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper" style="background: transparent;">
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('title')</h1>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid pb-4">
        @yield('content')
      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <span style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--color-ink-3);">v1.0.0</span>
    </div>
    <strong style="color: var(--color-ink-1);">
      &copy; {{ date('Y') }}
      <span style="color: var(--color-accent);">{{ $app_name ?? 'ITAM Enterprise' }}</span>
      — {{ $company_name ?? 'CBA Chemical Industry' }}
    </strong>
    <span style="color: var(--color-ink-3); margin-left: 4px;">· Team IT Pabrik</span>
  </footer>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('.select2').select2({ width: 'resolve' });
    $(document).on('select2:open', () => {
      document.querySelector('.select2-search__field').focus();
    });
  });

  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    var message = $(this).data('confirm-message') || "{{ __('messages.confirm_delete') ?? 'Are you sure you want to delete this data?' }}";
    var isDark = $('body').hasClass('dark-mode');
    Swal.fire({
      title: "{{ __('messages.deleting_data') ?? 'Deleting Data' }}",
      text: message,
      icon: 'warning',
      iconColor: 'oklch(58.0% 0.200 25)',
      showCancelButton: true,
      confirmButtonColor: 'oklch(58.0% 0.200 25)', // Red/Danger
      cancelButtonColor: isDark ? 'oklch(35.0% 0.025 258)' : 'oklch(89.0% 0.020 258)', // Muted
      confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> ' + ("{{ __('messages.yes_delete') ?? 'Yes, Delete' }}"),
      cancelButtonText: "{{ __('messages.cancel') ?? 'Cancel' }}",
      background: isDark ? 'oklch(18.0% 0.018 258)' : 'oklch(98.4% 0.005 258)',
      color: isDark ? 'oklch(95.0% 0.005 258)' : 'oklch(18.0% 0.030 258)',
      reverseButtons: true,
      customClass: { popup: 'rounded-xl shadow-lg border border-secondary', confirmButton: 'rounded-pill', cancelButton: 'rounded-pill' }
    }).then((result) => {
      if (result.isConfirmed) { form.submit(); }
    });
  });

  // Seamless AJAX search and pagination
  function loadTableData(url, btn) {
    let originalIcon = btn ? btn.html() : null;
    if (btn) btn.html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
      url: url,
      type: 'GET',
      success: function(response) {
        let parser = new DOMParser();
        let doc = parser.parseFromString(response, 'text/html');
        
        let newTable = $(doc).find('.table-responsive');
        let newPagination = $(doc).find('.card-footer');
        
        if (newTable.length) {
          $('.table-responsive').replaceWith(newTable);
          
          let currentFooter = $('.card-footer');
          if (currentFooter.length && newPagination.length) {
            currentFooter.replaceWith(newPagination);
          } else if (currentFooter.length) {
            currentFooter.remove(); // No pagination on new result
          } else if (newPagination.length) {
            $('.table-responsive').closest('.card').append(newPagination);
          }
          
          window.history.pushState({}, '', url);
        }
        if (btn) btn.html(originalIcon);
      },
      error: function() {
        if (btn) btn.closest('form').submit(); // Fallback
        else window.location.href = url;
      }
    });
  }

  function triggerAjaxSearch(form) {
    let url = form.attr('action') || window.location.href.split('?')[0];
    let params = form.serialize();
    loadTableData(url + '?' + params, form.find('button[type="submit"]'));
  }

  let searchTimeout;
  $(document).on('input', 'input[name="search"]', function() {
    let form = $(this).closest('form');
    if (form.length) {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(function() {
        triggerAjaxSearch(form);
      }, 500);
    }
  });

  $(document).on('change', 'form:has(input[name="search"]) select', function() {
    let form = $(this).closest('form');
    if (form.length) {
      triggerAjaxSearch(form);
    }
  });

  $(document).on('click', '.card-footer .pagination a', function(e) {
    e.preventDefault();
    loadTableData($(this).attr('href'), null);
  });
</script>
@stack('scripts')
</body>
</html>
