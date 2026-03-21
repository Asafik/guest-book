@extends('layouts.partials.app')

@php
  $pageTitle = 'Formulir';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/visitor.css') }}">
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
