@extends('layouts.partials.app')

@php
  $pageTitle = 'Pengguna';
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
            <i class="fas fa-users-cog"></i>
            <h4>Daftar Pengguna</h4>
          </div>
          <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah
          </button>
        </div>

        <div class="table-toolbar">
          <div class="toolbar-left">
            <label class="show-label">Tampilkan</label>
            <select class="show-select" id="perPage" onchange="changePerPage(this.value)">
              <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
              <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
              <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            </select>
          </div>
          <div class="toolbar-right">
            <div class="search-box">
              <input type="text" id="searchInput"
                placeholder="Cari nama..."
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
                <th class="sortable" onclick="doSort('name')">
                  Nama
                  <span class="sort-icon">
                    @if(request('sort') === 'name')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th class="sortable hidden-mobile" onclick="doSort('email')">
                  Email
                  <span class="sort-icon">
                    @if(request('sort') === 'email')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th class="sortable" onclick="doSort('role')">
                  Role
                  <span class="sort-icon">
                    @if(request('sort') === 'role')
                      <i class="fas fa-sort-{{ request('direction', 'asc') === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                      <i class="fas fa-sort"></i>
                    @endif
                  </span>
                </th>
                <th class="sortable hidden-mobile" onclick="doSort('created_at')">
                  Bergabung
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
            <tbody>
              @forelse ($users as $index => $user)
                @php
                  $words    = explode(' ', trim($user->name));
                  $initials = strtoupper(substr($words[0] ?? '', 0, 1))
                            . (isset($words[1]) ? strtoupper(substr($words[1], 0, 1)) : '');
                  $roleLabel = [
                    'super_admin' => 'Super Admin',
                    'operator'    => 'Operator',
                    'staff'       => 'Staff',
                  ][$user->role] ?? ucfirst($user->role);
                @endphp
                <tr>
                  <td>{{ $users->firstItem() + $index }}</td>
                  <td>
                    <div class="user-name">
                      <div class="user-avatar">{{ $initials }}</div>
                      <div class="user-info">
                        <span class="user-fullname">{{ $user->name }}</span>
                        <span class="user-email-mobile">{{ $user->email }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="hidden-mobile">{{ $user->email }}</td>
                  <td>
                    <span class="role-badge {{ str_replace('_', '-', $user->role) }}">
                      {{ $roleLabel }}
                    </span>
                  </td>
                  <td class="hidden-mobile">{{ $user->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="action-btns">
                      <button class="btn-action edit" title="Edit"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ $user->role }}"
                        onclick="openEditModal(this)">
                        <i class="fas fa-pen"></i>
                      </button>
                      <button class="btn-action delete" title="Hapus"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        onclick="confirmDelete(this.dataset.name, this.dataset.id)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="empty-td">
                    <div class="empty-state">
                      <i class="fas fa-users-slash"></i>
                      <p>Belum ada data pengguna</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div class="pagination-info">
            @if($users->total() > 0)
              Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data
            @else
              Tidak ada data
            @endif
          </div>
          <div class="pagination">
            <button class="page-btn" {{ $users->onFirstPage() ? 'disabled' : '' }}
              onclick="goToPage('{{ $users->previousPageUrl() }}')">
              <i class="fas fa-chevron-left"></i>
            </button>
            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
              <button class="page-btn {{ $page == $users->currentPage() ? 'active' : '' }}"
                onclick="goToPage('{{ $url }}')">
                {{ $page }}
              </button>
            @endforeach
            <button class="page-btn" {{ !$users->hasMorePages() ? 'disabled' : '' }}
              onclick="goToPage('{{ $users->nextPageUrl() }}')">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layouts.footer')
  </div>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-box">
    <div class="modal-header">
      <h4 id="modalTitle"><i class="fas fa-user-plus"></i> Tambah Pengguna</h4>
      <button class="modal-close-btn" onclick="closeModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="userId">
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" id="inputName" placeholder="Contoh: Admin Dinas">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" id="inputEmail" placeholder="Contoh: admin@jcc.go.id">
      </div>
      <div class="form-group">
        <label>Role</label>
        <select id="inputRole">
          <option value="">-- Pilih Role --</option>
          <option value="super_admin">Super Admin</option>
          <option value="operator">Operator</option>
          <option value="staff">Staff</option>
        </select>
      </div>
      <div class="form-group">
        <label>Password</label>
        <div class="input-password">
          <input type="password" id="passwordInput" placeholder="Minimal 8 karakter">
          <button type="button" class="toggle-password" onclick="togglePassword()">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
        <small id="passwordHint" style="color: var(--gray-light); font-size: 12px; display:none; margin-top:6px;">
          Kosongkan jika tidak ingin mengubah password
        </small>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-modal-cancel" onclick="closeModal()">Batal</button>
      <button class="btn-modal-save" id="btnSave" onclick="saveUser()">
        <i class="fas fa-save"></i> Simpan
      </button>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-modal">
    <div class="confirm-icon">
      <i class="fas fa-trash"></i>
    </div>
    <h4>Hapus Pengguna?</h4>
    <p>Anda akan menghapus pengguna <strong id="confirmName"></strong>. Tindakan ini tidak dapat dibatalkan.</p>
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

  .btn-add {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
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
    white-space: nowrap;
    flex-shrink: 0;
  }

  .btn-add:hover { background: var(--pink-dark); transform: scale(1.02); }

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

  .user-name { display: flex; align-items: center; gap: 10px; }

  .user-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }

  .user-fullname {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .user-email-mobile {
    display: none;
    font-size: 11px;
    color: var(--gray-light);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .user-avatar {
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

  .role-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
  }

  .role-badge.super-admin { background: rgba(255, 103, 154, 0.12); color: var(--pink); }
  .role-badge.operator { background: rgba(59, 130, 246, 0.12); color: #3b82f6; }
  .role-badge.staff { background: rgba(16, 185, 129, 0.12); color: #10b981; }

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

  .btn-action.edit { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
  .btn-action.edit:hover { background: #f59e0b; color: white; transform: scale(1.1); }
  .btn-action.delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
  .btn-action.delete:hover { background: #ef4444; color: white; transform: scale(1.1); }

  .empty-td { text-align: center !important; padding: 0 !important; border-bottom: none !important; }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 56px 20px;
    color: var(--gray-light);
  }

  .empty-state i { font-size: 40px; margin-bottom: 12px; opacity: 0.4; }
  .empty-state p { font-size: 14px; margin: 0; }

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

  .modal-overlay,
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

  .modal-overlay.show,
  .confirm-overlay.show { display: flex; }

  .modal-box {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2);
    animation: slideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateY(-24px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  .modal-header {
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

  .modal-header h4 {
    font-size: 16px;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .modal-header h4 i { color: var(--pink); }

  .modal-close-btn {
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
    flex-shrink: 0;
  }

  .modal-close-btn:hover { background: var(--pink); color: white; }

  .modal-body { padding: 24px; }

  .form-group { margin-bottom: 20px; }

  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray);
    margin-bottom: 8px;
  }

  .form-group input,
  .form-group select {
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
  }

  .form-group input:focus,
  .form-group select:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px rgba(255, 103, 154, 0.1);
  }

  .input-password { position: relative; }
  .input-password input { padding-right: 44px; }

  .toggle-password {
    position: absolute;
    top: 50%; right: 14px;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: var(--gray-light);
    cursor: pointer;
    font-size: 14px;
    padding: 0;
    transition: 0.2s;
  }

  .toggle-password:hover { color: var(--pink); }

  .modal-footer {
    display: flex;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid rgba(255, 103, 154, 0.1);
    position: sticky;
    bottom: 0;
    background: white;
  }

  .btn-modal-cancel {
    flex: 1; padding: 12px;
    border: 1px solid rgba(255, 103, 154, 0.25);
    border-radius: 12px;
    background: white; color: var(--dark);
    font-family: 'Poppins', sans-serif;
    font-size: 13px; font-weight: 600;
    cursor: pointer; transition: 0.2s;
  }

  .btn-modal-cancel:hover { border-color: var(--pink); color: var(--pink); }

  .btn-modal-save {
    flex: 1; padding: 12px;
    border: none; border-radius: 12px;
    background: var(--pink); color: white;
    font-family: 'Poppins', sans-serif;
    font-size: 13px; font-weight: 600;
    cursor: pointer; transition: 0.2s;
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    box-shadow: 0 4px 12px rgba(255, 103, 154, 0.3);
  }

  .btn-modal-save:hover { background: var(--pink-dark); transform: scale(1.02); }
  .btn-modal-save:disabled { opacity: 0.6; cursor: not-allowed; }

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
    display: flex; align-items: center;
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
    border-radius: 34px; background: white; color: var(--dark);
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

  /* ---- Responsive ---- */
  @media (max-width: 768px) {
    .content { padding: 16px; }

    .table-card { padding: 16px; }

    .table-toolbar { gap: 10px; }

    .hidden-mobile { display: none !important; }

    .user-email-mobile { display: block; }

    .search-box { max-width: 100%; }

    .table-footer { flex-direction: column; align-items: flex-start; gap: 10px; }

    .confirm-modal { padding: 28px 20px; }
  }

  @media (max-width: 560px) {
    .content { padding: 12px; }

    .table-card { padding: 14px; border-radius: 16px; }

    .table-header { gap: 8px; }

    .table-title h4 { font-size: 14px; }

    .btn-add { padding: 8px 14px; font-size: 12px; }

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
      showToast('Pengguna berhasil dihapus!', 'success');
      cleanUrl(['deleted']);
    }

    if (params.get('added') === '1') {
      showToast('Pengguna baru berhasil ditambahkan!', 'success');
      cleanUrl(['added']);
    }

    if (params.get('updated') === '1') {
      showToast('Data pengguna berhasil diperbarui!', 'success');
      cleanUrl(['updated']);
    }
  });

  function cleanUrl(keys) {
    const url = new URL(window.location.href);
    keys.forEach(k => url.searchParams.delete(k));
    window.history.replaceState({}, '', url);
  }

  function openAddModal() {
    document.getElementById('userId').value        = '';
    document.getElementById('inputName').value     = '';
    document.getElementById('inputEmail').value    = '';
    document.getElementById('inputRole').value     = '';
    document.getElementById('passwordInput').value = '';
    document.getElementById('passwordHint').style.display = 'none';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Tambah Pengguna';
    document.getElementById('modalOverlay').classList.add('show');
  }

  function openEditModal(btn) {
    document.getElementById('userId').value        = btn.dataset.id;
    document.getElementById('inputName').value     = btn.dataset.name;
    document.getElementById('inputEmail').value    = btn.dataset.email;
    document.getElementById('inputRole').value     = btn.dataset.role;
    document.getElementById('passwordInput').value = '';
    document.getElementById('passwordHint').style.display = 'block';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit"></i> Edit Pengguna';
    document.getElementById('modalOverlay').classList.add('show');
  }

  function closeModal() {
    document.getElementById('modalOverlay').classList.remove('show');
  }

  document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });

  function togglePassword() {
    const input    = document.getElementById('passwordInput');
    const icon     = document.getElementById('eyeIcon');
    const isHidden = input.type === 'password';
    input.type     = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
  }

  function saveUser() {
    const id       = document.getElementById('userId').value;
    const name     = document.getElementById('inputName').value.trim();
    const email    = document.getElementById('inputEmail').value.trim();
    const role     = document.getElementById('inputRole').value;
    const password = document.getElementById('passwordInput').value;
    const isEdit   = !!id;

    if (!name || !email || !role) {
      showToast('Nama, email, dan role wajib diisi.', 'error');
      return;
    }

    if (!isEdit && !password) {
      showToast('Password wajib diisi untuk pengguna baru.', 'error');
      return;
    }

    const url    = isEdit ? `/users/${id}` : '/users';
    const method = isEdit ? 'PUT' : 'POST';
    const btnSave = document.getElementById('btnSave');

    showLoading(isEdit ? 'Memperbarui data...' : 'Menyimpan data...');
    btnSave.disabled = true;
    closeModal();

    const body = { name, email, role };
    if (password) body.password = password;

    fetch(url, {
      method,
      headers: {
        'Content-Type' : 'application/json',
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept'       : 'application/json',
      },
      body: JSON.stringify(body)
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));
      if (!res.ok) throw new Error(data.message || 'Gagal menyimpan data.');
      return data;
    })
    .then(data => {
      hideLoading();
      btnSave.disabled = false;

      if (data.success) {
        const url = new URL(window.location.href);
        url.searchParams.set(isEdit ? 'updated' : 'added', '1');
        showLoading(isEdit ? 'Memperbarui data...' : 'Menyimpan data...');
        window.location.href = url.toString();
      } else {
        showToast('Gagal menyimpan data.', 'error');
      }
    })
    .catch(err => {
      hideLoading();
      btnSave.disabled = false;
      showToast(err.message || 'Terjadi kesalahan.', 'error');
    });
  }

  let deleteId = null;

  function confirmDelete(name, id) {
    deleteId = id;
    document.getElementById('confirmName').textContent = name;
    document.getElementById('confirmOverlay').classList.add('show');
  }

  function closeConfirm() {
    document.getElementById('confirmOverlay').classList.remove('show');
    deleteId = null;
  }

  document.getElementById('confirmOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
  });

  function doDelete() {
    const id = deleteId;
    if (!id) return;

    const btnDelete = document.querySelector('.btn-confirm-delete');

    showLoading('Menghapus data...');
    btnDelete.disabled = true;
    document.getElementById('confirmOverlay').classList.remove('show');

    fetch(`/users/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept'       : 'application/json',
      }
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));
      if (!res.ok) throw new Error(data.message || 'Gagal menghapus.');
      return data;
    })
    .then(data => {
      deleteId = null;
      btnDelete.disabled = false;

      if (data.success) {
        const url = new URL(window.location.href);
        url.searchParams.set('deleted', '1');
        window.location.href = url.toString();
      } else {
        hideLoading();
        showToast('Gagal menghapus pengguna.', 'error');
      }
    })
    .catch(err => {
      hideLoading();
      deleteId = null;
      btnDelete.disabled = false;
      showToast(err.message || 'Terjadi kesalahan.', 'error');
    });
  }

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
</script>
@endpush
