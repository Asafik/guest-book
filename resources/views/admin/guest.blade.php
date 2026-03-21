@extends('layouts.partials.app')

@php
  $pageTitle = 'Kunjungan';
@endphp

@section('content')
<div class="admin">
  @include('admin.layouts.sidebar')

  <div class="main">
    @include('admin.layouts.navbar')

    <div class="content">
      <div class="table-card">
        <div class="table-header">
          <div class="table-title">
            <i class="fas fa-list-alt"></i>
            <h4>Daftar Kunjungan</h4>
          </div>
          <div class="header-actions">
            <button type="button" class="btn-export excel" onclick="exportData('excel')">
              <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button type="button" class="btn-export pdf" onclick="exportData('pdf')">
              <i class="fas fa-file-pdf"></i> Export PDF
            </button>
          </div>
        </div>

        <div class="table-toolbar">
          <div class="toolbar-left">
            <label class="show-label">Tampilkan</label>
            <select class="show-select" id="perPage" onchange="changePerPage(this.value)">
              <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
              <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
              <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            </select>
            <label class="show-label">data</label>
          </div>

          <div class="toolbar-right">
            <div class="search-box">
              <input type="text" id="searchInput"
                placeholder="Cari nama tamu..."
                value="{{ request('search') }}"
                onkeydown="if(event.key==='Enter') doSearch()">
              <button class="search-icon-btn" onclick="doSearch()">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th width="40">No</th>
                <th class="sortable" onclick="doSort('full_name')">
                  Nama
                  <span class="sort-icon">
                    @if(request('sort') === 'full_name')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th class="sortable hidden-mobile" onclick="doSort('institution')">
                  Instansi
                  <span class="sort-icon">
                    @if(request('sort') === 'institution')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th class="hidden-mobile">No. HP</th>
                <th class="sortable" onclick="doSort('purpose')">
                  Keperluan
                  <span class="sort-icon">
                    @if(request('sort') === 'purpose')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th class="sortable hidden-mobile" onclick="doSort('created_at')">
                  Tanggal
                  <span class="sort-icon">
                    @if(request('sort') === 'created_at')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th width="80">Aksi</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              @forelse ($guests as $index => $guest)
                @php
                  $words    = explode(' ', trim($guest->full_name));
                  $initials = strtoupper(substr($words[0] ?? '', 0, 1))
                            . (isset($words[1]) ? strtoupper(substr($words[1], 0, 1)) : '');
                @endphp
                <tr>
                  <td>{{ $guests->firstItem() + $index }}</td>
                  <td>
                    <div class="guest-name">
                      <div class="guest-avatar">{{ $initials }}</div>
                      <div class="guest-info">
                        <span class="guest-fullname">{{ $guest->full_name }}</span>
                        <span class="guest-sub-mobile">{{ $guest->institution ?? '-' }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="hidden-mobile">{{ $guest->institution ?? '-' }}</td>
                  <td class="hidden-mobile">{{ $guest->phone_number ?? '-' }}</td>
                  <td><span class="purpose-badge">{{ ucfirst($guest->purpose) }}</span></td>
                  <td class="hidden-mobile">{{ $guest->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="action-btns">
                      <button class="btn-action view" title="Lihat Detail"
                        data-name="{{ $guest->full_name }}"
                        data-initials="{{ $initials }}"
                        data-institution="{{ $guest->institution ?? '-' }}"
                        data-phone="{{ $guest->phone_number ?? '-' }}"
                        data-purpose="{{ ucfirst($guest->purpose) }}"
                        data-meetwith="{{ $guest->meet_with ?? '-' }}"
                        data-notes="{{ $guest->notes ?? '-' }}"
                        data-date="{{ $guest->created_at->format('d M Y H:i') }}"
                        data-photo="{{ $guest->photo ? asset('storage/'.$guest->photo) : '' }}"
                        onclick="showDetail(this)">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn-action delete" title="Hapus"
                        data-id="{{ $guest->id }}"
                        data-name="{{ $guest->full_name }}"
                        onclick="confirmDelete(this.dataset.name, this.dataset.id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="empty-td">
                    <div class="empty-state">
                      <i class="fas fa-inbox"></i>
                      <p>Belum ada data kunjungan</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div class="pagination-info">
            @if($guests->total() > 0)
              Menampilkan {{ $guests->firstItem() }} - {{ $guests->lastItem() }} dari {{ $guests->total() }} data
            @else
              Tidak ada data
            @endif
          </div>
          <div class="pagination">
            <button class="page-btn" {{ $guests->onFirstPage() ? 'disabled' : '' }}
              onclick="goToPage('{{ $guests->previousPageUrl() }}')">
              <i class="fas fa-chevron-left"></i>
            </button>
            @foreach ($guests->getUrlRange(1, $guests->lastPage()) as $page => $url)
              <button class="page-btn {{ $page == $guests->currentPage() ? 'active' : '' }}"
                onclick="goToPage('{{ $url }}')">
                {{ $page }}
              </button>
            @endforeach
            <button class="page-btn" {{ !$guests->hasMorePages() ? 'disabled' : '' }}
              onclick="goToPage('{{ $guests->nextPageUrl() }}')">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layouts.footer')
  </div>
</div>

<!-- Modal Detail -->
<div class="detail-overlay" id="detailOverlay">
  <div class="detail-modal">
    <div class="detail-header">
      <h4><i class="fas fa-user"></i> Detail Kunjungan</h4>
      <button class="detail-close" onclick="closeDetail()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="detail-body">
      <div class="detail-photo-wrap" id="detailPhotoWrap"></div>
      <div class="detail-info">
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-user"></i> Nama Lengkap</div>
          <div class="detail-value" id="detailName"></div>
        </div>
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-building"></i> Instansi</div>
          <div class="detail-value" id="detailInstitution"></div>
        </div>
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-phone"></i> No. HP</div>
          <div class="detail-value" id="detailPhone"></div>
        </div>
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-clipboard-list"></i> Keperluan</div>
          <div class="detail-value" id="detailPurpose"></div>
        </div>
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-handshake"></i> Ketemu Dengan</div>
          <div class="detail-value" id="detailMeetWith"></div>
        </div>
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-sticky-note"></i> Catatan</div>
          <div class="detail-value" id="detailNotes"></div>
        </div>
        <div class="detail-row">
          <div class="detail-label"><i class="fas fa-calendar"></i> Tanggal</div>
          <div class="detail-value" id="detailDate"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-modal">
    <div class="confirm-icon">
      <i class="fas fa-trash"></i>
    </div>
    <h4>Hapus Data Kunjungan?</h4>
    <p>Anda akan menghapus data kunjungan atas nama <strong id="confirmName"></strong>. Tindakan ini tidak dapat dibatalkan.</p>
    <div class="confirm-btns">
      <button class="btn-cancel" onclick="closeConfirm()">Batal</button>
      <button class="btn-confirm-delete" onclick="doDelete()">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- Loading Overlay (dari global) -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-box">
    <div class="loading-spinner"></div>
    <p id="loadingText">Memuat data...</p>
    <small>Mohon tunggu sebentar</small>
  </div>
</div>

<!-- Toast Notifikasi (dari global) -->
<div class="toast-notif" id="toastNotif">
  <i class="fas fa-check-circle" id="toastIcon"></i>
  <span id="toastText">Berhasil!</span>
  <div class="toast-bar" id="toastBar"></div>
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
  }

  .content {
    padding: 24px;
    flex: 1;
    min-width: 0;
    overflow: hidden;
  }

  .table-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-soft);
    min-width: 0;
    padding: 20px;
  }

  .table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
  }

  .table-title { display: flex; align-items: center; gap: 10px; min-width: 0; }
  .table-title i { color: var(--pink); flex-shrink: 0; }
  .table-title h4 { font-size: 15px; font-weight: 600; color: var(--dark); white-space: nowrap; }

  .header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .btn-export {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn-export.excel {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
  }

  .btn-export.excel:hover {
    background: #10b981;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
  }

  .btn-export.pdf {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
  }

  .btn-export.pdf:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
  }

  .table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }

  .toolbar-left { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
  .show-label { font-size: 13px; color: var(--gray-light); font-weight: 500; }

  .show-select {
    padding: 7px 12px;
    border: 1px solid rgba(255, 103, 154, 0.25);
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: var(--dark);
    background: white;
    cursor: pointer;
    outline: none;
    transition: 0.2s;
  }

  .show-select:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
  }

  .toolbar-right { display: flex; align-items: center; flex: 1; justify-content: flex-end; }

  .search-box {
    display: flex;
    align-items: center;
    border: 1px solid rgba(255, 103, 154, 0.25);
    border-radius: 12px;
    background: white;
    overflow: hidden;
    transition: 0.2s;
    width: 100%;
    max-width: 280px;
  }

  .search-box:focus-within {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
  }

  .search-box input {
    border: none; outline: none;
    padding: 9px 14px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: var(--dark);
    flex: 1;
    min-width: 0;
    background: transparent;
  }

  .search-box input::placeholder { color: #bbb; }

  .search-icon-btn {
    width: 38px; height: 38px;
    border: none;
    background: var(--pink);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: 0.2s;
    flex-shrink: 0;
  }

  .search-icon-btn:hover { background: var(--pink-dark); }

  .table-responsive {
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 103, 154, 0.35) rgba(255, 103, 154, 0.06);
  }

  .table-responsive::-webkit-scrollbar { height: 5px; }
  .table-responsive::-webkit-scrollbar-track {
    background: rgba(255, 103, 154, 0.06);
    border-radius: 999px;
  }
  .table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, #ff679a, #e05588);
    border-radius: 999px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  th {
    text-align: left;
    padding: 13px 12px;
    color: var(--gray-light);
    font-weight: 600;
    border-bottom: 1px solid rgba(255, 103, 154, 0.18);
    white-space: nowrap;
  }

  th.sortable {
    cursor: pointer;
    user-select: none;
    transition: 0.2s;
  }

  th.sortable:hover { color: var(--pink); }

  .sort-icon { margin-left: 6px; font-size: 11px; opacity: 0.5; }
  th.sortable:hover .sort-icon { opacity: 1; color: var(--pink); }

  td {
    padding: 12px 12px;
    border-bottom: 1px solid rgba(255, 103, 154, 0.08);
    color: var(--dark);
    vertical-align: middle;
  }

  tbody tr:hover { background: var(--pink-soft); }

  .empty-td { text-align: center !important; padding: 0 !important; border-bottom: none !important; }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 56px 20px;
    color: var(--gray-light);
    width: 100%;
  }

  .empty-state i { font-size: 40px; margin-bottom: 12px; opacity: 0.4; }
  .empty-state p { font-size: 14px; margin: 0; }

  .guest-name { display: flex; align-items: center; gap: 10px; }

  .guest-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }

  .guest-fullname {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .guest-sub-mobile {
    display: none;
    font-size: 11px;
    color: var(--gray-light);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .guest-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff679a, #e05588);
    color: white;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.5px;
  }

  .purpose-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    background: rgba(255, 103, 154, 0.1);
    color: var(--pink);
    white-space: nowrap;
  }

  .action-btns { display: flex; align-items: center; gap: 6px; }

  .btn-action {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all 0.2s ease;
  }

  .btn-action.view { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
  .btn-action.view:hover { background: #3b82f6; color: white; transform: scale(1.1); }
  .btn-action.delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
  .btn-action.delete:hover { background: #ef4444; color: white; transform: scale(1.1); }

  .table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 16px;
    flex-wrap: wrap;
  }

  .pagination-info { font-size: 13px; color: var(--gray-light); }
  .pagination { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }

  .page-btn {
    width: 34px; height: 34px;
    border-radius: 8px;
    border: 1px solid rgba(255, 103, 154, 0.2);
    background: white;
    color: var(--dark);
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
  }

  .page-btn:hover:not(:disabled) { border-color: var(--pink); color: var(--pink); }
  .page-btn.active { background: var(--pink); border-color: var(--pink); color: white; }
  .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }

  /* Detail Modal */
  .detail-overlay,
  .confirm-overlay {
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

  .detail-overlay.show,
  .confirm-overlay.show { display: flex; }

  .detail-modal {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    animation: slideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 103, 154, 0.3) transparent;
  }

  .detail-modal::-webkit-scrollbar { width: 4px; }
  .detail-modal::-webkit-scrollbar-thumb {
    background: rgba(255, 103, 154, 0.3);
    border-radius: 999px;
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateY(-24px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  .detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(255, 103, 154, 0.12);
    position: sticky;
    top: 0;
    background: white;
    z-index: 2;
  }

  .detail-header h4 {
    font-size: 16px;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .detail-header h4 i { color: var(--pink); }

  .detail-close {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: none;
    background: rgba(255, 103, 154, 0.1);
    color: var(--pink);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: 0.2s;
  }

  .detail-close:hover { background: var(--pink); color: white; }

  .detail-body { padding: 24px; }
  .detail-photo-wrap { margin-bottom: 24px; }

  .detail-photo-img {
    width: 100%;
    max-height: 280px;
    object-fit: cover;
    border-radius: 16px;
    border: 1px solid rgba(255, 103, 154, 0.15);
    display: block;
  }

  .detail-photo-caption {
    text-align: center;
    font-size: 12px;
    color: var(--gray-light);
    margin-top: 8px;
  }

  .detail-avatar-large {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff679a, #e05588);
    color: white;
    font-size: 30px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 1px;
    box-shadow: 0 8px 24px rgba(255, 103, 154, 0.3);
    margin: 0 auto;
  }

  .detail-info { display: flex; flex-direction: column; }

  .detail-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 0;
    border-bottom: 1px solid rgba(255, 103, 154, 0.08);
  }

  .detail-row:last-child { border-bottom: none; }

  .detail-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-light);
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 145px;
    flex-shrink: 0;
  }

  .detail-label i { color: var(--pink); font-size: 12px; }

  .detail-value {
    font-size: 13px;
    font-weight: 500;
    color: var(--dark);
    line-height: 1.5;
  }

  /* Confirm Modal */
  .confirm-modal {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 400px;
    padding: 36px 32px;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    animation: slideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .confirm-icon {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
  }

  .confirm-icon i { font-size: 26px; color: #ef4444; }
  .confirm-modal h4 { font-size: 20px; font-weight: 700; color: var(--dark); margin-bottom: 12px; }
  .confirm-modal p { font-size: 14px; color: var(--gray-light); line-height: 1.6; margin-bottom: 28px; }
  .confirm-modal p strong { color: var(--dark); font-weight: 600; }

  .confirm-btns { display: flex; gap: 12px; }

  .btn-cancel {
    flex: 1; padding: 13px;
    border: 1px solid rgba(255, 103, 154, 0.25);
    border-radius: 34px;
    background: white; color: var(--dark);
    font-family: 'Poppins', sans-serif;
    font-size: 14px; font-weight: 600;
    cursor: pointer; transition: 0.2s;
  }

  .btn-cancel:hover { border-color: var(--pink); color: var(--pink); }

  .btn-confirm-delete {
    flex: 1; padding: 13px;
    border: none; border-radius: 34px;
    background: #ef4444; color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 14px; font-weight: 600;
    cursor: pointer; transition: 0.2s;
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
  }

  .btn-confirm-delete:hover { background: #dc2626; transform: scale(1.02); }
  .btn-confirm-delete:disabled { opacity: 0.6; cursor: not-allowed; }

  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(38, 26, 33, 0.28);
    z-index: 90;
    display: none;
  }

  .overlay.show { display: block; }

  /* ---- Responsive ---- */
  @media (max-width: 768px) {
    .content { padding: 16px; }
    .table-card { padding: 16px; }
    .table-toolbar { gap: 10px; }
    .hidden-mobile { display: none !important; }
    .guest-sub-mobile { display: block; }
    .search-box { max-width: 100%; }
    .table-footer { flex-direction: column; align-items: flex-start; gap: 10px; }
    .detail-label { min-width: 120px; }
  }

  @media (max-width: 560px) {
    .content { padding: 12px; }
    .table-card { padding: 14px; border-radius: 16px; }
    .table-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .header-actions { width: 100%; display: flex; gap: 8px; }
    .btn-export { flex: 1; justify-content: center; }
    .table-toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
    .toolbar-left { justify-content: flex-start; }
    .toolbar-right { justify-content: stretch; }
    .search-box { max-width: 100%; width: 100%; }
    .confirm-btns { flex-direction: column; }
    .pagination { justify-content: center; width: 100%; }
    .pagination-info { font-size: 12px; }
  }
</style>
@endpush

@push('scripts')
<script>
  function showLoading(text = 'Memuat data...') {
    document.getElementById('loadingText').textContent = text;
    document.getElementById('loadingOverlay').classList.add('show');
  }

  function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('show');
  }

  function goToPage(url) {
    if (!url) return;
    showLoading('Memuat data...');
    window.location.href = url;
  }

  function changePerPage(val) {
    showLoading('Memfilter data...');
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.delete('page');
    window.location.href = url.toString();
  }

  function doSearch() {
    const q = document.getElementById('searchInput').value.trim();
    showLoading('Mencari data...');
    const url = new URL(window.location.href);
    url.searchParams.set('search', q);
    url.searchParams.delete('page');
    window.location.href = url.toString();
  }

  function exportData(type) {
    showLoading('Memproses ekspor...');
    const url = new URL(window.location.href);
    if (type === 'excel') {
      url.pathname = '/guests/export/excel';
    } else {
      url.pathname = '/guests/export/pdf';
    }
    
    // Use the backend routes we defined, maintaining search params
    const baseUrl = '{{ url("") }}';
    const finalUrl = baseUrl + url.pathname + url.search;
    
    window.location.href = finalUrl;

    // Sembunyikan loading dan tampilkan toast setelah beberapa saat
    setTimeout(() => {
      hideLoading();
      showToast('Berhasil mengekspor ' + type.toUpperCase() + '!', 'success');
    }, 2000);
  }

  function doSort(column) {
    const url       = new URL(window.location.href);
    const current   = url.searchParams.get('sort');
    const direction = url.searchParams.get('direction');

    if (current === column) {
      url.searchParams.set('direction', direction === 'asc' ? 'desc' : 'asc');
    } else {
      url.searchParams.set('sort', column);
      url.searchParams.set('direction', 'asc');
    }

    url.searchParams.delete('page');
    showLoading('Mengurutkan data...');
    window.location.href = url.toString();
  }

  window.addEventListener('load', () => {
    hideLoading();

    const params = new URLSearchParams(window.location.search);
    if (params.get('deleted') === '1') {
      showToast('Data kunjungan berhasil dihapus!', 'success');
      const url = new URL(window.location.href);
      url.searchParams.delete('deleted');
      window.history.replaceState({}, '', url);
    }
  });

  function showDetail(btn) {
    const photoWrap = document.getElementById('detailPhotoWrap');
    const photo     = btn.dataset.photo;

    if (photo) {
      photoWrap.innerHTML = `
        <img src="${photo}" alt="Foto Kunjungan" class="detail-photo-img">
        <div class="detail-photo-caption"><i class="fas fa-image"></i> Foto Kunjungan</div>
      `;
    } else {
      photoWrap.innerHTML = `
        <div class="detail-avatar-large">${btn.dataset.initials || '-'}</div>
      `;
    }

    document.getElementById('detailName').textContent        = btn.dataset.name        || '-';
    document.getElementById('detailInstitution').textContent = btn.dataset.institution  || '-';
    document.getElementById('detailPhone').textContent       = btn.dataset.phone        || '-';
    document.getElementById('detailPurpose').textContent     = btn.dataset.purpose      || '-';
    document.getElementById('detailMeetWith').textContent    = btn.dataset.meetwith     || '-';
    document.getElementById('detailNotes').textContent       = btn.dataset.notes        || '-';
    document.getElementById('detailDate').textContent        = btn.dataset.date         || '-';

    document.getElementById('detailOverlay').classList.add('show');
  }

  function closeDetail() {
    document.getElementById('detailOverlay').classList.remove('show');
  }

  document.getElementById('detailOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
  });

  let deleteId = null;

  function confirmDelete(name, id) {
    deleteId = id;
    document.getElementById('confirmName').textContent = name || '-';
    document.getElementById('confirmOverlay').classList.add('show');
  }

  function closeConfirm() {
    document.getElementById('confirmOverlay').classList.remove('show');
    deleteId = null;
  }

  document.getElementById('confirmOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
  });

  function showToast(message, type = 'success') {
    const toast = document.getElementById('toastNotif');
    const bar   = document.getElementById('toastBar');
    const icon  = document.getElementById('toastIcon');

    document.getElementById('toastText').textContent = message;
    icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-times-circle';
    toast.classList.remove('success', 'error');
    toast.classList.add(type);

    bar.style.animation = 'none';
    bar.offsetHeight;
    bar.style.animation = '';

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function doDelete() {
    const id = deleteId;
    if (!id) return;

    const btnDelete = document.querySelector('.btn-confirm-delete');

    showLoading('Menghapus data...');
    btnDelete.disabled = true;
    document.getElementById('confirmOverlay').classList.remove('show');

    fetch(`/guests/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN'     : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept'           : 'application/json',
        'X-Requested-With' : 'XMLHttpRequest'
      }
    })
    .then(async (res) => {
      const data = await res.json().catch(() => ({}));
      if (!res.ok) throw new Error(data.message || 'Gagal menghapus data.');
      return data;
    })
    .then((data) => {
      deleteId = null;
      btnDelete.disabled = false;

      if (data.success) {
        const url = new URL(window.location.href);
        url.searchParams.set('deleted', '1');
        window.location.href = url.toString();
      } else {
        hideLoading();
        showToast('Gagal menghapus data.', 'error');
      }
    })
    .catch((error) => {
      hideLoading();
      deleteId = null;
      btnDelete.disabled = false;
      showToast(error.message || 'Terjadi kesalahan.', 'error');
    });
  }
</script>
@endpush
