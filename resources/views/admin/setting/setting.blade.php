@extends('layouts.partials.app')

@php
  $pageTitle = 'Pengaturan';
@endphp

@section('content')
<div class="admin">
  @include('admin.layouts.sidebar')

  <div class="main">
    @include('admin.layouts.navbar')

    <div class="content">

      <div class="setting-card">
        <div class="setting-header">
          <div class="setting-title">
            <i class="fas fa-sliders-h"></i>
            <h4>Pengaturan</h4>
          </div>
        </div>

        <div class="setting-body">

          <div class="section-label">
            <i class="fas fa-shield-alt"></i>
            <span>Informasi Login</span>
          </div>

          <div class="login-info-grid">
            <div class="login-info-item">
              <div class="login-info-icon"><i class="fas fa-desktop"></i></div>
              <div class="login-info-content">
                <div class="login-info-label">Sistem Operasi</div>
                <div class="login-info-value" id="osInfo">Mendeteksi...</div>
              </div>
            </div>
            <div class="login-info-item">
              <div class="login-info-icon"><i class="fas fa-globe"></i></div>
              <div class="login-info-content">
                <div class="login-info-label">Browser</div>
                <div class="login-info-value" id="browserInfo">Mendeteksi...</div>
              </div>
            </div>
            <div class="login-info-item">
              <div class="login-info-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div class="login-info-content">
                <div class="login-info-label">IP Address</div>
                <div class="login-info-value" id="ipInfo">Mendeteksi...</div>
              </div>
            </div>
            <div class="login-info-item">
              <div class="login-info-icon"><i class="fas fa-clock"></i></div>
              <div class="login-info-content">
                <div class="login-info-label">Waktu Sekarang</div>
                <div class="login-info-value" id="timeInfo">Mendeteksi...</div>
              </div>
            </div>
          </div>

          <div class="divider">
            <span>Informasi Instansi</span>
          </div>

          <div class="form-row-2">
            <div class="form-group">
              <label>Nama Aplikasi</label>
              <input type="text" id="appName"
                value="{{ $setting ? $setting->app_name : '' }}"
                placeholder="Nama aplikasi">
            </div>
            <div class="form-group">
              <label>Nama Instansi</label>
              <input type="text" id="institutionName"
                value="{{ $setting ? $setting->institution_name : '' }}"
                placeholder="Nama instansi">
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-group">
              <label>Singkatan Instansi</label>
              <input type="text" id="institutionShort"
                value="{{ $setting ? $setting->institution_short : '' }}"
                placeholder="Contoh: JCC">
            </div>
            <div class="form-group">
              <label>Tahun</label>
              <input type="text" id="year"
                value="{{ $setting ? $setting->year : '' }}"
                placeholder="Contoh: 2026">
            </div>
          </div>

          <div class="form-group">
            <label>Alamat Instansi</label>
            <textarea rows="3" id="address"
              placeholder="Alamat lengkap instansi">{{ $setting ? $setting->address : '' }}</textarea>
          </div>

          <div class="form-group">
            <label>Deskripsi Aplikasi</label>
            <textarea rows="3" id="description"
              placeholder="Deskripsi singkat aplikasi">{{ $setting ? $setting->description : '' }}</textarea>
          </div>

          <div class="divider">
            <span>Path & QR Code</span>
          </div>

          <div class="url-barcode-wrapper">
            <div class="form-group" style="margin-bottom:0">
              <label>Path QR Code</label>
              <div class="url-input-wrap">
                <span class="url-prefix-icon"><i class="fas fa-link"></i></span>
                <input type="text" id="qrPath"
                  value="{{ $setting ? $setting->qr_path : '' }}"
                  placeholder="/formulir"
                  oninput="generateBarcodeFromPath(this.value)">
                <button type="button" class="url-clear-btn" onclick="clearPath()" title="Hapus Path">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              <p class="url-hint">
                <i class="fas fa-info-circle"></i>
                Isi dengan path seperti <strong>/formulir</strong> atau <strong>/beranda</strong>. Domain, IP, dan protocol akan mengikuti aplikasi yang sedang aktif.
              </p>
            </div>

            <div class="barcode-card" id="barcodeCard">
              <div class="barcode-empty" id="barcodeEmpty">
                <div class="barcode-empty-icon">
                  <i class="fas fa-qrcode"></i>
                </div>
                <p>Masukkan path untuk<br>menampilkan QR Code</p>
              </div>

              <div class="barcode-result" id="barcodeResult" style="display:none">
                <div class="barcode-qr-wrap">
                  <div class="qr-box">
                    <div id="qrCanvas"></div>
                  </div>
                  <div class="barcode-scan-corner tl"></div>
                  <div class="barcode-scan-corner tr"></div>
                  <div class="barcode-scan-corner bl"></div>
                  <div class="barcode-scan-corner br"></div>
                </div>
                <div class="barcode-url-label" id="barcodeUrlLabel"></div>
                <button type="button" class="barcode-download-btn" onclick="downloadBarcode()">
                  <i class="fas fa-download"></i> Unduh QR Code
                </button>
              </div>
            </div>
          </div>

          <div class="divider">
            <span>Logo & Favicon</span>
          </div>

          <div class="form-row-2">
            <div class="form-group">
              <label>Logo Aplikasi</label>
              <div class="upload-setting" onclick="document.getElementById('logoInput').click()">
                <div class="upload-setting-preview">
                  <img src="{{ $setting && $setting->logo ? asset('storage/'.$setting->logo) : '' }}"
                    id="logoImg"
                    style="{{ $setting && $setting->logo ? 'display:block' : 'display:none' }}">
                  <div class="upload-setting-placeholder" id="logoPlaceholder"
                    style="{{ $setting && $setting->logo ? 'display:none' : 'display:flex' }}">
                    <i class="fas fa-image"></i>
                    <span>Klik untuk upload logo</span>
                    <small>PNG, JPG • Maks 2MB</small>
                  </div>
                </div>
                <input type="file" id="logoInput" accept="image/*" style="display:none"
                  onchange="previewImage(this, 'logoImg', 'logoPlaceholder')">
              </div>
            </div>

            <div class="form-group">
              <label>Favicon</label>
              <div class="upload-setting" onclick="document.getElementById('faviconInput').click()">
                <div class="upload-setting-preview">
                  <img src="{{ $setting && $setting->favicon ? asset('storage/'.$setting->favicon) : '' }}"
                    id="faviconImg"
                    style="{{ $setting && $setting->favicon ? 'display:block' : 'display:none' }}">
                  <div class="upload-setting-placeholder" id="faviconPlaceholder"
                    style="{{ $setting && $setting->favicon ? 'display:none' : 'display:flex' }}">
                    <i class="fas fa-star"></i>
                    <span>Klik untuk upload favicon</span>
                    <small>PNG, ICO • Maks 1MB</small>
                  </div>
                </div>
                <input type="file" id="faviconInput" accept="image/*,.ico" style="display:none"
                  onchange="previewImage(this, 'faviconImg', 'faviconPlaceholder')">
              </div>
            </div>
          </div>

          <div class="setting-footer">
            <button class="btn-save" onclick="openConfirm()">
              <i class="fas fa-save"></i> Simpan Perubahan
            </button>
          </div>

        </div>
      </div>

    </div>

    @include('admin.layouts.footer')
  </div>
</div>

<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-modal">
    <div class="confirm-icon">
      <i class="fas fa-cog"></i>
    </div>
    <h4>Simpan Perubahan?</h4>
    <p>Perubahan yang akan disimpan:</p>
    <div class="confirm-changes" id="confirmChanges"></div>
    <div class="confirm-btns">
      <button class="btn-cancel" onclick="closeConfirm()">Batal</button>
      <button class="btn-confirm-save" id="btnConfirmSave" onclick="doSave()">
        <i class="fas fa-save"></i> Ya, Simpan
      </button>
    </div>
  </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-box">
    <div class="loading-spinner"></div>
    <p>Memperbarui pengaturan...</p>
    <small>Mohon tunggu sebentar</small>
  </div>
</div>

<div class="success-overlay" id="successOverlay">
  <div class="success-box">
    <div class="success-icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <h3>Pengaturan Berhasil Diperbarui!</h3>
    <p>Perubahan pengaturan telah berhasil disimpan.</p>
    <button class="btn-ok" id="btnOk">
      <i class="fas fa-check"></i> OK
    </button>
  </div>
</div>

<div class="overlay" id="overlay"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/css/setting.css') }}">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
  function getOS() {
    const ua = navigator.userAgent;
    if (ua.includes('Windows NT 10.0')) return 'Windows 10/11';
    if (ua.includes('Windows NT 6.1')) return 'Windows 7';
    if (ua.includes('Windows')) return 'Windows';
    if (ua.includes('Mac OS X')) return 'macOS';
    if (ua.includes('Android')) return 'Android';
    if (ua.includes('iPhone') || ua.includes('iPad')) return 'iOS';
    if (ua.includes('Linux')) return 'Linux';
    return 'Tidak Diketahui';
  }

  function getBrowser() {
    const ua = navigator.userAgent;
    if (ua.includes('Edg')) return 'Microsoft Edge';
    if (ua.includes('Chrome')) return 'Google Chrome';
    if (ua.includes('Firefox')) return 'Mozilla Firefox';
    if (ua.includes('Safari') && !ua.includes('Chrome')) return 'Safari';
    if (ua.includes('Opera') || ua.includes('OPR')) return 'Opera';
    return 'Tidak Diketahui';
  }

  function updateTime() {
    const now = new Date();
    const opts = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    document.getElementById('timeInfo').textContent = now.toLocaleDateString('id-ID', opts);
  }

  fetch('https://api.ipify.org?format=json')
    .then(r => r.json())
    .then(d => { document.getElementById('ipInfo').textContent = d.ip; })
    .catch(() => { document.getElementById('ipInfo').textContent = 'Tidak tersedia'; });

  document.getElementById('osInfo').textContent = getOS();
  document.getElementById('browserInfo').textContent = getBrowser();
  updateTime();
  setInterval(updateTime, 60000);

  function previewImage(input, imgId, placeholderId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      const img = document.getElementById(imgId);
      const placeholder = document.getElementById(placeholderId);
      img.src = e.target.result;
      img.style.display = 'block';
      placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
  }

  let barcodeDebounce = null;

  function normalizePath(path) {
    const clean = (path || '').trim();
    if (!clean) return '';
    return clean.startsWith('/') ? clean : '/' + clean;
  }

  function buildFullUrlFromPath(path) {
    const normalizedPath = normalizePath(path);
    if (!normalizedPath) return '';
    return window.location.origin + normalizedPath;
  }

  function isValidPath(path) {
    const clean = (path || '').trim();
    if (!clean) return false;
    return !clean.includes(' ');
  }

  function setBarcodeEmptyState() {
    const empty = document.getElementById('barcodeEmpty');
    const result = document.getElementById('barcodeResult');
    const card = document.getElementById('barcodeCard');
    const wrapper = document.getElementById('qrCanvas');
    const label = document.getElementById('barcodeUrlLabel');

    empty.style.display = 'flex';
    result.style.display = 'none';
    card.classList.remove('has-barcode');
    wrapper.innerHTML = '';
    label.textContent = '';
  }

  function createQrCanvasData(url, size = 800) {
    const temp = document.createElement('div');
    temp.style.position = 'fixed';
    temp.style.left = '-99999px';
    temp.style.top = '-99999px';
    document.body.appendChild(temp);

    new QRCode(temp, {
      text: url,
      width: size,
      height: size,
      colorDark: '#000000',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.M
    });

    const canvas = temp.querySelector('canvas');
    const img = temp.querySelector('img');
    let dataUrl = null;

    if (canvas) {
      dataUrl = canvas.toDataURL('image/png');
    } else if (img) {
      dataUrl = img.src;
    }

    document.body.removeChild(temp);
    return dataUrl;
  }

  function renderQrToPreview(url) {
    const wrapper = document.getElementById('qrCanvas');
    wrapper.innerHTML = '';

    new QRCode(wrapper, {
      text: url,
      width: 200,
      height: 200,
      colorDark: '#000000',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.M
    });
  }

  function generateBarcodeFromPath(path) {
    clearTimeout(barcodeDebounce);

    barcodeDebounce = setTimeout(() => {
      const empty = document.getElementById('barcodeEmpty');
      const result = document.getElementById('barcodeResult');
      const card = document.getElementById('barcodeCard');
      const label = document.getElementById('barcodeUrlLabel');
      const cleanPath = (path || '').trim();

      if (!cleanPath) {
        setBarcodeEmptyState();
        return;
      }

      empty.style.display = 'none';
      result.style.display = 'flex';
      card.classList.add('has-barcode');

      if (!isValidPath(cleanPath)) {
        label.textContent = cleanPath;
        document.getElementById('qrCanvas').innerHTML = `
          <div style="
            width:190px;
            height:190px;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            font-size:12px;
            color:#999;
            line-height:1.6;
            padding:16px;
            background:#fff;
            border-radius:12px;
            box-sizing:border-box;
          ">
            Path tidak valid.<br>Contoh: /formulir
          </div>
        `;
        return;
      }

      const fullUrl = buildFullUrlFromPath(cleanPath);
      label.textContent = fullUrl;

      try {
        renderQrToPreview(fullUrl);
      } catch (e) {
        document.getElementById('qrCanvas').innerHTML = `
          <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=190x190&margin=10&data=${encodeURIComponent(fullUrl)}"
            width="190"
            height="190"
            alt="QR Code">
        `;
      }
    }, 250);
  }

  function clearPath() {
    document.getElementById('qrPath').value = '';
    setBarcodeEmptyState();
  }

  function downloadBarcode() {
    const path = document.getElementById('qrPath').value.trim();

    if (!path) {
      alert('Path QR Code masih kosong.');
      return;
    }

    if (!isValidPath(path)) {
      alert('Path tidak valid. Contoh: /formulir');
      return;
    }

    const fullUrl = buildFullUrlFromPath(path);

    try {
      const dataUrl = createQrCanvasData(fullUrl, 1000);
      if (!dataUrl) {
        alert('QR Code gagal dibuat.');
        return;
      }

      const link = document.createElement('a');
      link.download = 'qrcode-instansi.png';
      link.href = dataUrl;
      link.click();
    } catch (e) {
      const fallbackUrl = `https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&margin=40&data=${encodeURIComponent(fullUrl)}`;
      window.open(fallbackUrl, '_blank');
    }
  }

  window.addEventListener('DOMContentLoaded', () => {
    const existingPath = document.getElementById('qrPath').value.trim();
    if (existingPath) {
      generateBarcodeFromPath(existingPath);
    } else {
      setBarcodeEmptyState();
    }
  });

  const fieldLabels = {
    app_name: 'Nama Aplikasi',
    institution_name: 'Nama Instansi',
    institution_short: 'Singkatan Instansi',
    year: 'Tahun',
    address: 'Alamat Instansi',
    description: 'Deskripsi Aplikasi',
    qr_path: 'Path QR Code',
    logo: 'Logo Aplikasi',
    favicon: 'Favicon',
  };

  function getFormValues() {
    const path = document.getElementById('qrPath').value.trim();

    return {
      app_name: document.getElementById('appName').value.trim(),
      institution_name: document.getElementById('institutionName').value.trim(),
      institution_short: document.getElementById('institutionShort').value.trim(),
      year: document.getElementById('year').value.trim(),
      address: document.getElementById('address').value.trim(),
      description: document.getElementById('description').value.trim(),
      qr_path: path,
      logo: document.getElementById('logoInput').files[0]
        ? document.getElementById('logoInput').files[0].name
        : '(tidak diubah)',
      favicon: document.getElementById('faviconInput').files[0]
        ? document.getElementById('faviconInput').files[0].name
        : '(tidak diubah)',
    };
  }

  function openConfirm() {
    const values = getFormValues();

    if (!values.app_name || !values.institution_name || !values.institution_short || !values.year) {
      alert('Nama aplikasi, instansi, singkatan, dan tahun wajib diisi.');
      return;
    }

    if (values.qr_path && !isValidPath(values.qr_path)) {
      alert('Path QR Code tidak valid. Contoh: /formulir');
      return;
    }

    const changes = document.getElementById('confirmChanges');
    changes.innerHTML = Object.entries(values).map(([key, val]) => `
      <div class="change-item">
        <div class="change-label">${fieldLabels[key]}</div>
        <div class="change-value">${val || '-'}</div>
      </div>
    `).join('');

    document.getElementById('confirmOverlay').classList.add('show');
  }

  function closeConfirm() {
    document.getElementById('confirmOverlay').classList.remove('show');
  }

  document.getElementById('confirmOverlay').addEventListener('click', function (e) {
    if (e.target === this) closeConfirm();
  });

  function doSave() {
    const btnSave = document.getElementById('btnConfirmSave');
    const loading = document.getElementById('loadingOverlay');

    loading.classList.add('show');
    btnSave.disabled = true;
    closeConfirm();

    const qrPath = normalizePath(document.getElementById('qrPath').value.trim());

    const formData = new FormData();
    formData.append('app_name', document.getElementById('appName').value.trim());
    formData.append('institution_name', document.getElementById('institutionName').value.trim());
    formData.append('institution_short', document.getElementById('institutionShort').value.trim());
    formData.append('year', document.getElementById('year').value.trim());
    formData.append('address', document.getElementById('address').value.trim());
    formData.append('description', document.getElementById('description').value.trim());
    formData.append('qr_path', qrPath);
    formData.append('_method', 'PUT');

    const logoFile = document.getElementById('logoInput').files[0];
    const faviconFile = document.getElementById('faviconInput').files[0];

    if (logoFile) formData.append('logo', logoFile);
    if (faviconFile) formData.append('favicon', faviconFile);

    fetch('/settings', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
      },
      body: formData
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));
      if (!res.ok) throw new Error(data.message || 'Gagal menyimpan.');
      return data;
    })
    .then(data => {
      loading.classList.remove('show');
      btnSave.disabled = false;

      if (data.success) {
        document.getElementById('successOverlay').classList.add('show');
        document.getElementById('btnOk').onclick = () => {
          document.getElementById('successOverlay').classList.remove('show');
          window.location.reload();
        };
      } else {
        alert('Gagal menyimpan pengaturan.');
      }
    })
    .catch(err => {
      loading.classList.remove('show');
      btnSave.disabled = false;
      alert(err.message || 'Terjadi kesalahan.');
    });
  }
</script>
@endpush
