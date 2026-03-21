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
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
