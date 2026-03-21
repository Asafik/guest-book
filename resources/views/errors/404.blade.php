@extends('layouts.partials.app')

@php
  $pageTitle = 'Halaman Tidak Ditemukan';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('errors/404.css') }}">
@endpush

@section('content')
<section class="error-section">

  {{-- BG DECORATIVE ICONS --}}
  <div class="bg-icons">
    <i class="fas fa-magnifying-glass"></i>
    <i class="fas fa-map-location-dot"></i>
    <i class="fas fa-triangle-exclamation"></i>
    <i class="fas fa-compass"></i>
    <i class="fas fa-link-slash"></i>
    <i class="fas fa-circle-xmark"></i>
    <i class="fas fa-route"></i>
    <i class="fas fa-ghost"></i>
    <i class="fas fa-satellite-dish"></i>
    <i class="fas fa-wifi"></i>
    <i class="fas fa-map-pin"></i>
    <i class="fas fa-binoculars"></i>
    <i class="fas fa-star"></i>
    <i class="fas fa-question-circle"></i>
  </div>

  <div class="container">
    <div class="error-card">

      {{-- ICON TOP --}}
      <div class="error-icon-wrap">
        <i class="fas fa-magnifying-glass"></i>
      </div>

      {{-- NUMBER --}}
      <div class="error-number">404</div>

      <div class="error-divider"></div>

      <h1 class="error-title">Halaman Tidak Ditemukan</h1>
      <p class="error-desc">
        Halaman yang kamu cari tidak ada atau sudah dipindahkan.<br>
        Periksa kembali URL atau kembali ke beranda.
      </p>

      <div class="error-actions">
        <a href="{{ url('/beranda') }}" class="btn-primary">
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
