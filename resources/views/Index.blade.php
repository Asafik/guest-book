@extends('layouts.partials.app')

@php
  $pageTitle = 'Beranda';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
    @include('layouts.navbar')

    <main>
        <section class="hero" id="home">
            <span class="blur-circle" style="width:140px;height:140px;top:80px;left:20px;"></span>
            <span class="blur-circle" style="width:90px;height:90px;top:130px;right:28%;"></span>
            <span class="blur-circle" style="width:120px;height:120px;bottom:70px;left:8%;"></span>
            <span class="blur-circle" style="width:100px;height:100px;top:180px;right:4%;"></span>

            <div class="container hero-grid">
                <div class="reveal">
                    <h1>
                        <span class="line1" id="typing-line1"></span>
                        <span class="line2" id="typing-line2"></span>
                    </h1>

                    <p>
                        <strong>Jember Command Center</strong> menghadirkan sistem pencatatan tamu
                        terintegrasi untuk seluruh perangkat daerah. Dengan tampilan modern,
                        data kunjungan langsung terpantau, survei kepuasan masyarakat otomatis,
                        dan laporan real-time siap mendukung kebijakan berbasis data.
                    </p>

                    <div class="feature-boxes">
                        <div class="feature-box reveal">
                            <div class="feature-icon"><i class="fas fa-rocket"></i></div>
                            <div class="feature-title">Pelayanan Cepat</div>
                        </div>

                        <div class="feature-box reveal">
                            <div class="feature-icon"><i class="fas fa-database"></i></div>
                            <div class="feature-title">Data Terpadu</div>
                        </div>

                        <div class="feature-box reveal">
                            <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                            <div class="feature-title">Layanan Prima</div>
                        </div>
                    </div>

                    <div class="hero-actions reveal">
                        <a class="btn btn-primary" href="{{ url('/formulir') }}">
                            <i class="fas fa-pen"></i> Isi Formulir
                        </a>
                    </div>
                </div>

               <div class="hero-visual reveal">
                <div class="hero-logo-only">
                    <img
                    src="{{ $globalSetting && $globalSetting->logo ? asset('storage/'.$globalSetting->logo) : asset('jcc.png') }}"
                    alt="{{ $globalSetting->institution_name ?? 'Jember Command Center' }}"
                    onerror="this.src='https://via.placeholder.com/550x450/fff1f5/ff679a?text={{ $globalSetting->institution_short ?? 'JCC' }}'"
                    >
                </div>
                </div>
            </div>
        </section>

        <section id="formulir" style="min-height: 100vh; background: linear-gradient(145deg, var(--bg-start), var(--bg-end)); padding: 100px 0; display: none;"></section>
    </main>

    @include('layouts.footer')
@endsection

@push('scripts')
<script>
    const revealElements = document.querySelectorAll('.reveal');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.16 });

    revealElements.forEach(el => observer.observe(el));

    const line1Element = document.getElementById('typing-line1');
    const line2Element = document.getElementById('typing-line2');

    const line1Text = 'Jember';
    const line2Text = 'Command Center';

    let line1CharIndex = 0;
    let line2CharIndex = 0;
    let isDeleting = false;

    function typeEffect() {
        if (isDeleting) {
            if (line2CharIndex > 0) {
                line2CharIndex--;
                line2Element.textContent = line2Text.substring(0, line2CharIndex);
            } else if (line1CharIndex > 0) {
                line1CharIndex--;
                line1Element.textContent = line1Text.substring(0, line1CharIndex);
            } else {
                isDeleting = false;
                setTimeout(typeEffect, 300);
                return;
            }
        } else {
            if (line1CharIndex < line1Text.length) {
                line1CharIndex++;
                line1Element.textContent = line1Text.substring(0, line1CharIndex);
            } else if (line2CharIndex < line2Text.length) {
                line2CharIndex++;
                line2Element.textContent = line2Text.substring(0, line2CharIndex);
            } else {
                setTimeout(() => {
                    isDeleting = true;
                    typeEffect();
                }, 2000);
                return;
            }
        }

        const speed = isDeleting ? 50 : 100;
        setTimeout(typeEffect, speed);
    }

    window.addEventListener('load', () => {
        line1Element.textContent = '';
        line2Element.textContent = '';
        line1CharIndex = 0;
        line2CharIndex = 0;
        isDeleting = false;
        setTimeout(typeEffect, 500);
    });
</script>
@endpush
