@extends('layouts.partials.app')

@php
    $pageTitle = 'Kunjungan';

    // Mapping purpose ke bahasa Indonesia
    $purposeMap = [
        'coordination' => 'Koordinasi',
        'audience' => 'Audiensi',
        'monitoring' => 'Monitoring',
        'meeting' => 'Rapat',
        'visit' => 'Bertemu',
        'other' => 'Lainnya'
    ];
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
                            <input
                                type="text"
                                id="searchInput"
                                placeholder="Cari nama tamu..."
                                value="{{ request('search') }}"
                                onkeydown="if(event.key==='Enter') doSearch()"
                            >
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

                                    // Konversi purpose ke bahasa Indonesia
                                    $purposeText = $purposeMap[$guest->purpose] ?? ucfirst($guest->purpose);
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
                                    <td>
                                        <span class="purpose-badge">{{ $purposeText }}</span>
                                    </td>
                                    <td class="hidden-mobile">{{ $guest->created_at->format('d M Y') }}</td>

                                    <td>
                                        <div class="action-btns">
                                            <button
                                                class="btn-action view"
                                                title="Lihat Detail"
                                                data-name="{{ $guest->full_name }}"
                                                data-initials="{{ $initials }}"
                                                data-address="{{ $guest->address ?? '-' }}"
                                                data-institution="{{ $guest->institution ?? '-' }}"
                                                data-phone="{{ $guest->phone_number ?? '-' }}"
                                                data-purpose="{{ $purposeText }}"
                                                data-meetwith="{{ $guest->meet_with ?? '-' }}"
                                                data-notes="{{ $guest->notes ?? '-' }}"
                                                data-date="{{ $guest->created_at->format('d M Y H:i') }}"
                                                onclick="showDetail(this)">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button
                                                class="btn-action delete"
                                                title="Hapus"
                                                data-id="{{ $guest->id }}"
                                                data-name="{{ $guest->full_name }}"
                                                onclick="confirmDelete(this.dataset.name, this.dataset.id)"
                                            >
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
                        <button
                            class="page-btn"
                            {{ $guests->onFirstPage() ? 'disabled' : '' }}
                            onclick="goToPage('{{ $guests->previousPageUrl() }}')"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        @foreach ($guests->getUrlRange(1, $guests->lastPage()) as $page => $url)
                            <button
                                class="page-btn {{ $page == $guests->currentPage() ? 'active' : '' }}"
                                onclick="goToPage('{{ $url }}')"
                            >
                                {{ $page }}
                            </button>
                        @endforeach

                        <button
                            class="page-btn"
                            {{ !$guests->hasMorePages() ? 'disabled' : '' }}
                            onclick="goToPage('{{ $guests->nextPageUrl() }}')"
                        >
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
            <div class="detail-avatar-large-wrap">
                <div class="detail-avatar-large" id="detailInitials">-</div>
            </div>

            <div class="detail-info">
                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-user"></i> Nama Lengkap</div>
                    <div class="detail-value" id="detailName"></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label"><i class="fas fa-map-marker-alt"></i> Alamat</div>
                    <div class="detail-value" id="detailAddress"></div>
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
        <p>
            Anda akan menghapus data kunjungan atas nama
            <strong id="confirmName"></strong>.
            Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="confirm-btns">
            <button class="btn-cancel" onclick="closeConfirm()">Batal</button>
            <button class="btn-confirm-delete" onclick="doDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p id="loadingText">Memuat data...</p>
        <small>Mohon tunggu sebentar</small>
    </div>
</div>

<!-- Toast Notifikasi -->
<div class="toast-notif" id="toastNotif">
    <i class="fas fa-check-circle" id="toastIcon"></i>
    <span id="toastText">Berhasil!</span>
    <div class="toast-bar" id="toastBar"></div>
</div>

<div class="overlay" id="overlay"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/css/guest.css') }}">
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

        const baseUrl = '{{ url("") }}';
        const finalUrl = baseUrl + url.pathname + url.search;

        window.location.href = finalUrl;

        setTimeout(() => {
            hideLoading();
            showToast('Berhasil mengekspor ' + type.toUpperCase() + '!', 'success');
        }, 2000);
    }

    function doSort(column) {
        const url = new URL(window.location.href);
        const current = url.searchParams.get('sort');
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
    document.getElementById('detailInitials').textContent = btn.dataset.initials || '-';
    document.getElementById('detailName').textContent = btn.dataset.name || '-';
    document.getElementById('detailAddress').textContent = btn.dataset.address || '-';
    document.getElementById('detailInstitution').textContent = btn.dataset.institution || '-';
    document.getElementById('detailPhone').textContent = btn.dataset.phone || '-';
    document.getElementById('detailPurpose').textContent = btn.dataset.purpose || '-';
    document.getElementById('detailMeetWith').textContent = btn.dataset.meetwith || '-';
    document.getElementById('detailNotes').textContent = btn.dataset.notes || '-';
    document.getElementById('detailDate').textContent = btn.dataset.date || '-';

    document.getElementById('detailOverlay').classList.add('show');
}

    function closeDetail() {
        document.getElementById('detailOverlay').classList.remove('show');
    }

    document.getElementById('detailOverlay').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDetail();
        }
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

    document.getElementById('confirmOverlay').addEventListener('click', function (e) {
        if (e.target === this) {
            closeConfirm();
        }
    });

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastNotif');
        const bar = document.getElementById('toastBar');
        const icon = document.getElementById('toastIcon');

        document.getElementById('toastText').textContent = message;
        icon.className = type === 'success'
            ? 'fas fa-check-circle'
            : 'fas fa-times-circle';

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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async (res) => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                throw new Error(data.message || 'Gagal menghapus data.');
            }
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
