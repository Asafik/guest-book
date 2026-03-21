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
<link rel="stylesheet" href="{{ asset('admin/css/user.css') }}">
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
