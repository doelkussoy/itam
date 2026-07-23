<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITAM Enterprise | Reset Password</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
    :root {
      --color-bg: #0f172a;
      --color-card-bg: rgba(30, 41, 59, 0.4);
      --color-card-border: rgba(255, 255, 255, 0.1);
      --color-text-main: #f8fafc;
      --color-text-muted: #94a3b8;
      --color-primary: #6366f1;
      --color-primary-glow: rgba(99, 102, 241, 0.5);
      --color-primary-hover: #4f46e5;
      --color-input-bg: rgba(15, 23, 42, 0.6);
      --color-input-border: rgba(255, 255, 255, 0.08);
      
      --font-main: 'Inter', sans-serif;
      --radius-lg: 24px;
      --radius-md: 12px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: var(--font-main);
      color: var(--color-text-main);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--color-bg);
      
      /* Vibrant animated background */
      background-image: 
        radial-gradient(circle at 15% 50%, rgba(99, 102, 241, 0.15), transparent 25%),
        radial-gradient(circle at 85% 30%, rgba(168, 85, 247, 0.15), transparent 25%);
      position: relative;
      overflow: hidden;
    }

    /* Animated background blobs */
    body::before, body::after {
      content: '';
      position: absolute;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      filter: blur(80px);
      z-index: -1;
      animation: float 20s infinite ease-in-out alternate;
    }
    body::before {
      background: rgba(99, 102, 241, 0.1);
      top: -200px;
      left: -200px;
    }
    body::after {
      background: rgba(168, 85, 247, 0.1);
      bottom: -200px;
      right: -200px;
      animation-delay: -10s;
    }

    @keyframes float {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(100px, 50px) scale(1.2); }
    }

    /* Glassmorphism Card */
    .login-container {
      width: 100%;
      max-width: 440px;
      padding: 20px;
      z-index: 10;
    }

    .glass-card {
      background: var(--color-card-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--color-card-border);
      border-radius: var(--radius-lg);
      padding: 48px 40px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      text-align: center;
    }

    /* Brand Header */
    .brand-header {
      margin-bottom: 24px;
    }
    .brand-header img {
      height: 52px;
      margin-bottom: 16px;
      filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
    }
    .brand-header h1 {
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: -0.02em;
      margin-bottom: 4px;
    }
    .brand-header h1 span {
      color: #a855f7;
      background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .instruction-text {
        font-size: 0.95rem;
        color: var(--color-text-muted);
        line-height: 1.5;
        margin-bottom: 24px;
    }

    /* Form Styles */
    .form-group {
      text-align: left;
      margin-bottom: 20px;
      position: relative;
    }
    .form-group label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 8px;
      color: #e2e8f0;
    }

    .input-wrapper {
      position: relative;
    }
    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--color-text-muted);
      transition: var(--transition);
    }

    .form-control {
      width: 100%;
      background: var(--color-input-bg);
      border: 1px solid var(--color-input-border);
      border-radius: var(--radius-md);
      padding: 12px 44px;
      color: var(--color-text-main);
      font-family: var(--font-main);
      font-size: 0.95rem;
      transition: var(--transition);
      outline: none;
    }
    
    .form-control::placeholder {
      color: #475569;
    }

    .form-control:focus {
      border-color: var(--color-primary);
      background: rgba(15, 23, 42, 0.8);
      box-shadow: 0 0 0 4px var(--color-primary-glow);
    }
    .form-control:focus ~ .input-icon {
      color: var(--color-primary);
    }
    
    .toggle-password {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--color-text-muted);
      cursor: pointer;
      transition: var(--transition);
      z-index: 10;
    }
    .toggle-password:hover {
      color: var(--color-primary);
    }

    /* Invalid State */
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback {
      display: block;
      color: #f87171;
      font-size: 0.8rem;
      margin-top: 6px;
      text-align: left;
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
      color: white;
      border: none;
      border-radius: var(--radius-md);
      padding: 14px;
      font-size: 1rem;
      font-weight: 600;
      font-family: var(--font-main);
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-top: 32px;
    }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    }
    .btn-submit:active {
      transform: translateY(0);
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="glass-card">
    
    <div class="brand-header">
      <img src="{{ asset('logo.png') }}" alt="ITAM Logo">
      <h1>ITAM <span>Enterprise</span></h1>
    </div>

    <div class="instruction-text">
      {{ __('Create your new password') }}
    </div>

    <form method="POST" action="{{ route('password.store') }}">
      @csrf

      <!-- Password Reset Token -->
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <!-- Email Address -->
      <div class="form-group">
        <label for="email">{{ __('Email') }}</label>
        <div class="input-wrapper">
          <input
            id="email"
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email"
            value="{{ old('email', $request->email) }}"
            required
            autofocus
            autocomplete="username"
            readonly>
          <i class="fas fa-envelope input-icon"></i>
        </div>
        @error('email')
          <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">{{ __('New Password') }}</label>
        <div class="input-wrapper">
          <input
            id="password"
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            name="password"
            placeholder="Enter new password"
            required
            autocomplete="new-password">
          <i class="fas fa-lock input-icon"></i>
          <i class="fas fa-eye toggle-password" onclick="togglePass('password', this)" title="Show/Hide Password"></i>
        </div>
        @error('password')
          <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
        <div class="input-wrapper">
          <input
            id="password_confirmation"
            type="password"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            name="password_confirmation"
            placeholder="Confirm new password"
            required
            autocomplete="new-password">
          <i class="fas fa-lock input-icon"></i>
          <i class="fas fa-eye toggle-password" onclick="togglePass('password_confirmation', this)" title="Show/Hide Password"></i>
        </div>
        @error('password_confirmation')
          <span class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <button type="submit" class="btn-submit">
        <i class="fas fa-save mr-2"></i> {{ __('Reset Password') }}
      </button>

    </form>

  </div>
</div>

<script>
  function togglePass(inputId, iconElement) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
      input.type = 'text';
      iconElement.classList.remove('fa-eye');
      iconElement.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      iconElement.classList.remove('fa-eye-slash');
      iconElement.classList.add('fa-eye');
    }
  }
</script>

</body>
</html>
