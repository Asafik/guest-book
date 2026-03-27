@extends('layouts.partials.app')

@php
    $pageTitle = 'Formulir';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/visitor.css') }}">

<style>
    .autofilled {
        background: rgba(34, 197, 94, 0.08);
        border-color: rgba(34, 197, 94, 0.35) !important;
    }

    .ocr-status {
        margin-top: 10px;
        display: none;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.5;
    }

    .ocr-status.info {
        background: rgba(59, 130, 246, 0.12);
        color: #1d4ed8;
        border: 1px solid rgba(59, 130, 246, 0.25);
    }

    .ocr-status.success {
        background: rgba(34, 197, 94, 0.12);
        color: #166534;
        border: 1px solid rgba(34, 197, 94, 0.25);
    }

    .ocr-status.error {
        background: rgba(239, 68, 68, 0.12);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.25);
    }

    .upload-preview {
        position: relative;
        display: none;
        margin-top: 12px;
        border-radius: 14px;
        overflow: hidden;
        border: 2px dashed rgba(255, 255, 255, 0.35);
        background: #0f172a;
    }

    .upload-preview img {
        width: 100%;
        border-radius: 14px;
        display: block;
        object-fit: cover;
        max-height: 280px;
    }

    .preview-ktp-frame {
        position: absolute;
        inset: 12px;
        border: 2px solid rgba(255,255,255,0.92);
        border-radius: 14px;
        pointer-events: none;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.10);
    }

    .preview-ktp-label {
        position: absolute;
        left: 16px;
        bottom: 16px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(0,0,0,0.65);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.2px;
        pointer-events: none;
    }

    .remove-photo {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.75);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 4;
    }

    .camera-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.88);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .camera-modal.show {
        display: flex;
    }

    .camera-container {
        position: relative;
        width: 100%;
        height: 100dvh;
        background: #000;
        overflow: hidden;
    }

    #cameraFeed {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: #000;
        transform: scaleX(1);
    }

    .camera-feed-user {
        transform: scaleX(-1);
    }

    .camera-top-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 3;
        padding: 18px 16px;
        background: linear-gradient(to bottom, rgba(0,0,0,0.55), rgba(0,0,0,0));
        color: #fff;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
    }

    .camera-helper-status {
        position: absolute;
        left: 16px;
        right: 16px;
        top: 78px;
        z-index: 4;
        text-align: center;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.4;
        backdrop-filter: blur(8px);
        background: rgba(15, 23, 42, 0.55);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.18);
    }

    .camera-helper-status.ok {
        background: rgba(34, 197, 94, 0.18);
        color: #dcfce7;
        border-color: rgba(34, 197, 94, 0.45);
    }

    .camera-helper-status.warn {
        background: rgba(245, 158, 11, 0.18);
        color: #fef3c7;
        border-color: rgba(245, 158, 11, 0.45);
    }

    .camera-helper-status.error {
        background: rgba(239, 68, 68, 0.18);
        color: #fee2e2;
        border-color: rgba(239, 68, 68, 0.45);
    }

    .camera-guide {
        position: absolute;
        inset: 0;
        z-index: 2;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .camera-guide-box {
        position: relative;
        width: min(92vw, 460px);
        aspect-ratio: 1.58 / 1;
        border: 3px solid rgba(255,255,255,0.96);
        border-radius: 18px;
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.38);
    }

    .camera-guide-box.ready {
        border-color: #22c55e;
    }

    .camera-guide-box.warn {
        border-color: #f59e0b;
    }

    .camera-guide-box.error {
        border-color: #ef4444;
    }

    .camera-guide-box::before,
    .camera-guide-box::after {
        content: "";
        position: absolute;
        inset: 10px;
        border: 1px dashed rgba(255,255,255,0.45);
        border-radius: 12px;
    }

    .camera-guide-label {
        position: absolute;
        top: -42px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.72);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 12px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .camera-guide-corner {
        position: absolute;
        width: 28px;
        height: 28px;
        border-color: #22c55e;
        border-style: solid;
    }

    .camera-guide-corner.tl {
        top: -2px;
        left: -2px;
        border-width: 4px 0 0 4px;
        border-top-left-radius: 14px;
    }

    .camera-guide-corner.tr {
        top: -2px;
        right: -2px;
        border-width: 4px 4px 0 0;
        border-top-right-radius: 14px;
    }

    .camera-guide-corner.bl {
        bottom: -2px;
        left: -2px;
        border-width: 0 0 4px 4px;
        border-bottom-left-radius: 14px;
    }

    .camera-guide-corner.br {
        bottom: -2px;
        right: -2px;
        border-width: 0 4px 4px 0;
        border-bottom-right-radius: 14px;
    }

    .camera-controls {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 22px;
        z-index: 3;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        padding: 0 16px;
    }

    .camera-btn {
        width: 58px;
        height: 58px;
        border: none;
        border-radius: 999px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        background: rgba(255,255,255,0.16);
        backdrop-filter: blur(8px);
    }

    .camera-btn.close {
        background: rgba(239, 68, 68, 0.9);
    }

    .camera-btn.capture {
        width: 78px;
        height: 78px;
        background: #fff;
        color: #111827;
        font-size: 24px;
    }

    .camera-btn.switch {
        background: rgba(255,255,255,0.16);
    }

    .capture-note {
        position: absolute;
        left: 16px;
        right: 16px;
        bottom: 120px;
        z-index: 3;
        text-align: center;
        color: #fff;
        font-size: 14px;
        line-height: 1.5;
        background: rgba(0,0,0,0.35);
        padding: 10px 14px;
        border-radius: 12px;
        backdrop-filter: blur(6px);
    }

    @media (min-width: 768px) {
        .camera-container {
            max-width: 480px;
            max-height: 90vh;
            height: 90vh;
            border-radius: 20px;
        }
    }
</style>
@endpush

@section('content')
    @include('layouts.navbar')

    <main>
        <section class="form-section">
            <div class="container">
                <div class="form-card reveal">
                    <h2><i class="fas fa-file-signature"></i> Formulir Kunjungan</h2>
                    <p>Isi data diri Anda untuk mendaftar sebagai tamu.</p>

                    <form id="visitorForm" method="POST" action="{{ url('/formulir') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="upload-group">
                            <div class="upload-label">
                                <i class="fas fa-id-card" style="color: var(--primary); margin-right: 6px;"></i>
                                Scan KTP <small>(opsional, isi nama & alamat otomatis)</small>
                            </div>

                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content" id="uploadContent">
                                    <i class="fas fa-camera"></i>
                                    <div class="upload-text">Klik untuk buka kamera</div>
                                    <div class="upload-hint">
                                        <i class="fas fa-mobile-alt"></i> Kamera web di halaman
                                    </div>
                                    <small style="display:block; margin-top:8px; color:var(--text-muted);">
                                        Kamera akan terbuka di halaman web, lalu scan KTP otomatis.
                                    </small>
                                </div>

                                <div class="upload-preview" id="uploadPreview">
                                    <img src="" alt="Preview KTP" id="previewImage">
                                    <div class="preview-ktp-frame"></div>
                                    <div class="preview-ktp-label">Preview Foto KTP</div>
                                    <button type="button" class="remove-photo" id="removePhoto">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="ocrStatus" class="ocr-status"></div>
                        </div>

                        <div class="form-group">
                            <label for="full_name">Nama Lengkap</label>
                            <input type="text" id="full_name" name="full_name" placeholder="Contoh: Ahmad Subagio" required>
                        </div>

                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea id="address" name="address" rows="3" placeholder="Alamat sesuai KTP"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="institution">Instansi / Asal</label>
                                <input type="text" id="institution" name="institution" placeholder="Nama instansi/perusahaan">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Nomor HP</label>
                                <input type="tel" id="phone_number" name="phone_number" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="purpose">Tujuan</label>
                            <select id="purpose" name="purpose" required>
                                <option value="">Pilih tujuan</option>
                                <option value="coordination">Koordinasi</option>
                                <option value="audience">Audiensi</option>
                                <option value="monitoring">Monitoring</option>
                                <option value="meeting">Meeting</option>
                                <option value="visit">Berkunjung</option>
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
                            <textarea id="notes" name="notes" rows="3" placeholder="Tulis keterangan tambahan."></textarea>
                        </div>

                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <div class="camera-modal" id="cameraModal">
        <div class="camera-container">
            <div class="camera-top-bar">
                Arahkan kamera ke KTP. Sistem akan membaca otomatis.
            </div>

            <div class="camera-helper-status warn" id="cameraHelperStatus">
                Arahkan KTP ke dalam kotak
            </div>

            <video id="cameraFeed" autoplay playsinline muted></video>
            <canvas id="cameraCanvas" style="display:none;"></canvas>

            <div class="camera-guide">
                <div class="camera-guide-box warn" id="cameraGuideBox">
                    <div class="camera-guide-label">Pas kan KTP di area kotak</div>
                    <span class="camera-guide-corner tl"></span>
                    <span class="camera-guide-corner tr"></span>
                    <span class="camera-guide-corner bl"></span>
                    <span class="camera-guide-corner br"></span>
                </div>
            </div>

            <div class="capture-note">
                Jika scan otomatis belum merespons, tekan tombol kamera.
            </div>

            <div class="camera-controls">
                <button class="camera-btn close" id="closeCamera" type="button" title="Tutup">
                    <i class="fas fa-times"></i>
                </button>

                <button class="camera-btn capture" id="capturePhoto" type="button" title="Ambil Foto">
                    <i class="fas fa-camera"></i>
                </button>

                <button class="camera-btn switch" id="switchCamera" type="button" title="Ganti Kamera">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p id="loadingText">Memproses...</p>
            <small id="loadingSubText">Mohon tunggu sebentar</small>
        </div>
    </div>

    <div class="success-overlay" id="successOverlay">
        <div class="success-box">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Pendaftaran Berhasil!</h3>
            <p>
                <span>Terima kasih telah berkunjung.</span><br><br>
                Data kunjungan Anda telah kami catat dengan baik.
            </p>
            <button class="btn-ok" id="btnOk" type="button">
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

    const purposeSelect = document.getElementById('purpose');
    const meetWithInput = document.getElementById('meetWithInput');

    const uploadArea = document.getElementById('uploadArea');
    const uploadContent = document.getElementById('uploadContent');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewImage = document.getElementById('previewImage');
    const removePhoto = document.getElementById('removePhoto');

    const cameraModal = document.getElementById('cameraModal');
    const cameraFeed = document.getElementById('cameraFeed');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const closeCamera = document.getElementById('closeCamera');
    const capturePhoto = document.getElementById('capturePhoto');
    const switchCamera = document.getElementById('switchCamera');
    const cameraHelperStatus = document.getElementById('cameraHelperStatus');
    const cameraGuideBox = document.getElementById('cameraGuideBox');

    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingText = document.getElementById('loadingText');
    const loadingSubText = document.getElementById('loadingSubText');

    const successOverlay = document.getElementById('successOverlay');
    const btnOk = document.getElementById('btnOk');
    const btnSubmit = document.getElementById('btnSubmit');

    const ocrStatus = document.getElementById('ocrStatus');
    const fullNameInput = document.getElementById('full_name');
    const addressInput = document.getElementById('address');
    const visitorForm = document.getElementById('visitorForm');

    let selectedFile = null;
    let cameraStream = null;
    let currentFacingMode = 'environment';

    let autoScanInterval = null;
    let isAutoScanning = false;
    let isOcrProcessing = false;
    let autoScanSuccess = false;

    purposeSelect.addEventListener('change', function () {
        if (this.value === 'visit') {
            meetWithInput.classList.add('show');
        } else {
            meetWithInput.classList.remove('show');
        }
    });

    btnOk.addEventListener('click', () => {
        successOverlay.classList.remove('show');
    });

    function showOcrStatus(type, message) {
        ocrStatus.className = 'ocr-status ' + type;
        ocrStatus.style.display = 'block';
        ocrStatus.textContent = message;
    }

    function hideOcrStatus() {
        ocrStatus.style.display = 'none';
        ocrStatus.className = 'ocr-status';
        ocrStatus.textContent = '';
    }

    function normalizeText(value) {
        return String(value || '')
            .replace(/\s+/g, ' ')
            .replace(/\s*\/\s*/g, '/')
            .replace(/\s*,\s*/g, ', ')
            .trim();
    }

    function buildFullAddress(data = {}) {
        const alamatUtama =
            data.address ||
            data.alamat ||
            '';

        const rtRw =
            data.rt_rw ||
            data.rtrw ||
            data.rtRw ||
            '';

        const kelDesa =
            data.village ||
            data.kel_desa ||
            data.keldesa ||
            data.kelurahan ||
            data.desa ||
            '';

        const kecamatan =
            data.subdistrict ||
            data.kecamatan ||
            '';

        const parts = [];

        if (alamatUtama) parts.push(normalizeText(alamatUtama));
        if (rtRw) parts.push(`RT/RW ${normalizeText(rtRw)}`);
        if (kelDesa) parts.push(normalizeText(kelDesa));
        if (kecamatan) parts.push(normalizeText(kecamatan));

        return parts.join(', ');
    }

    function getOcrPayload(result) {
        return result?.data || {};
    }

    function hasMinimumOcrData(result) {
        const data = getOcrPayload(result);
        const fullName = normalizeText(data.full_name || data.name || '');
        const fullAddress = buildFullAddress(data);

        return Boolean(result && result.success && fullName && fullAddress);
    }

    function markAutofilledBoth(fullName, address) {
        fullNameInput.value = normalizeText(fullName);
        addressInput.value = normalizeText(address);
        fullNameInput.classList.add('autofilled');
        addressInput.classList.add('autofilled');
    }

    function applyOcrToInputs(result) {
        const data = getOcrPayload(result);

        const fullName = normalizeText(
            data.full_name ||
            data.name ||
            ''
        );

        const fullAddress = buildFullAddress(data);

        if (!fullName || !fullAddress) {
            return false;
        }

        markAutofilledBoth(fullName, fullAddress);
        return true;
    }

    function clearAutofilledValues() {
        fullNameInput.classList.remove('autofilled');
        addressInput.classList.remove('autofilled');
    }

    function resetAutofilledState() {
        clearAutofilledValues();
    }

    function resetUploadPreview() {
        selectedFile = null;
        previewImage.src = '';
        uploadPreview.style.display = 'none';
        uploadContent.style.display = 'block';
        uploadArea.classList.remove('preview-active');
        hideOcrStatus();
        resetAutofilledState();
    }

    function updateCameraMirror() {
        if (currentFacingMode === 'user') {
            cameraFeed.classList.add('camera-feed-user');
        } else {
            cameraFeed.classList.remove('camera-feed-user');
        }
    }

    function setCameraHelper(type, message) {
        cameraHelperStatus.className = 'camera-helper-status ' + type;
        cameraHelperStatus.textContent = message;

        cameraGuideBox.classList.remove('ready', 'warn', 'error');
        if (type === 'ok') {
            cameraGuideBox.classList.add('ready');
        } else if (type === 'warn') {
            cameraGuideBox.classList.add('warn');
        } else {
            cameraGuideBox.classList.add('error');
        }
    }

    function stopAutoScan() {
        if (autoScanInterval) {
            clearInterval(autoScanInterval);
            autoScanInterval = null;
        }

        isAutoScanning = false;
        isOcrProcessing = false;
    }

    function stopCamera() {
        stopAutoScan();

        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }

        cameraFeed.srcObject = null;
        cameraModal.classList.remove('show');
    }

    async function openCamera() {
        try {
            stopCamera();

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Browser ini tidak mendukung akses kamera.');
                return;
            }

            autoScanSuccess = false;

            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { ideal: currentFacingMode },
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                },
                audio: false
            });

            cameraFeed.srcObject = cameraStream;
            updateCameraMirror();
            cameraModal.classList.add('show');
            setCameraHelper('warn', 'Arahkan KTP ke dalam kotak');

            try {
                await cameraFeed.play();
            } catch (playError) {
                console.warn('Video play warning:', playError);
            }

            startAutoScan();
        } catch (err) {
            console.error('Camera Error:', err);
            alert('Tidak dapat mengakses kamera: ' + err.message);
        }
    }

    async function switchCameraFacing() {
        currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
        await openCamera();
    }

    function captureFrameToFile() {
        const videoWidth = cameraFeed.videoWidth;
        const videoHeight = cameraFeed.videoHeight;

        if (!videoWidth || !videoHeight) {
            return Promise.resolve(null);
        }

        const guideRatio = 1.58;
        let cropWidth = videoWidth * 0.94;
        let cropHeight = cropWidth / guideRatio;

        if (cropHeight > videoHeight * 0.82) {
            cropHeight = videoHeight * 0.82;
            cropWidth = cropHeight * guideRatio;
        }

        const sx = (videoWidth - cropWidth) / 2;
        const sy = (videoHeight - cropHeight) / 2;

        cameraCanvas.width = cropWidth;
        cameraCanvas.height = cropHeight;

        const context = cameraCanvas.getContext('2d');
        context.clearRect(0, 0, cropWidth, cropHeight);

        if (currentFacingMode === 'user') {
            context.save();
            context.translate(cropWidth, 0);
            context.scale(-1, 1);
            context.drawImage(
                cameraFeed,
                sx, sy, cropWidth, cropHeight,
                0, 0, cropWidth, cropHeight
            );
            context.restore();
        } else {
            context.drawImage(
                cameraFeed,
                sx, sy, cropWidth, cropHeight,
                0, 0, cropWidth, cropHeight
            );
        }

        return new Promise((resolve) => {
            cameraCanvas.toBlob((blob) => {
                if (!blob) {
                    resolve(null);
                    return;
                }

                const file = new File([blob], `ktp_auto_${Date.now()}.jpg`, {
                    type: 'image/jpeg'
                });

                resolve(file);
            }, 'image/jpeg', 0.92);
        });
    }

    function analyzeCurrentFrameDistance() {
        const width = cameraCanvas.width;
        const height = cameraCanvas.height;

        if (!width || !height) {
            return { state: 'warn', message: 'Arahkan KTP ke dalam kotak' };
        }

        const smallCanvas = document.createElement('canvas');
        const smallWidth = 220;
        const smallHeight = Math.max(120, Math.round((height / width) * smallWidth));

        smallCanvas.width = smallWidth;
        smallCanvas.height = smallHeight;

        const smallCtx = smallCanvas.getContext('2d');
        smallCtx.drawImage(cameraCanvas, 0, 0, smallWidth, smallHeight);

        const imageData = smallCtx.getImageData(0, 0, smallWidth, smallHeight).data;
        let edgeSum = 0;
        let samples = 0;

        const gray = new Array(smallWidth * smallHeight);

        for (let y = 0; y < smallHeight; y++) {
            for (let x = 0; x < smallWidth; x++) {
                const idx = (y * smallWidth + x) * 4;
                gray[y * smallWidth + x] =
                    (imageData[idx] * 0.299) +
                    (imageData[idx + 1] * 0.587) +
                    (imageData[idx + 2] * 0.114);
            }
        }

        for (let y = 1; y < smallHeight - 1; y++) {
            for (let x = 1; x < smallWidth - 1; x++) {
                const c = gray[y * smallWidth + x];
                const r = gray[y * smallWidth + (x + 1)];
                const b = gray[(y + 1) * smallWidth + x];

                edgeSum += Math.abs(c - r) + Math.abs(c - b);
                samples++;
            }
        }

        const edgeScore = samples ? (edgeSum / samples) : 0;

        if (edgeScore < 18) {
            return { state: 'warn', message: 'Mohon dekatkan kamera' };
        }

        if (edgeScore > 42) {
            return { state: 'warn', message: 'Mohon agak jauhkan kamera' };
        }

        return { state: 'ok', message: 'Posisi sudah cukup pas, sedang membaca...' };
    }

    async function processKtpOcr(file, options = {}) {
        const { silent = false } = options;

        if (isOcrProcessing) return null;
        isOcrProcessing = true;

        if (!silent) {
            loadingOverlay.classList.add('show');
            loadingText.textContent = 'Membaca data KTP...';
            loadingSubText.textContent = 'Mengisi nama dan alamat otomatis';
            showOcrStatus('info', 'Sedang membaca nama dan alamat dari KTP...');
        }

        try {
            const ocrFormData = new FormData();
            ocrFormData.append('photo', file);

            const response = await fetch('{{ url('/formulir/ocr-ktp') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: ocrFormData
            });

            const contentType = response.headers.get('content-type') || '';
            let result = null;

            if (contentType.includes('application/json')) {
                result = await response.json();
            } else {
                const text = await response.text();
                console.error('OCR response bukan JSON:', text);
                throw new Error('Response OCR tidak valid');
            }

            if (!response.ok) {
                throw new Error(result.message || 'Terjadi kesalahan saat membaca KTP');
            }

            const hasFullData = hasMinimumOcrData(result);

            if (hasFullData) {
                if (!silent) {
                    applyOcrToInputs(result);
                    showOcrStatus(
                        'success',
                        result.message || 'Nama dan alamat berhasil diisi otomatis. Silakan periksa kembali.'
                    );
                }
                return result;
            }

            if (!silent) {
                showOcrStatus(
                    'info',
                    'Nama dan alamat harus terbaca lengkap. Silakan coba lagi.'
                );
            }

            if (result?.data?.raw_text) {
                console.log('OCR RAW TEXT:', result.data.raw_text);
            }

            if (result?.data) {
                console.log('OCR DATA:', result.data);
            }

            return result;
        } catch (error) {
            console.error('OCR Error:', error);

            if (!silent) {
                showOcrStatus(
                    'error',
                    error.message || 'Gagal membaca KTP. Silakan scan ulang atau isi manual.'
                );
            }

            return null;
        } finally {
            if (!silent) {
                loadingOverlay.classList.remove('show');
                loadingText.textContent = 'Memproses...';
                loadingSubText.textContent = 'Mohon tunggu sebentar';
            }

            isOcrProcessing = false;
        }
    }

    async function startAutoScan() {
        stopAutoScan();
        isAutoScanning = true;

        autoScanInterval = setInterval(async () => {
            if (!isAutoScanning || isOcrProcessing || autoScanSuccess) return;
            if (!cameraModal.classList.contains('show')) return;
            if (!cameraFeed.videoWidth || !cameraFeed.videoHeight) return;

            const file = await captureFrameToFile();
            if (!file) return;

            const frameStatus = analyzeCurrentFrameDistance();
            setCameraHelper(frameStatus.state, frameStatus.message);

            if (frameStatus.message === 'Mohon dekatkan kamera') return;
            if (frameStatus.message === 'Mohon agak jauhkan kamera') return;

            const result = await processKtpOcr(file, { silent: true });

            if (hasMinimumOcrData(result)) {
                autoScanSuccess = true;
                stopAutoScan();

                selectedFile = file;
                applyOcrToInputs(result);

                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    uploadContent.style.display = 'none';
                    uploadPreview.style.display = 'block';
                    uploadArea.classList.add('preview-active');

                    stopCamera();

                    showOcrStatus(
                        'success',
                        result.message || 'Nama dan alamat berhasil diisi otomatis. Silakan periksa kembali.'
                    );
                };
                reader.readAsDataURL(file);
            }
        }, 1800);
    }

    uploadArea.addEventListener('click', (e) => {
        if (e.target.closest('.remove-photo')) return;
        if (selectedFile) return;
        openCamera();
    });

    closeCamera.addEventListener('click', () => {
        stopCamera();
    });

    cameraModal.addEventListener('click', (e) => {
        if (e.target === cameraModal) {
            stopCamera();
        }
    });

    switchCamera.addEventListener('click', async () => {
        await switchCameraFacing();
    });

    capturePhoto.addEventListener('click', async () => {
        const file = await captureFrameToFile();

        if (!file) {
            alert('Kamera belum siap.');
            return;
        }

        selectedFile = file;
        resetAutofilledState();
        hideOcrStatus();

        const reader = new FileReader();
        reader.onload = async (e) => {
            previewImage.src = e.target.result;
            uploadContent.style.display = 'none';
            uploadPreview.style.display = 'block';
            uploadArea.classList.add('preview-active');

            stopCamera();

            const result = await processKtpOcr(file);

            if (hasMinimumOcrData(result)) {
                applyOcrToInputs(result);
            } else {
                fullNameInput.value = '';
                addressInput.value = '';
                clearAutofilledValues();
                showOcrStatus('info', 'Nama dan alamat harus terbaca lengkap. Silakan coba lagi.');
            }
        };
        reader.readAsDataURL(file);
    });

    removePhoto.addEventListener('click', (e) => {
        e.stopPropagation();
        resetUploadPreview();
        fullNameInput.value = '';
        addressInput.value = '';
    });

    visitorForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        loadingOverlay.classList.add('show');
        loadingText.textContent = 'Menyimpan data...';
        loadingSubText.textContent = 'Mohon tunggu sebentar';
        btnSubmit.disabled = true;

        const formData = new FormData(this);

        if (selectedFile) {
            formData.append('photo', selectedFile, selectedFile.name);
        }

        try {
            const response = await fetch('{{ url('/formulir') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            });

            const contentType = response.headers.get('content-type') || '';
            let result = null;

            if (contentType.includes('application/json')) {
                result = await response.json();
            } else {
                const text = await response.text();
                console.error('Submit response bukan JSON:', text);
                throw new Error('Response simpan tidak valid');
            }

            if (!response.ok) {
                throw new Error(result.message || 'Terjadi kesalahan saat menyimpan data');
            }

            if (result.success) {
                this.reset();
                resetUploadPreview();
                fullNameInput.value = '';
                addressInput.value = '';
                meetWithInput.classList.remove('show');
                successOverlay.classList.add('show');
            } else {
                alert(result.message || 'Terjadi kesalahan. Silakan coba lagi.');
            }
        } catch (err) {
            console.error('Submit Error:', err);
            alert(err.message || 'Gagal mengirim data. Periksa koneksi internet Anda.');
        } finally {
            loadingOverlay.classList.remove('show');
            loadingText.textContent = 'Memproses...';
            loadingSubText.textContent = 'Mohon tunggu sebentar';
            btnSubmit.disabled = false;
        }
    });

    fullNameInput.addEventListener('input', () => {
        fullNameInput.classList.remove('autofilled');
    });

    addressInput.addEventListener('input', () => {
        addressInput.classList.remove('autofilled');
    });

    window.addEventListener('beforeunload', () => {
        stopCamera();
    });
</script>
@endpush
