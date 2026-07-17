<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITAM Enterprise | Secure Login</title>

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
  
  <!-- Custom Modern Tech Login CSS -->
  <style>
    :root {
      --neon-cyan: #00f0ff;
      --neon-purple: #b026ff;
    }
    
    body {
      font-family: 'Inter', sans-serif !important;
      background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(0, 0, 0) 90%);
      color: #e2e8f0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .tech-background {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background-image: 
        radial-gradient(at 80% 0%, rgba(0, 240, 255, 0.15) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(176, 38, 255, 0.15) 0px, transparent 50%);
      z-index: -1;
    }

    .login-box {
      width: 400px;
    }

    .login-logo a {
      color: #fff;
      font-weight: 700;
      font-size: 2.2rem;
      text-shadow: 0 0 10px rgba(0,240,255,0.5);
    }
    
    .login-logo b {
      background: -webkit-linear-gradient(45deg, var(--neon-cyan), var(--neon-purple));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .card {
      background: rgba(15, 23, 42, 0.6) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6), 0 0 15px rgba(0,240,255,0.1);
    }
    
    .login-card-body {
      background: transparent !important;
      padding: 2.5rem 2rem;
    }

    .login-box-msg {
      color: #cbd5e1;
      font-weight: 300;
      letter-spacing: 1px;
    }

    .form-control {
      background-color: rgba(0,0,0,0.2) !important;
      border: 1px solid rgba(255,255,255,0.1);
      color: #fff !important;
      border-radius: 8px 0 0 8px;
    }
    
    .form-control:focus {
      border-color: var(--neon-cyan);
      box-shadow: 0 0 8px rgba(0,240,255,0.3);
    }

    .input-group-text {
      background-color: rgba(0,0,0,0.2) !important;
      border: 1px solid rgba(255,255,255,0.1);
      border-left: none;
      color: var(--neon-cyan);
      border-radius: 0 8px 8px 0;
    }

    .btn-primary {
      background: linear-gradient(90deg, var(--neon-purple), var(--neon-cyan));
      border: none;
      border-radius: 8px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      box-shadow: 0 4px 15px rgba(0,240,255,0.3);
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,240,255,0.5);
    }

    .icheck-primary label {
      color: #cbd5e1;
      font-weight: 400;
    }
    
    a {
      color: var(--neon-cyan);
      transition: color 0.3s;
    }
    a:hover {
      color: var(--neon-purple);
      text-shadow: 0 0 8px rgba(176,38,255,0.5);
    }
  </style>
</head>
<body class="hold-transition login-page dark-mode">
<div class="tech-background"></div>

<div class="login-box">
  <div class="login-logo mb-4 text-center">
    <img src="{{ asset('logo.png') }}" alt="CBA Logo" style="height: 60px; margin-bottom: 10px; display: block; margin: 0 auto;">
    <a href="#"><b>ITAM</b> Enterprise</a>
  </div>
  
  <div class="card theme-card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">SECURE ACCESS GATEWAY</p>

      <!-- Session Status -->
      <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
          <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          @error('username')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        
        <div class="input-group mb-4">
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        
        <div class="row align-items-center">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember_me" name="remember">
              <label for="remember_me" class="theme-text">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block">Sign In <i class="fas fa-sign-in-alt ml-1"></i></button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      @if (Route::has('password.request'))
        <p class="mb-0 mt-4 text-center">
          <a href="{{ route('password.request') }}"><i class="fas fa-key mr-1"></i> I forgot my password</a>
        </p>
      @endif
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
