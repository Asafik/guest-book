@extends('layouts.partials.app')

@php
  $pageTitle = 'Login';
@endphp

@section('content')
<div class="login-page">

  <div class="login-bg">
    <div class="bg-circle c1"></div>
    <div class="bg-circle c2"></div>
    <div class="bg-circle c3"></div>
    <div class="bg-circle c4"></div>
    <div class="bg-icon bi-1"><i class="fas fa-users"></i></div>
    <div class="bg-icon bi-2"><i class="fas fa-book-open"></i></div>
    <div class="bg-icon bi-3"><i class="fas fa-clipboard-list"></i></div>
    <div class="bg-icon bi-4"><i class="fas fa-landmark"></i></div>
    <div class="bg-icon bi-5"><i class="fas fa-id-card"></i></div>
  </div>

  <div class="login-card">
    <div class="card-logo">
      <img src="{{ asset('jcc.png') }}" alt="Logo"
        onerror="this.style.display='none'; this.parentElement.innerHTML+='<i class=\'fas fa-landmark\'></i>';">
    </div>

    <div class="card-title">
      <h2>Selamat Datang</h2>
      <p>Masuk ke panel admin JCC</p>
    </div>

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
      @csrf

      <div class="form-group">
        <label>Email</label>
        <div class="input-wrap">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email"
            placeholder="admin@jcc.go.id"
            value="{{ old('email') }}"
            required autofocus>
        </div>
        @error('email')
          <span class="field-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" id="passwordInput"
            placeholder="Masukkan password" required>
          <button type="button" class="toggle-pass" onclick="togglePass()">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
        @error('password')
          <span class="field-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-remember">
        <label class="remember-label">
          <input type="checkbox" name="remember">
          <span>Ingat saya</span>
        </label>
      </div>

      <button type="submit" class="btn-login" id="btnLogin">
        <i class="fas fa-sign-in-alt"></i>
        Masuk
      </button>
    </form>
  </div>

  <!-- Loading Overlay (dari global) -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
      <div class="loading-spinner"></div>
      <p id="loadingText">Memverifikasi akun...</p>
      <small>Mohon tunggu sebentar</small>
    </div>
  </div>

  <!-- Toast (dari global) -->
  <div class="toast-notif" id="toastNotif">
    <i class="fas fa-check-circle" id="toastIcon"></i>
    <span id="toastText"></span>
    <div class="toast-bar" id="toastBar"></div>
  </div>

</div>
@endsection

@push('styles')
<style>
  body { margin: 0; padding: 0; min-height: 100vh; }

  .login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #ff679a 0%, #e05588 50%, #c94070 100%);
    position: relative;
    overflow: hidden;
    padding: 24px;
  }

  .login-bg { position: absolute; inset: 0; pointer-events: none; }

  .bg-circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.07); }

  .c1 { width: 500px; height: 500px; top: -160px; left: -120px; }
  .c2 { width: 350px; height: 350px; bottom: -100px; right: -80px; }
  .c3 { width: 220px; height: 220px; top: 40%; left: 10%; background: rgba(255,255,255,0.05); }
  .c4 { width: 160px; height: 160px; top: 10%; right: 15%; background: rgba(255,255,255,0.06); }

  .bg-icon { position: absolute; color: rgba(255,255,255,0.1); }

  .bi-1 { top: 8%;  left: 6%; font-size: 80px; }
  .bi-2 { top: 15%; right: 8%; font-size: 64px; }
  .bi-3 { bottom: 12%; left: 8%; font-size: 56px; }
  .bi-4 { bottom: 8%; right: 10%; font-size: 72px; }
  .bi-5 { top: 50%; left: 3%; font-size: 48px; }

  .login-card {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 460px;
    background: white;
    border-radius: 28px;
    padding: 32px 44px 40px;
    box-shadow: 0 32px 80px rgba(0,0,0,0.2);
  }

  .card-logo {
    width: 160px; height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
  }

  .card-logo img { width: 100%; height: 100%; object-fit: contain; }
  .card-logo i { font-size: 56px; color: #ff679a; }

  .card-title { text-align: center; margin-bottom: 24px; }
  .card-title h2 { font-size: 24px; font-weight: 800; color: #261a21; margin-bottom: 5px; }
  .card-title p { font-size: 13px; color: #9e8f95; }

  .form-group { margin-bottom: 16px; }

  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #6c5d63;
    margin-bottom: 8px;
  }

  .input-wrap {
    display: flex;
    align-items: center;
    border: 1px solid rgba(255, 103, 154, 0.22);
    border-radius: 12px;
    background: #fffafb;
    overflow: hidden;
    transition: 0.2s;
  }

  .input-wrap:focus-within {
    border-color: #ff679a;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
  }

  .input-wrap > i {
    padding: 0 16px;
    color: #ff679a;
    font-size: 14px;
    flex-shrink: 0;
    margin-right: 10px;
  }

  .input-wrap input {
    flex: 1;
    padding: 13px 12px 13px 0;
    border: none;
    outline: none;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: #261a21;
    background: transparent;
    min-width: 0;
  }

  .input-wrap input::placeholder { color: #bbb; }

  .toggle-pass {
    width: 44px; height: 44px;
    border: none;
    background: transparent;
    color: #9e8f95;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: 0.2s;
    flex-shrink: 0;
  }

  .toggle-pass:hover { color: #ff679a; }

  .field-error { display: block; font-size: 12px; color: #ef4444; margin-top: 6px; }

  .form-remember { margin-bottom: 20px; margin-top: 4px; }

  .remember-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    color: #6c5d63;
    user-select: none;
  }

  .remember-label input[type="checkbox"] {
    width: 16px; height: 16px;
    accent-color: #ff679a;
    cursor: pointer;
  }

  .btn-login {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff679a, #e05588);
    color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 8px 24px rgba(255, 103, 154, 0.4);
    letter-spacing: 0.3px;
  }

  .btn-login:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(255, 103, 154, 0.5); }
  .btn-login:active { transform: translateY(0); }
  .btn-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

  @media (max-width: 560px) {
    .login-card { padding: 24px 24px 32px; border-radius: 22px; max-width: 100%; }
    .card-title h2 { font-size: 22px; }
    .bg-icon { display: none; }
    .card-logo { width: 130px; height: 130px; }
  }
</style>
@endpush

@push('scripts')
<script>
  function togglePass() {
    const input    = document.getElementById('passwordInput');
    const icon     = document.getElementById('eyeIcon');
    const isHidden = input.type === 'password';
    input.type     = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
  }

  document.getElementById('loginForm').addEventListener('submit', function () {
    document.getElementById('loadingOverlay').classList.add('show');
    document.getElementById('btnLogin').disabled = true;
  });

  function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotif');
    const icon  = document.getElementById('toastIcon');
    const bar   = document.getElementById('toastBar');

    document.getElementById('toastText').textContent = message;
    icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-times-circle';
    toast.classList.remove('success', 'error');
    toast.classList.add(type);

    bar.style.animation = 'none';
    bar.offsetHeight;
    bar.style.animation = '';

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  window.addEventListener('load', () => {
    document.getElementById('loadingOverlay').classList.remove('show');
    document.getElementById('btnLogin').disabled = false;

    @if(session('error'))
      showToast(@json(session('error')), 'error');
    @endif

    @if(session('success'))
      showToast(@json(session('success')), 'success');
    @endif
  });
</script>
@endpush
