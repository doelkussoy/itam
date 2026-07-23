<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITAM Enterprise | Sign In</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

  <!-- Google Fonts: DM Sans + JetBrains Mono (Hallmark Tally) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- AdminLTE base -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <!-- ═══════════════════════════════════════════════════════
       Hallmark · Tally — Login Page
       genre: modern-minimal · theme: Pastel · accent: cool-indigo
       ══════════════════════════════════════════════════════ -->
  <style>
    :root {
      --color-paper-0:     oklch(98.4% 0.005 258);
      --color-paper-1:     oklch(96.2% 0.010 258);
      --color-paper-2:     oklch(93.0% 0.015 258);
      --color-ink-0:       oklch(18.0% 0.030 258);
      --color-ink-1:       oklch(35.0% 0.025 258);
      --color-ink-2:       oklch(52.0% 0.018 258);
      --color-ink-3:       oklch(70.0% 0.012 258);
      --color-accent:      oklch(54.0% 0.220 268);
      --color-accent-soft: oklch(72.0% 0.140 268);
      --color-accent-tint: oklch(94.0% 0.040 268);
      --color-companion:   oklch(82.0% 0.180 130);
      --color-success:     oklch(68.0% 0.150 145);
      --color-danger:      oklch(58.0% 0.200 25);
      --color-focus:       oklch(54.0% 0.220 268);

      --font-display: 'DM Sans', system-ui, sans-serif;
      --font-body:    'DM Sans', system-ui, sans-serif;
      --font-mono:    'JetBrains Mono', ui-monospace, monospace;

      --radius-sm:   6px;
      --radius-md:   10px;
      --radius-lg:   16px;
      --radius-xl:   20px;
      --radius-pill: 999px;

      --rule-soft:   1px solid color-mix(in oklch, var(--color-ink-0) 9%, transparent);
      --shadow-card: 0 1px 3px rgba(30,30,80,0.06), 0 8px 32px rgba(79,70,229,0.10);

      --dur-fast: 150ms;
      --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
    }

    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      margin: 0;
      padding: 0;
      font-family: var(--font-body) !important;
      color: var(--color-ink-0) !important;
      -webkit-font-smoothing: antialiased;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;

      /* Tally pastel radial background */
      background:
        radial-gradient(1200px 600px at 12% -10%, color-mix(in oklch, var(--color-accent-tint) 90%, transparent), transparent 60%),
        radial-gradient(900px 500px at 88% 8%, color-mix(in oklch, var(--color-companion) 35%, transparent), transparent 55%),
        var(--color-paper-0) !important;
      background-attachment: fixed !important;
    }

    /* Subtle dot grid overlay */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background-image:
        radial-gradient(circle, color-mix(in oklch, var(--color-ink-0) 15%, transparent) 1px, transparent 1px);
      background-size: 28px 28px;
      mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 20%, transparent 75%);
      -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 20%, transparent 75%);
      pointer-events: none;
      z-index: 0;
    }

    ::selection { background: var(--color-accent); color: var(--color-paper-0); }

    :focus-visible {
      outline: 2px solid var(--color-focus);
      outline-offset: 3px;
      border-radius: var(--radius-sm);
    }

    /* ── Login wrapper ──────────────────────────────────── */
    .login-wrapper {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 420px;
      padding: 24px 16px;
    }

    /* ── Brand block ────────────────────────────────────── */
    .login-brand {
      text-align: center;
      margin-bottom: 28px;
    }
    .login-brand img {
      height: 48px;
      margin-bottom: 14px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .login-brand h1 {
      font-family: var(--font-display);
      font-size: 1.625rem;
      font-weight: 700;
      letter-spacing: -0.04em;
      color: var(--color-ink-0);
      margin: 0 0 4px;
      line-height: 1;
    }
    .login-brand h1 span {
      color: var(--color-accent);
    }
    .login-brand p {
      font-family: var(--font-mono);
      font-size: 0.6875rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--color-ink-3);
      margin: 0;
    }

    /* ── Login card ─────────────────────────────────────── */
    .login-card {
      background: color-mix(in oklch, var(--color-paper-0) 92%, transparent);
      -webkit-backdrop-filter: blur(24px);
      backdrop-filter: blur(24px);
      border: var(--rule-soft);
      border-radius: var(--radius-xl);
      box-shadow:
        0 0 0 1px rgba(255,255,255,0.8) inset,
        var(--shadow-card);
      padding: 36px 32px;
    }

    .login-card h2 {
      font-family: var(--font-display);
      font-size: 1rem;
      font-weight: 600;
      color: var(--color-ink-0);
      margin: 0 0 20px;
      letter-spacing: -0.02em;
    }

    /* ── Form groups ────────────────────────────────────── */
    .form-group label {
      display: block;
      font-size: 0.8125rem;
      font-weight: 500;
      color: var(--color-ink-1);
      margin-bottom: 6px;
    }

    .input-field {
      width: 100%;
      height: 42px;
      padding: 0 14px;
      font-family: var(--font-body);
      font-size: 0.9375rem;
      color: var(--color-ink-0);
      background: var(--color-paper-0);
      border: var(--rule-soft);
      border-radius: var(--radius-md);
      transition: border-color var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out);
      outline: none;
      -webkit-appearance: none;
    }
    .input-field:focus {
      border-color: var(--color-accent);
      box-shadow: 0 0 0 3px color-mix(in oklch, var(--color-accent) 15%, transparent);
    }
    .input-field::placeholder { color: var(--color-ink-3); }

    .input-with-icon {
      position: relative;
    }
    .input-with-icon .input-field {
      padding-right: 42px;
    }
    .input-with-icon .input-icon {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--color-ink-3);
      font-size: 0.875rem;
      pointer-events: none;
    }

    /* Error state */
    .input-field.is-invalid {
      border-color: var(--color-danger);
    }
    .invalid-feedback {
      display: block;
      font-size: 0.75rem;
      color: oklch(45% 0.180 25);
      margin-top: 4px;
    }
    .valid-feedback { display: none; }

    /* ── Remember + submit row ──────────────────────────── */
    .login-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 24px;
    }

    .remember-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.8125rem;
      color: var(--color-ink-2);
      cursor: pointer;
    }
    .remember-label input[type="checkbox"] {
      width: 15px;
      height: 15px;
      accent-color: var(--color-accent);
      cursor: pointer;
    }

    .btn-signin {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 22px;
      background: var(--color-ink-0);
      color: var(--color-paper-0);
      border: none;
      border-radius: var(--radius-pill);
      font-family: var(--font-body);
      font-size: 0.875rem;
      font-weight: 600;
      letter-spacing: 0.01em;
      cursor: pointer;
      transition: background var(--dur-fast) var(--ease-out), transform var(--dur-fast) var(--ease-out), box-shadow var(--dur-fast) var(--ease-out);
      box-shadow: 0 1px 2px rgba(0,0,0,0.12);
    }
    .btn-signin:hover {
      background: var(--color-accent);
      transform: translateY(-1px);
      box-shadow: 0 4px 16px color-mix(in oklch, var(--color-accent) 35%, transparent);
    }
    .btn-signin:active { transform: translateY(0); }

    /* ── Divider + Forgot link ──────────────────────────── */
    .login-footer {
      margin-top: 20px;
      text-align: center;
      border-top: var(--rule-soft);
      padding-top: 16px;
    }
    .login-footer a {
      font-size: 0.8125rem;
      color: var(--color-accent);
      text-decoration: none;
      font-weight: 500;
      transition: opacity var(--dur-fast);
    }
    .login-footer a:hover { opacity: 0.75; }

    /* ── Session status alert ───────────────────────────── */
    .status-alert {
      background: oklch(93% 0.050 145);
      color: oklch(35% 0.120 145);
      border: 1px solid color-mix(in oklch, oklch(68% 0.150 145) 25%, transparent);
      border-radius: var(--radius-md);
      padding: 10px 14px;
      font-size: 0.875rem;
      margin-bottom: 16px;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: var(--color-paper-1); }
    ::-webkit-scrollbar-thumb {
      background: color-mix(in oklch, var(--color-accent) 30%, var(--color-paper-2));
      border-radius: var(--radius-pill);
    }
  </style>
</head>
<body>

<div class="login-wrapper">
  <!-- Brand -->
  <div class="login-brand">
    <img src="{{ asset('logo.png') }}" alt="ITAM Logo">
    <h1>ITAM <span>Enterprise</span></h1>
    <p>IT Asset Management System</p>
  </div>

  <!-- Card -->
  <div class="login-card">
    <h2>Sign in to your account</h2>

    <!-- Session Status -->
    @if (session('status'))
      <div class="status-alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Username -->
      <div class="form-group mb-3">
        <label for="username">Username</label>
        <div class="input-with-icon">
          <input
            id="username"
            type="text"
            class="input-field @error('username') is-invalid @enderror"
            name="username"
            value="{{ old('username') }}"
            placeholder="Enter your username"
            required
            autofocus
            autocomplete="username">
          <i class="fas fa-user input-icon"></i>
        </div>
        @error('username')
          <span class="invalid-feedback">{{ $message }}</span>
        @enderror
      </div>

      <!-- Password -->
      <div class="form-group mb-0">
        <label for="password">Password</label>
        <div class="input-with-icon">
          <input
            id="password"
            type="password"
            class="input-field @error('password') is-invalid @enderror"
            name="password"
            placeholder="Enter your password"
            required
            autocomplete="current-password">
          <i class="fas fa-lock input-icon"></i>
        </div>
        @error('password')
          <span class="invalid-feedback">{{ $message }}</span>
        @enderror
      </div>

      <!-- Actions -->
      <div class="login-actions">
        <label class="remember-label">
          <input type="checkbox" id="remember_me" name="remember">
          Remember me
        </label>
        <button type="submit" class="btn-signin">
          Sign in
          <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
        </button>
      </div>
    </form>

    @if (Route::has('password.request'))
      <div class="login-footer">
        <a href="{{ route('password.request') }}">
          <i class="fas fa-key" style="font-size: 0.75rem; margin-right: 4px;"></i>Forgot your password?
        </a>
      </div>
    @endif
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
