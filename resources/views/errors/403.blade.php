@extends('layouts.partials.app')

@php
  $pageTitle = 'Akses Ditolak';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('errors/403.css') }}">
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
