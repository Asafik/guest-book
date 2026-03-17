@extends('layouts.partials.app')

@php
  $pageTitle = 'Beranda';
@endphp

@push('styles')
<style>
    .hero {
        position: relative;
        min-height: calc(100vh - 82px);
        display: flex;
        align-items: center;
        overflow: hidden;
        padding: 10px 0;
    }

    .blur-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 103, 154, 0.08);
        filter: blur(50px);
        pointer-events: none;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.02fr 0.98fr;
        gap: 40px;
        align-items: center;
        padding: 10px 0;
    }

    .hero h1 {
        margin-top: 0;
        font-size: clamp(40px, 7vw, 62px);
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -1.2px;
        color: var(--primary);
        max-width: 680px;
        min-height: 140px;
        display: flex;
        flex-direction: column;
    }

    .hero h1 .line1 {
        display: block;
        margin-bottom: -5px;
    }

    .hero h1 .line2 {
        display: block;
        margin-top: -5px;
    }

    .typing-cursor {
        display: inline-block;
        width: 4px;
        margin-left: 4px;
        background-color: var(--primary);
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }

    .hero p {
        margin-top: 12px;
        max-width: 600px;
        font-size: 18px;
        line-height: 1.6;
        color: var(--text-soft);
    }

    .feature-boxes {
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        max-width: 640px;
    }

    .feature-box {
        background: var(--card-bg-strong);
        border: 1px solid rgba(255, 103, 154, 0.22);
        border-radius: 20px;
        padding: 16px 10px;
        text-align: center;
        box-shadow: 0 10px 20px -12px rgba(160, 70, 100, 0.2);
        backdrop-filter: blur(8px);
        transition: 0.25s ease;
    }

    .feature-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 24px -12px rgba(160, 70, 100, 0.3);
    }

    .feature-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 6px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 20px;
        background: rgba(255, 103, 154, 0.14);
        border: 1px solid rgba(255, 103, 154, 0.24);
        color: var(--primary-deep);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.45);
    }

    .feature-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-soft);
    }

    .hero-actions {
        margin-top: 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 50px;
        padding: 0 22px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 15px;
        transition: 0.22s ease;
        cursor: pointer;
        border: none;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .btn-primary {
        color: #fff;
        background: #ff679a;
        box-shadow: 0 14px 28px -10px rgba(255, 103, 154, 0.48);
    }

    .btn-primary:hover {
        background: #f55a8e;
        box-shadow: 0 16px 30px -8px rgba(255, 103, 154, 0.54);
    }

    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 450px;
    }

    .hero-logo-only {
        width: min(100%, 600px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .hero-logo-only img {
        width: 100%;
        max-width: 550px;
        height: auto;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 18px 30px rgba(255, 103, 154, 0.18));
    }

    .reveal {
        opacity: 0;
        transform: translateY(26px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 992px) {
        .hero-grid {
            grid-template-columns: 1fr;
            gap: 20px;
            text-align: center;
            padding: 10px 0 20px;
        }

        .hero h1, .hero p {
            margin-left: auto;
            margin-right: auto;
        }

        .hero h1 {
            font-size: clamp(38px, 6vw, 58px);
            min-height: 120px;
        }

        .hero p {
            font-size: 16px;
            margin-top: 8px;
        }

        .feature-boxes {
            margin-left: auto;
            margin-right: auto;
            max-width: 600px;
            margin-top: 14px;
        }

        .hero-actions {
            justify-content: center;
            margin-top: 14px;
        }

        .hero-visual {
            min-height: auto;
            order: -1;
        }

        .hero-logo-only img {
            max-width: 450px;
        }
    }

    @media (max-width: 768px) {
        .hero {
            min-height: auto;
            padding: 5px 0 15px;
        }

        .hero-grid {
            gap: 12px;
            padding: 10px 0;
        }

        .hero h1 {
            font-size: clamp(32px, 7vw, 44px);
            min-height: 90px;
        }

        .hero h1 .line1 {
            margin-bottom: -3px;
        }

        .hero h1 .line2 {
            margin-top: -3px;
        }

        .hero p {
            font-size: 14px;
            line-height: 1.5;
            margin-top: 6px;
            padding: 0 5px;
        }

        .feature-boxes {
            grid-template-columns: 1fr;
            gap: 8px;
            max-width: 260px;
            margin-top: 12px;
        }

        .feature-box {
            display: flex;
            align-items: center;
            text-align: left;
            padding: 10px 14px;
            gap: 10px;
            border-radius: 18px;
        }

        .feature-icon {
            margin: 0;
            width: 38px;
            height: 38px;
            font-size: 17px;
            flex-shrink: 0;
        }

        .feature-title {
            font-size: 13px;
        }

        .hero-actions {
            flex-direction: column;
            width: 100%;
            max-width: 260px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 12px;
        }

        .btn {
            width: 100%;
            min-height: 46px;
            font-size: 14px;
            padding: 0 16px;
        }

        .hero-visual {
            min-height: auto;
        }

        .hero-logo-only img {
            max-width: 280px;
        }
    }

    @media (min-width: 769px) and (max-width: 992px) {
        .feature-boxes {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .feature-box {
            padding: 14px 8px;
        }

        .hero-actions {
            justify-content: center;
        }

        .btn {
            flex: 0 1 auto;
        }

        .hero-logo-only img {
            max-width: 400px;
        }
    }
</style>
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
