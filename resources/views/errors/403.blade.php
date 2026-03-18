@extends('layouts.partials.app')

@php
  $pageTitle = 'Akses Ditolak';
@endphp

@push('styles')
<style>
  .error-section {
    min-height: calc(100vh - 82px - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
  }

  /* ===== BG DECORATIVE ICONS ===== */
  .bg-icons {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
  }

  .bg-icons i {
    position: absolute;
    color: rgba(255, 103, 154, 0.08);
    animation: floatIcon linear infinite;
  }

  .bg-icons i:nth-child(1)  { font-size: 48px; top: 8%;  left: 5%;  animation-duration: 14s; animation-delay: 0s; }
  .bg-icons i:nth-child(2)  { font-size: 32px; top: 15%; left: 18%; animation-duration: 18s; animation-delay: 2s; }
  .bg-icons i:nth-child(3)  { font-size: 56px; top: 5%;  left: 35%; animation-duration: 12s; animation-delay: 1s; }
  .bg-icons i:nth-child(4)  { font-size: 38px; top: 20%; left: 55%; animation-duration: 16s; animation-delay: 3s; }
  .bg-icons i:nth-child(5)  { font-size: 44px; top: 10%; left: 72%; animation-duration: 15s; animation-delay: 0.5s; }
  .bg-icons i:nth-child(6)  { font-size: 30px; top: 18%; left: 88%; animation-duration: 19s; animation-delay: 4s; }
  .bg-icons i:nth-child(7)  { font-size: 52px; top: 55%; left: 3%;  animation-duration: 13s; animation-delay: 1.5s; }
  .bg-icons i:nth-child(8)  { font-size: 36px; top: 70%; left: 15%; animation-duration: 17s; animation-delay: 2.5s; }
  .bg-icons i:nth-child(9)  { font-size: 42px; top: 80%; left: 30%; animation-duration: 11s; animation-delay: 0.8s; }
  .bg-icons i:nth-child(10) { font-size: 28px; top: 60%; left: 50%; animation-duration: 20s; animation-delay: 3.5s; }
  .bg-icons i:nth-child(11) { font-size: 50px; top: 75%; left: 68%; animation-duration: 14s; animation-delay: 1.2s; }
  .bg-icons i:nth-child(12) { font-size: 34px; top: 85%; left: 82%; animation-duration: 16s; animation-delay: 2.8s; }
  .bg-icons i:nth-child(13) { font-size: 40px; top: 40%; left: 92%; animation-duration: 13s; animation-delay: 0.3s; }
  .bg-icons i:nth-child(14) { font-size: 46px; top: 45%; left: 8%;  animation-duration: 18s; animation-delay: 4.5s; }

  @keyframes floatIcon {
    0%   { transform: translateY(0px) rotate(0deg);   opacity: 0.5; }
    25%  { transform: translateY(-14px) rotate(5deg);  opacity: 1;   }
    50%  { transform: translateY(-6px) rotate(-4deg);  opacity: 0.6; }
    75%  { transform: translateY(-18px) rotate(3deg);  opacity: 0.9; }
    100% { transform: translateY(0px) rotate(0deg);   opacity: 0.5; }
  }

  /* ===== CARD ===== */
  .error-card {
    background: var(--card-bg-strong);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    padding: 60px 48px;
    text-align: center;
    max-width: 500px;
    width: 100%;
    position: relative;
    z-index: 1;
    margin: 0 auto;
  }

  .error-card::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255, 103, 154, 0.10), transparent 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  /* ===== ICON TOP ===== */
  .error-icon-wrap {
    width: 72px;
    height: 72px;
    background: var(--primary-light);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    animation: shake 3s ease-in-out infinite;
  }

  .error-icon-wrap i {
    font-size: 30px;
    color: var(--primary);
  }

  @keyframes shake {
    0%, 100% { transform: rotate(0deg)   scale(1);    }
    15%       { transform: rotate(-8deg)  scale(1.05); }
    30%       { transform: rotate(8deg)   scale(1.05); }
    45%       { transform: rotate(-5deg)  scale(1);    }
    60%       { transform: rotate(5deg)   scale(1);    }
    75%       { transform: rotate(0deg)   scale(1);    }
  }

  /* ===== NUMBER ===== */
  .error-number {
    font-size: 110px;
    font-weight: 800;
    line-height: 1;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -4px;
    margin-bottom: 4px;
    animation: floatUp 3s ease-in-out infinite;
  }

  @keyframes floatUp {
    0%, 100% { transform: translateY(0px);  }
    50%       { transform: translateY(-8px); }
  }

  .error-divider {
    width: 56px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-soft));
    border-radius: 999px;
    margin: 16px auto 24px;
  }

  .error-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 10px;
  }

  .error-desc {
    font-size: 14.5px;
    color: var(--text-muted);
    line-height: 1.7;
    margin-bottom: 36px;
  }

  /* ===== BUTTONS ===== */
  .error-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 26px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    font-weight: 600;
    font-size: 14.5px;
    border-radius: 14px;
    border: none;
    cursor: pointer;
    transition: 0.22s ease;
    box-shadow: 0 8px 20px rgba(255, 103, 154, 0.35);
    text-decoration: none;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(255, 103, 154, 0.45);
    color: #fff;
  }

  .btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 26px;
    background: transparent;
    color: var(--primary);
    font-weight: 600;
    font-size: 14.5px;
    border-radius: 14px;
    border: 2px solid var(--card-border);
    cursor: pointer;
    transition: 0.22s ease;
    text-decoration: none;
  }

  .btn-outline:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    transform: translateY(-2px);
    color: var(--primary-dark);
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .error-card {
      padding: 40px 24px;
    }
    .error-number {
      font-size: 88px;
    }
    .error-title {
      font-size: 19px;
    }
    .error-actions {
      flex-direction: column;
      align-items: center;
    }
    .btn-primary,
    .btn-outline {
      width: 100%;
      justify-content: center;
    }
  }
</style>
@endpush

@section('content')
<section class="error-section">

  {{-- BG DECORATIVE ICONS --}}
  <div class="bg-icons">
    <i class="fas fa-lock"></i>
    <i class="fas fa-shield-halved"></i>
    <i class="fas fa-ban"></i>
    <i class="fas fa-key"></i>
    <i class="fas fa-user-lock"></i>
    <i class="fas fa-circle-xmark"></i>
    <i class="fas fa-hand"></i>
    <i class="fas fa-eye-slash"></i>
    <i class="fas fa-door-closed"></i>
    <i class="fas fa-fence"></i>
    <i class="fas fa-triangle-exclamation"></i>
    <i class="fas fa-fingerprint"></i>
    <i class="fas fa-id-badge"></i>
    <i class="fas fa-shield-xmark"></i>
  </div>

  <div class="container">
    <div class="error-card">

      {{-- ICON TOP --}}
      <div class="error-icon-wrap">
        <i class="fas fa-lock"></i>
      </div>

      {{-- NUMBER --}}
      <div class="error-number">403</div>

      <div class="error-divider"></div>

      <h1 class="error-title">Akses Ditolak</h1>
      <p class="error-desc">
        Kamu tidak memiliki izin untuk mengakses halaman ini.<br>
        Silakan login atau hubungi administrator jika kamu merasa ini kesalahan.
      </p>

      <div class="error-actions">
        <a href="{{ url('/') }}" class="btn-primary">
          <i class="fas fa-house"></i>
          Kembali ke Beranda
        </a>
        <a href="javascript:history.back()" class="btn-outline">
          <i class="fas fa-arrow-left"></i>
          Halaman Sebelumnya
        </a>
      </div>

    </div>
  </div>
</section>
@endsection

@push('scripts')
@endpush
