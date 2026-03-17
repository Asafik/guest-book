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
            <span>URL & QR Code</span>
          </div>

          <div class="url-barcode-wrapper">
            {{-- Input URL --}}
            <div class="form-group" style="margin-bottom:0">
              <label>URL QR Code</label>
              <div class="url-input-wrap">
                <span class="url-prefix-icon"><i class="fas fa-link"></i></span>
                <input type="url" id="qrUrl"
                  value="{{ $setting ? $setting->qr_url : '' }}"
                  placeholder="https://contoh.com"
                  oninput="generateBarcode(this.value)">
                <button type="button" class="url-clear-btn" onclick="clearUrl()" title="Hapus URL">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              <p class="url-hint">
                <i class="fas fa-info-circle"></i>
                URL ini akan digunakan sebagai konten QR Code instansi.
              </p>
            </div>

            {{-- Preview QR --}}
            <div class="barcode-card" id="barcodeCard">
              <div class="barcode-empty" id="barcodeEmpty">
                <div class="barcode-empty-icon">
                  <i class="fas fa-qrcode"></i>
                </div>
                <p>Masukkan URL untuk<br>menampilkan QR Code</p>
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
<style>
  .admin {
    display: flex;
    min-height: 100vh;
    position: relative;
    width: 100%;
  }

  .main {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    overflow: hidden; /* FIX: cegah main melebar keluar */
  }

  .content {
    padding: 24px;
    flex: 1;
    min-width: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    gap: 20px;
    box-sizing: border-box; /* FIX: padding tidak menambah lebar */
  }

  .setting-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
    min-width: 0; /* FIX: cegah card stretch */
    width: 100%;  /* FIX: pastikan tidak melebihi parent */
    box-sizing: border-box;
  }

  .setting-header {
    display: flex;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(255, 103, 154, 0.1);
  }

  .setting-title { display: flex; align-items: center; gap: 10px; }
  .setting-title i { color: var(--pink); font-size: 16px; }
  .setting-title h4 { font-size: 15px; font-weight: 700; color: var(--dark); margin: 0; }

  .setting-body {
    padding: 24px;
    min-width: 0; /* FIX */
    box-sizing: border-box;
  }

  .section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    font-size: 13px;
    font-weight: 700;
    color: var(--pink);
  }

  .section-label i { font-size: 13px; }

  .login-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 24px;
  }

  .login-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: var(--pink-soft);
    border-radius: 14px;
    border: 1px solid rgba(255, 103, 154, 0.1);
    min-width: 0; /* FIX */
  }

  .login-info-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255, 103, 154, 0.1);
    color: var(--pink);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
  }

  .login-info-content {
    min-width: 0; /* FIX: cegah teks overflow */
    overflow: hidden;
  }

  .login-info-label {
    font-size: 11px;
    color: var(--gray-light);
    font-weight: 500;
    margin-bottom: 3px;
  }

  .login-info-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 20px;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255, 103, 154, 0.15);
  }

  .divider span {
    font-size: 12px;
    font-weight: 700;
    color: var(--pink);
    white-space: nowrap;
  }

  .form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .form-group { margin-bottom: 16px; }

  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray);
    margin-bottom: 8px;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid rgba(255, 103, 154, 0.22);
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: var(--dark);
    background: white;
    outline: none;
    transition: 0.2s;
    box-sizing: border-box;
    resize: none;
  }

  .form-group input:focus,
  .form-group textarea:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
  }

  /* ===== URL & QR Code ===== */
  .url-barcode-wrapper {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 20px;
    width: 100%;
    box-sizing: border-box;
  }

  .url-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
  }

  .url-prefix-icon {
    position: absolute;
    left: 14px;
    color: var(--pink);
    font-size: 13px;
    pointer-events: none;
    z-index: 1;
  }

  .url-input-wrap input[type="url"] {
    padding-left: 38px !important;
    padding-right: 42px !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .url-clear-btn {
    position: absolute;
    right: 12px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 103, 154, 0.1);
    color: var(--pink);
    cursor: pointer;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
    z-index: 1;
  }

  .url-clear-btn:hover { background: var(--pink); color: white; }

  .url-hint {
    margin: 8px 0 0;
    font-size: 11.5px;
    color: var(--gray-light);
    display: flex;
    align-items: center;
    gap: 5px;
    line-height: 1.5;
  }

  .url-hint i { color: var(--pink); font-size: 11px; flex-shrink: 0; }

  /* Card QR: centered, max-width agar tidak terlalu lebar */
  .barcode-card {
    width: 100%;
    max-width: 320px;
    margin: 0 auto;
    background: white;
    border: 1.5px dashed rgba(255, 103, 154, 0.3);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px 20px;
    box-sizing: border-box;
    transition: 0.3s;
    overflow: hidden;
  }

  .barcode-card.has-barcode {
    border-style: solid;
    border-color: rgba(255, 103, 154, 0.2);
    background: #fff7fa;
  }

  .barcode-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-align: center;
    padding: 20px 0;
  }

  .barcode-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(255, 103, 154, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .barcode-empty-icon i { font-size: 26px; color: var(--pink); opacity: 0.4; }

  .barcode-empty p {
    font-size: 12px;
    color: var(--gray-light);
    line-height: 1.6;
    margin: 0;
    font-weight: 500;
  }

  .barcode-result {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    width: 100%;
    animation: fadeInUp 0.3s ease;
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .barcode-qr-wrap {
    position: relative;
    display: inline-block;
    padding: 8px;
  }

  .qr-box {
    background: #ffffff;
    padding: 10px;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(255, 103, 154, 0.08);
    line-height: 0; /* hilangkan gap bawah canvas/img */
  }

  /* QR size 200px — aman dalam card max-width 320px */
  #qrCanvas {
    width: 200px;
    height: 200px;
    display: block;
    overflow: hidden;
  }

  #qrCanvas canvas,
  #qrCanvas img {
    display: block !important;
    width: 200px !important;
    height: 200px !important;
    max-width: 200px !important;
    max-height: 200px !important;
    image-rendering: pixelated;
    border-radius: 0 !important;
    background: #ffffff;
  }

  .barcode-scan-corner {
    position: absolute;
    width: 18px;
    height: 18px;
    border-color: var(--pink);
    border-style: solid;
    border-radius: 3px;
    pointer-events: none;
  }

  .barcode-scan-corner.tl { top: 0; left: 0; border-width: 3px 0 0 3px; }
  .barcode-scan-corner.tr { top: 0; right: 0; border-width: 3px 3px 0 0; }
  .barcode-scan-corner.bl { bottom: 0; left: 0; border-width: 0 0 3px 3px; }
  .barcode-scan-corner.br { bottom: 0; right: 0; border-width: 0 3px 3px 0; }

  .barcode-url-label {
    font-size: 11px;
    color: var(--gray-light);
    font-weight: 500;
    text-align: center;
    word-break: break-all;
    width: 100%;
    max-width: 260px;
    background: white;
    border-radius: 10px;
    padding: 7px 10px;
    border: 1px solid rgba(255, 103, 154, 0.15);
    line-height: 1.45;
    box-sizing: border-box;
  }

  .barcode-download-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    border: none;
    border-radius: 20px;
    background: var(--pink);
    color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    box-shadow: 0 4px 12px rgba(255, 103, 154, 0.25);
  }

  .barcode-download-btn:hover {
    background: var(--pink-dark);
    transform: scale(1.03);
  }

  .upload-setting {
    border: 2px dashed rgba(255, 103, 154, 0.3);
    border-radius: 14px;
    height: 130px;
    cursor: pointer;
    overflow: hidden;
    transition: 0.2s;
    background: white;
  }

  .upload-setting:hover {
    border-color: var(--pink);
    background: var(--pink-soft);
  }

  .upload-setting-preview {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    box-sizing: border-box;
  }

  .upload-setting-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 8px;
  }

  .upload-setting-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    pointer-events: none;
    text-align: center;
  }

  .upload-setting-placeholder i {
    font-size: 28px;
    color: var(--pink);
    opacity: 0.5;
  }

  .upload-setting-placeholder span {
    font-size: 12px;
    font-weight: 600;
    color: var(--gray);
  }

  .upload-setting-placeholder small {
    font-size: 11px;
    color: var(--gray-light);
  }

  .setting-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 103, 154, 0.1);
  }

  .btn-save {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 28px;
    border: none;
    border-radius: 12px;
    background: var(--pink);
    color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    box-shadow: 0 4px 12px rgba(255, 103, 154, 0.3);
  }

  .btn-save:hover {
    background: var(--pink-dark);
    transform: scale(1.02);
  }

  .confirm-overlay,
  .success-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(5px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  .confirm-overlay.show,
  .success-overlay.show {
    display: flex;
  }

  .confirm-modal {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 440px;
    padding: 36px 32px;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    animation: slideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateY(-24px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  .confirm-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(255, 103, 154, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
  }

  .confirm-icon i { font-size: 26px; color: var(--pink); }

  .confirm-modal h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
  }

  .confirm-modal > p {
    font-size: 13px;
    color: var(--gray-light);
    margin-bottom: 16px;
  }

  .confirm-changes {
    background: var(--pink-soft);
    border: 1px solid rgba(255, 103, 154, 0.15);
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 24px;
    text-align: left;
    max-height: 200px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 103, 154, 0.3) transparent;
  }

  .confirm-changes::-webkit-scrollbar { width: 4px; }
  .confirm-changes::-webkit-scrollbar-thumb {
    background: rgba(255, 103, 154, 0.3);
    border-radius: 999px;
  }

  .change-item {
    display: flex;
    flex-direction: column;
    padding: 6px 0;
    border-bottom: 1px solid rgba(255, 103, 154, 0.08);
  }

  .change-item:last-child { border-bottom: none; }

  .change-label {
    font-size: 11px;
    color: var(--gray-light);
    font-weight: 600;
    margin-bottom: 2px;
  }

  .change-value {
    font-size: 13px;
    font-weight: 500;
    color: var(--dark);
    word-break: break-word;
  }

  .confirm-btns { display: flex; gap: 12px; }

  .btn-cancel {
    flex: 1;
    padding: 13px;
    border: 1px solid rgba(255, 103, 154, 0.25);
    border-radius: 34px;
    background: white;
    color: var(--dark);
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
  }

  .btn-cancel:hover {
    border-color: var(--pink);
    color: var(--pink);
  }

  .btn-confirm-save {
    flex: 1;
    padding: 13px;
    border: none;
    border-radius: 34px;
    background: var(--pink);
    color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 8px 20px rgba(255, 103, 154, 0.3);
  }

  .btn-confirm-save:hover {
    background: var(--pink-dark);
    transform: scale(1.02);
  }

  .btn-confirm-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .loading-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
  }

  .loading-overlay.show { display: flex; }

  .loading-box {
    background: white;
    border-radius: 24px;
    padding: 40px 48px;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
  }

  .loading-spinner {
    width: 56px;
    height: 56px;
    border: 5px solid rgba(255, 103, 154, 0.2);
    border-top-color: var(--pink);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
  }

  @keyframes spin { to { transform: rotate(360deg); } }

  .loading-box p {
    font-weight: 600;
    color: var(--dark);
    font-size: 16px;
    margin: 0 0 4px;
  }

  .loading-box small { color: var(--gray-light); font-size: 13px; }

  .success-box {
    background: white;
    border-radius: 24px;
    padding: 48px 42px;
    max-width: 420px;
    width: 100%;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .success-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 103, 154, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
  }

  .success-icon i { font-size: 40px; color: var(--pink); }

  .success-box h3 {
    font-size: 22px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 12px;
  }

  .success-box p {
    color: var(--gray-light);
    font-size: 14px;
    line-height: 1.7;
    margin-bottom: 28px;
  }

  .btn-ok {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 34px;
    background: var(--pink);
    color: white;
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s;
    box-shadow: 0 8px 20px rgba(255, 103, 154, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-ok:hover {
    background: var(--pink-dark);
    transform: scale(1.02);
  }

  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(38, 26, 33, 0.28);
    z-index: 90;
    display: none;
  }

  .overlay.show { display: block; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1100px) {
    .login-info-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 768px) {
    .content { padding: 18px; }
    .form-row-2 { grid-template-columns: 1fr; }
    .setting-body { padding: 18px; }
    .setting-header { padding: 16px 18px; }
    .login-info-grid { grid-template-columns: repeat(2, 1fr); }
    .success-box { padding: 36px 24px; }
    .confirm-modal { padding: 28px 20px; }
    .barcode-card { max-width: 100%; }
  }

  @media (max-width: 560px) {
    .content { padding: 14px; }
    .login-info-grid { grid-template-columns: 1fr; }
    .confirm-btns { flex-direction: column; }
  }
</style>
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

  function isValidUrl(value) {
    try {
      const url = new URL(value);
      return ['http:', 'https:'].includes(url.protocol);
    } catch (e) {
      return false;
    }
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

  function generateBarcode(url) {
    clearTimeout(barcodeDebounce);

    barcodeDebounce = setTimeout(() => {
      const empty = document.getElementById('barcodeEmpty');
      const result = document.getElementById('barcodeResult');
      const card = document.getElementById('barcodeCard');
      const label = document.getElementById('barcodeUrlLabel');

      const cleanUrl = (url || '').trim();

      if (!cleanUrl) {
        setBarcodeEmptyState();
        return;
      }

      empty.style.display = 'none';
      result.style.display = 'flex';
      card.classList.add('has-barcode');
      label.textContent = cleanUrl;

      if (!isValidUrl(cleanUrl)) {
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
            URL tidak valid.<br>Gunakan format http:// atau https://
          </div>
        `;
        return;
      }

      try {
        renderQrToPreview(cleanUrl);
      } catch (e) {
        document.getElementById('qrCanvas').innerHTML = `
          <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=190x190&margin=10&data=${encodeURIComponent(cleanUrl)}"
            width="190"
            height="190"
            alt="QR Code">
        `;
      }
    }, 250);
  }

  function clearUrl() {
    document.getElementById('qrUrl').value = '';
    setBarcodeEmptyState();
  }

  function downloadBarcode() {
    const url = document.getElementById('qrUrl').value.trim();

    if (!url) { alert('URL QR Code masih kosong.'); return; }
    if (!isValidUrl(url)) { alert('URL tidak valid. Gunakan format http:// atau https://'); return; }

    try {
      const dataUrl = createQrCanvasData(url, 1000);
      if (!dataUrl) { alert('QR Code gagal dibuat.'); return; }

      const link = document.createElement('a');
      link.download = 'qrcode-instansi.png';
      link.href = dataUrl;
      link.click();
    } catch (e) {
      const fallbackUrl = `https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&margin=40&data=${encodeURIComponent(url)}`;
      window.open(fallbackUrl, '_blank');
    }
  }

  window.addEventListener('DOMContentLoaded', () => {
    const existingUrl = document.getElementById('qrUrl').value.trim();
    if (existingUrl) {
      generateBarcode(existingUrl);
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
    qr_url: 'URL QR Code',
    logo: 'Logo Aplikasi',
    favicon: 'Favicon',
  };

  function getFormValues() {
    return {
      app_name: document.getElementById('appName').value.trim(),
      institution_name: document.getElementById('institutionName').value.trim(),
      institution_short: document.getElementById('institutionShort').value.trim(),
      year: document.getElementById('year').value.trim(),
      address: document.getElementById('address').value.trim(),
      description: document.getElementById('description').value.trim(),
      qr_url: document.getElementById('qrUrl').value.trim(),
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

    if (values.qr_url && !isValidUrl(values.qr_url)) {
      alert('URL QR Code tidak valid. Gunakan format http:// atau https://');
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

    const formData = new FormData();
    formData.append('app_name', document.getElementById('appName').value.trim());
    formData.append('institution_name', document.getElementById('institutionName').value.trim());
    formData.append('institution_short', document.getElementById('institutionShort').value.trim());
    formData.append('year', document.getElementById('year').value.trim());
    formData.append('address', document.getElementById('address').value.trim());
    formData.append('description', document.getElementById('description').value.trim());
    formData.append('qr_url', document.getElementById('qrUrl').value.trim());
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
