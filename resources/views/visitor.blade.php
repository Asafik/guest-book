@extends('layouts.partials.app')

@php
  $pageTitle = 'Formulir';
@endphp

@push('styles')
<style>
    .form-section {
        flex: 1;
        padding: 80px 0 100px;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .form-card {
        background: var(--card-bg-strong);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        padding: 48px 42px;
        max-width: 720px;
        margin: 0 auto;
        box-shadow: var(--shadow-lg);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 5;
    }

    .form-card h2 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card h2 i { color: var(--primary); }

    .form-card > p {
        color: var(--text-muted);
        margin-bottom: 32px;
        font-size: 16px;
    }

    .form-group { margin-bottom: 24px; }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-soft);
        font-size: 15px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 16px 18px;
        border: 1px solid rgba(255, 103, 154, 0.22);
        border-radius: 24px;
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(4px);
        transition: 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(255, 103, 154, 0.12);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .hidden-input {
        display: none;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px dashed rgba(255, 103, 154, 0.3);
    }

    .hidden-input.show { display: block; }

    .upload-group { margin-bottom: 24px; }

    .upload-label {
        display: block;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--text-soft);
        font-size: 15px;
    }

    .upload-label small {
        font-weight: 400;
        color: var(--text-muted);
        margin-left: 8px;
    }

    .upload-area {
        position: relative;
        border: 2px dashed rgba(255, 103, 154, 0.3);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(4px);
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
        min-height: 180px;
    }

    .upload-area:hover {
        border-color: var(--primary);
        background: rgba(255, 255, 255, 0.8);
    }

    .upload-area.dragover {
        border-color: var(--primary);
        background: rgba(255, 103, 154, 0.05);
        transform: scale(1.02);
    }

    .upload-content {
        padding: 30px 20px;
        text-align: center;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .upload-content i {
        font-size: 40px;
        color: var(--primary);
        margin-bottom: 12px;
        opacity: 0.7;
    }

    .upload-content .upload-text {
        font-weight: 500;
        color: var(--text-soft);
        margin-bottom: 4px;
    }

    .upload-content .upload-hint {
        font-size: 13px;
        color: var(--text-muted);
    }

    .upload-content .upload-hint i {
        font-size: 13px;
        margin: 0 2px;
    }

    .upload-preview {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.03);
        z-index: 5;
    }

    .upload-preview img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 22px;
    }

    .upload-preview .remove-photo {
        position: absolute;
        top: 8px; right: 8px;
        width: 32px; height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border: none;
        color: var(--primary-dark);
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        box-shadow: var(--shadow-sm);
        z-index: 20;
        pointer-events: auto;
    }

    .upload-preview .remove-photo:hover {
        background: white;
        color: var(--primary-deep);
        transform: scale(1.1);
    }

    .upload-area.preview-active .upload-content { opacity: 0; }

    .camera-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .camera-modal.show { display: flex; }

    .camera-container {
        background: #000;
        border-radius: 24px;
        overflow: hidden;
        max-width: 90%; max-height: 90%;
        position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    #cameraFeed {
        width: 100%;
        max-width: 800px;
        height: auto;
        display: block;
        transform: scaleX(-1);
    }

    .camera-controls {
        position: absolute;
        bottom: 30px; left: 0; right: 0;
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 20px;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
    }

    .camera-btn {
        width: 60px; height: 60px;
        border-radius: 50%;
        border: none;
        background: white;
        color: var(--primary);
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .camera-btn:hover { transform: scale(1.1); }

    .camera-btn.capture {
        background: var(--primary);
        color: white;
        width: 70px; height: 70px;
        font-size: 28px;
    }

    .camera-btn.close {
        background: rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(5px);
    }

    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal.show { display: flex; }

    .modal-content {
        background: var(--card-bg-strong);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        padding: 32px;
        max-width: 400px;
        width: 90%;
        box-shadow: var(--shadow-lg);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-content h3 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-dark);
        text-align: center;
    }

    .modal-content p {
        color: var(--text-muted);
        text-align: center;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .modal-options {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }

    .modal-option {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 24px 16px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-option:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .modal-option i { font-size: 36px; color: var(--primary); }
    .modal-option span { font-weight: 600; color: var(--text-soft); font-size: 16px; }
    .modal-option small { color: var(--text-muted); font-size: 12px; text-align: center; }

    .modal-close {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 34px;
        background: rgba(255, 103, 154, 0.1);
        color: var(--text-soft);
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: 0.2s;
    }

    .modal-close:hover {
        background: rgba(255, 103, 154, 0.2);
        color: var(--primary-dark);
    }

    .btn-submit {
        width: 100%;
        padding: 18px;
        border: none;
        border-radius: 34px;
        background: var(--primary);
        color: white;
        font-weight: 700;
        font-size: 18px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 14px 28px -12px rgba(255, 103, 154, 0.5);
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover {
        background: var(--primary-dark);
        transform: scale(1.02);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Loading Overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
    }

    .loading-overlay.show { display: flex; }

    .loading-box {
        background: var(--card-bg-strong);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        padding: 40px 48px;
        text-align: center;
        box-shadow: var(--shadow-lg);
        animation: modalSlideIn 0.3s ease;
    }

    .loading-spinner {
        width: 56px; height: 56px;
        border: 5px solid rgba(255, 103, 154, 0.2);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 16px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .loading-box p {
        font-weight: 600;
        color: var(--text-soft);
        font-size: 16px;
        margin: 0;
    }

    .loading-box small {
        color: var(--text-muted);
        font-size: 13px;
    }

    /* Success Popup */
    .success-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 999999;
        align-items: center;
        justify-content: center;
    }

    .success-overlay.show { display: flex; }

    .success-box {
        background: var(--card-bg-strong);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        padding: 48px 42px;
        max-width: 460px;
        width: 90%;
        text-align: center;
        box-shadow: var(--shadow-lg);
        animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .success-icon {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(255, 103, 154, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }

    .success-icon i {
        font-size: 40px;
        color: var(--primary);
    }

    .success-box h3 {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 16px;
    }

    .success-box p {
        color: var(--text-muted);
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 32px;
    }

    .success-box p span {
        color: var(--primary);
        font-weight: 600;
    }

    .btn-ok {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 34px;
        background: var(--primary);
        color: white;
        font-weight: 700;
        font-size: 17px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 14px 28px -12px rgba(255, 103, 154, 0.5);
    }

    .btn-ok:hover {
        background: var(--primary-dark);
        transform: scale(1.02);
    }

    .decor-icon {
        position: absolute;
        color: var(--primary);
        opacity: 0.15;
        z-index: 1;
        pointer-events: none;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    .icon-1 { top: 15%; left: 5%; font-size: 80px; animation-delay: 0s; }
    .icon-2 { bottom: 20%; right: 3%; font-size: 100px; animation-delay: 1s; opacity: 0.1; }
    .icon-3 { top: 25%; right: 8%; font-size: 60px; animation-delay: 2s; opacity: 0.12; }
    .icon-4 { bottom: 30%; left: 2%; font-size: 70px; animation-delay: 0.5s; opacity: 0.1; }
    .icon-5 { top: 60%; left: 10%; font-size: 50px; animation-delay: 1.5s; opacity: 0.1; }
    .icon-6 { top: 10%; right: 15%; font-size: 45px; animation-delay: 2.5s; opacity: 0.15; }
    .icon-7 { bottom: 15%; left: 15%; font-size: 65px; animation-delay: 0.8s; opacity: 0.1; transform: rotate(-10deg); }

    .icon-3, .icon-6 { animation: floatReverse 7s ease-in-out infinite; }

    @keyframes floatReverse {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(15px) rotate(-5deg); }
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

    @media (max-width: 768px) {
        .form-section { padding: 40px 0 60px; }
        .form-card { padding: 32px 22px; }
        .form-card h2 { font-size: 26px; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
        .upload-content { padding: 20px 15px; }
        .upload-content i { font-size: 32px; }
        .modal-options { flex-direction: column; gap: 12px; }
        .modal-option { padding: 20px; }
        .icon-2, .icon-4, .icon-7 { display: none; }
        .success-box { padding: 36px 24px; }
    }
</style>
@endpush

@section('content')
    @include('layouts.navbar')

    <main>
        <i class="fas fa-map-marked-alt decor-icon icon-1"></i>
        <i class="fas fa-building decor-icon icon-2"></i>
        <i class="fas fa-people-arrows decor-icon icon-3"></i>
        <i class="fas fa-calendar-check decor-icon icon-4"></i>
        <i class="fas fa-id-card decor-icon icon-5"></i>
        <i class="fas fa-handshake decor-icon icon-6"></i>
        <i class="fas fa-phone-alt decor-icon icon-7"></i>

        <section class="form-section">
            <div class="container">
                <div class="form-card reveal">
                    <h2><i class="fas fa-file-signature"></i> Formulir Kunjungan</h2>
                    <p>Isi data diri Anda untuk mendaftar sebagai tamu di Jember Command Center.</p>

                    <form id="visitorForm" method="POST" action="/formulir" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="full_name">Nama Lengkap</label>
                            <input type="text" id="full_name" name="full_name" placeholder="Contoh: Ahmad Subagio" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="institution">Instansi / Asal</label>
                                <input type="text" id="institution" name="institution" placeholder="Nama instansi/perusahaan">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Nomor HP</label>
                                <input type="tel" id="phone_number" name="phone_number"
                                    placeholder="08123456789"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="purpose">Keperluan Kunjungan</label>
                            <select id="purpose" name="purpose" required>
                                <option value="">-- Pilih --</option>
                                <option value="coordination">Koordinasi Dinas</option>
                                <option value="audience">Audiensi</option>
                                <option value="monitoring">Monitoring</option>
                                <option value="meeting">Rapat</option>
                                <option value="visit">Ketemu</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                        <div class="hidden-input" id="meetWithInput">
                            <div class="form-group">
                                <label for="meet_with">
                                    Ketemu dengan siapa?
                                    <span style="color: var(--text-muted); font-weight: 400;">(opsional)</span>
                                </label>
                                <input type="text" id="meet_with" name="meet_with" placeholder="Contoh: Bapak Camat, Ibu Sekretaris, dll">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Catatan (opsional)</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Tulis keterangan tambahan..."></textarea>
                        </div>

                        <div class="upload-group">
                            <div class="upload-label">
                                <i class="fas fa-camera" style="color: var(--primary); margin-right: 6px;"></i>
                                Upload Foto <small>(opsional, maks. 5MB)</small>
                            </div>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="upload-text">Klik untuk upload foto</div>
                                    <div class="upload-hint">
                                        <i class="fas fa-image"></i> JPG, PNG • Maks 5MB
                                    </div>
                                </div>
                                <div class="upload-preview" id="uploadPreview">
                                    <img src="" alt="Preview">
                                    <button type="button" class="remove-photo" id="removePhoto">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="photoData" name="photo_data">

                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Pilihan Upload -->
    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <h3>Pilih Sumber Foto</h3>
            <p>Pilih cara mengambil atau memilih foto</p>
            <div class="modal-options">
                <div class="modal-option" id="chooseFileOption">
                    <i class="fas fa-folder-open"></i>
                    <span>Pilih File</span>
                    <small>Dari galeri atau file manager</small>
                </div>
                <div class="modal-option" id="cameraOption">
                    <i class="fas fa-camera"></i>
                    <span>Buka Kamera</span>
                    <small>Ambil foto langsung</small>
                </div>
            </div>
            <button class="modal-close" id="closeModal">Tutup</button>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="camera-modal" id="cameraModal">
        <div class="camera-container">
            <video id="cameraFeed" autoplay playsinline></video>
            <canvas id="cameraCanvas" style="display: none;"></canvas>
            <div class="camera-controls">
                <button class="camera-btn close" id="closeCamera">
                    <i class="fas fa-times"></i>
                </button>
                <button class="camera-btn capture" id="capturePhoto">
                    <i class="fas fa-camera"></i>
                </button>
                <button class="camera-btn" id="switchCamera" style="display: none;">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Input file galeri -->
    <input type="file" id="fileInput" name="photo" accept="image/*" style="display: none;">

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Menyimpan data...</p>
            <small>Mohon tunggu sebentar</small>
        </div>
    </div>

    <!-- Success Popup -->
    <div class="success-overlay" id="successOverlay">
        <div class="success-box">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Pendaftaran Berhasil!</h3>
            <p>
                <span>Terima kasih telah berkunjung di Jember Command Center.</span><br><br>
                Terima kasih telah mengisi formulir pendaftaran. Data kunjungan Anda telah kami catat dengan baik.
            </p>
            <button class="btn-ok" id="btnOk">
                <i class="fas fa-check"></i> OK
            </button>
        </div>
    </div>

    @include('layouts.footer')
@endsection

@push('scripts')
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.16 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    const purposeSelect  = document.getElementById('purpose');
    const meetWithInput  = document.getElementById('meetWithInput');

    purposeSelect.addEventListener('change', function () {
        this.value === 'visit'
            ? meetWithInput.classList.add('show')
            : meetWithInput.classList.remove('show');
    });

    const uploadArea       = document.getElementById('uploadArea');
    const previewContainer = document.getElementById('uploadPreview');
    const previewImage     = previewContainer.querySelector('img');
    const removeBtn        = document.getElementById('removePhoto');
    const fileInput        = document.getElementById('fileInput');
    const modal            = document.getElementById('uploadModal');
    const closeModalBtn    = document.getElementById('closeModal');
    const chooseFileOption = document.getElementById('chooseFileOption');
    const cameraOption     = document.getElementById('cameraOption');
    const cameraModal      = document.getElementById('cameraModal');
    const cameraFeed       = document.getElementById('cameraFeed');
    const cameraCanvas     = document.getElementById('cameraCanvas');
    const captureBtn       = document.getElementById('capturePhoto');
    const closeCameraBtn   = document.getElementById('closeCamera');
    const switchCameraBtn  = document.getElementById('switchCamera');
    const loadingOverlay   = document.getElementById('loadingOverlay');
    const successOverlay   = document.getElementById('successOverlay');
    const btnOk            = document.getElementById('btnOk');
    const btnSubmit        = document.getElementById('btnSubmit');

    let selectedFile  = null;
    let cameraStream  = null;
    let currentCamera = 'environment';
    let isCameraPhoto = false;

    // Tutup success popup saat klik OK
    btnOk.addEventListener('click', () => {
        successOverlay.classList.remove('show');
    });

    uploadArea.addEventListener('click', (e) => {
        if (e.target.closest('.remove-photo')) return;
        if (previewContainer.style.display === 'block') return;
        modal.classList.add('show');
    });

    closeModalBtn.addEventListener('click', () => modal.classList.remove('show'));
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('show'); });

    chooseFileOption.addEventListener('click', () => {
        modal.classList.remove('show');
        fileInput.click();
    });

    cameraOption.addEventListener('click', async () => {
        modal.classList.remove('show');
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: currentCamera, width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            cameraFeed.srcObject = cameraStream;
            cameraModal.classList.add('show');
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');
            if (videoDevices.length > 1) switchCameraBtn.style.display = 'flex';
        } catch (err) {
            alert('Tidak dapat mengakses kamera. Pastikan kamera terhubung dan izin diberikan.');
        }
    });

    switchCameraBtn.addEventListener('click', async () => {
        currentCamera = currentCamera === 'environment' ? 'user' : 'environment';
        if (cameraStream) cameraStream.getTracks().forEach(t => t.stop());
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: currentCamera, width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            cameraFeed.srcObject = cameraStream;
        } catch (err) {
            alert('Tidak dapat mengganti kamera.');
        }
    });

    captureBtn.addEventListener('click', () => {
        cameraCanvas.width  = cameraFeed.videoWidth;
        cameraCanvas.height = cameraFeed.videoHeight;
        const context = cameraCanvas.getContext('2d');
        if (currentCamera === 'user') {
            context.translate(cameraCanvas.width, 0);
            context.scale(-1, 1);
        }
        context.drawImage(cameraFeed, 0, 0, cameraCanvas.width, cameraCanvas.height);
        cameraCanvas.toBlob((blob) => {
            const file = new File([blob], `camera_${Date.now()}.jpg`, { type: 'image/jpeg' });
            isCameraPhoto = true;
            handleFileSelect(file);
            closeCameraBtn.click();
        }, 'image/jpeg', 0.85);
    });

    closeCameraBtn.addEventListener('click', () => {
        if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
        cameraModal.classList.remove('show');
        switchCameraBtn.style.display = 'none';
        currentCamera = 'environment';
    });

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) { isCameraPhoto = false; handleFileSelect(file); }
        fileInput.value = '';
    });

    function handleFileSelect(file) {
        if (!file) return false;
        if (!file.type.match('image.*')) { alert('Hanya file gambar yang diperbolehkan!'); return false; }
        if (file.size > 5 * 1024 * 1024) { alert('Ukuran file maksimal 5MB!'); return false; }
        selectedFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            uploadArea.classList.add('preview-active');
            uploadArea.style.borderColor = 'var(--primary)';
            if (isCameraPhoto) {
                document.getElementById('photoData').value = e.target.result;
            }
        };
        reader.readAsDataURL(file);
        return true;
    }

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => {
        uploadArea.addEventListener(ev, (e) => { e.preventDefault(); e.stopPropagation(); });
    });
    ['dragenter', 'dragover'].forEach(ev => {
        uploadArea.addEventListener(ev, () => {
            if (previewContainer.style.display !== 'block') uploadArea.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach(ev => {
        uploadArea.addEventListener(ev, () => uploadArea.classList.remove('dragover'));
    });
    uploadArea.addEventListener('drop', (e) => {
        if (previewContainer.style.display === 'block') return;
        const file = e.dataTransfer.files[0];
        isCameraPhoto = false;
        handleFileSelect(file);
    });

    removeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        selectedFile = null;
        isCameraPhoto = false;
        previewContainer.style.display = 'none';
        previewImage.src = '';
        uploadArea.classList.remove('preview-active');
        uploadArea.style.borderColor = 'rgba(255, 103, 154, 0.3)';
        document.getElementById('photoData').value = '';
        fileInput.value = '';
    });

    document.getElementById('visitorForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        loadingOverlay.classList.add('show');
        btnSubmit.disabled = true;

        const formData = new FormData(this);

        if (isCameraPhoto && selectedFile) {
            formData.delete('photo');
            formData.append('photo', selectedFile, selectedFile.name);
        }

        try {
            const response = await fetch('/formulir', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Reset form
                this.reset();
                selectedFile  = null;
                isCameraPhoto = false;
                previewContainer.style.display = 'none';
                previewImage.src = '';
                uploadArea.classList.remove('preview-active');
                uploadArea.style.borderColor = 'rgba(255, 103, 154, 0.3)';
                meetWithInput.classList.remove('show');
                document.getElementById('photoData').value = '';

                // Tampilkan success popup
                successOverlay.classList.add('show');
            } else {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }

        } catch (err) {
            alert('Gagal mengirim data. Periksa koneksi internet Anda.');
        } finally {
            loadingOverlay.classList.remove('show');
            btnSubmit.disabled = false;
        }
    });

    window.addEventListener('beforeunload', () => {
        if (cameraStream) cameraStream.getTracks().forEach(t => t.stop());
    });
</script>
@endpush
