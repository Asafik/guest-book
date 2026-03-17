@extends('layouts.partials.app')

@php
  $pageTitle = 'Dashboard';
@endphp

@section('content')
<div class="admin">
  @include('admin.layouts.sidebar')

  <div class="main">
    @include('admin.layouts.navbar')

    <div class="content">
      <div class="grid">
        <div class="card">
          <div class="card-header">
            <div class="card-icon"><i class="fas fa-users"></i></div>
            <div class="card-label">Total Kunjungan</div>
          </div>
          <div class="card-value">{{ number_format($totalVisitors) }}</div>
          <div class="card-meta neutral">Semua kunjungan</div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-icon"><i class="fas fa-user-clock"></i></div>
            <div class="card-label">Kunjungan Hari Ini</div>
          </div>
          <div class="card-value">{{ number_format($todayVisitors) }}</div>
          <div class="card-meta neutral">{{ now()->format('d M Y') }}</div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="card-label">Kunjungan Bulan Ini</div>
          </div>
          <div class="card-value">{{ number_format($monthVisitors) }}</div>
          <div class="card-meta neutral">{{ now()->translatedFormat('F Y') }}</div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-icon"><i class="fas fa-building"></i></div>
            <div class="card-label">Total Instansi</div>
          </div>
          <div class="card-value">{{ number_format($totalInstitutions) }}</div>
          <div class="card-meta neutral">Instansi unik</div>
        </div>
      </div>

      <div class="charts">
        <div class="chart-card">
          <div class="chart-header">
            <div class="chart-title">
              <i class="fas fa-chart-line"></i>
              <h4>Tren Kunjungan</h4>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="chartVisits"></canvas>
          </div>
        </div>

        <div class="chart-card">
          <div class="chart-header">
            <div class="chart-title">
              <i class="fas fa-chart-pie"></i>
              <h4>Keperluan</h4>
            </div>
          </div>
          <div class="chart-container">
            <canvas id="chartPurpose"></canvas>
          </div>
        </div>
      </div>

      <div class="table-card">
        <div class="table-header">
          <div class="table-title">
            <i class="fas fa-clock"></i>
            <h4>Kunjungan Terbaru</h4>
          </div>
          <a href="{{ route('admin.guests') }}" class="table-link">Lihat semua →</a>
        </div>

        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th width="50">No</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>No. HP</th>
                <th>Keperluan</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($latestVisitors as $index => $visitor)
                @php
                  $words    = explode(' ', trim($visitor->full_name));
                  $initials = strtoupper(substr($words[0] ?? '', 0, 1))
                            . (isset($words[1]) ? strtoupper(substr($words[1], 0, 1)) : '');
                @endphp
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>
                    <div class="guest-name">
                      <div class="guest-avatar">{{ $initials }}</div>
                      <span>{{ $visitor->full_name }}</span>
                    </div>
                  </td>
                  <td>{{ $visitor->institution ?? '-' }}</td>
                  <td>{{ $visitor->phone_number ?? '-' }}</td>
                  <td><span class="purpose-badge">{{ ucfirst($visitor->purpose) }}</span></td>
                  <td>{{ $visitor->created_at->format('d M Y') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="empty-td">
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
      </div>
    </div>

    @include('admin.layouts.footer')
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

  .grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 20px;
    min-width: 0;
  }

  .card,
  .chart-card,
  .table-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-soft);
    min-width: 0;
  }

  .card {
    padding: 18px;
    overflow: hidden;
  }

  .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    min-width: 0;
  }

  .card-icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: var(--pink-light);
    color: var(--pink);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .card-label {
    font-size: 13px;
    color: var(--gray);
    font-weight: 500;
  }

  .card-value {
    font-size: 30px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 8px;
    color: var(--dark);
  }

  .card-meta { font-size: 12px; font-weight: 500; }
  .card-meta.positive { color: #10b981; }
  .card-meta.negative { color: #ef4444; }
  .card-meta.neutral { color: var(--gray-light); }

  .charts {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
    gap: 16px;
    margin-bottom: 20px;
    min-width: 0;
  }

  .charts > * { min-width: 0; }

  .chart-card,
  .table-card { padding: 20px; }

  .chart-card { overflow: hidden; }

  .chart-header,
  .table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    min-width: 0;
  }

  .chart-title,
  .table-title {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
  }

  .chart-title i,
  .table-title i { color: var(--pink); flex-shrink: 0; }

  .chart-title h4,
  .table-title h4 { font-size: 15px; font-weight: 600; color: var(--dark); }

  .chart-container {
    height: 260px;
    position: relative;
    width: 100%;
    min-width: 0;
    overflow: hidden;
  }

  .charts .chart-card:last-child .chart-container { height: 300px; }

  .chart-container canvas {
    display: block;
    width: 100% !important;
    max-width: 100% !important;
    height: 100% !important;
  }

  .table-link {
    font-size: 13px;
    color: var(--pink);
    font-weight: 600;
  }

  .table-responsive {
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 103, 154, 0.35) rgba(255, 103, 154, 0.06);
  }

  .table-responsive::-webkit-scrollbar { height: 5px; }
  .table-responsive::-webkit-scrollbar-track {
    background: rgba(255, 103, 154, 0.06);
    border-radius: 999px;
    margin: 0 8px;
  }
  .table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, #ff679a, #e05588);
    border-radius: 999px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 640px;
  }

  th {
    text-align: left;
    padding: 13px 12px;
    color: var(--gray-light);
    font-weight: 600;
    border-bottom: 1px solid rgba(255, 103, 154, 0.18);
  }

  td {
    padding: 14px 12px;
    border-bottom: 1px solid rgba(255, 103, 154, 0.08);
    color: var(--dark);
    vertical-align: middle;
  }

  tbody tr:hover { background: var(--pink-soft); }

  .guest-name { display: flex; align-items: center; gap: 10px; }
  .guest-name span { font-weight: 500; white-space: nowrap; }

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

  .empty-td {
    text-align: center !important;
    padding: 0 !important;
    border-bottom: none !important;
  }

  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 20px;
    color: var(--gray-light);
    width: 100%;
  }

  .empty-state i { font-size: 36px; margin-bottom: 10px; opacity: 0.4; }
  .empty-state p { font-size: 14px; margin: 0; }

  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(38, 26, 33, 0.28);
    z-index: 90;
    display: none;
  }

  .overlay.show { display: block; }

  @media (max-width: 1100px) {
    .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .charts { grid-template-columns: 1fr; }
    .charts .chart-card:last-child .chart-container { height: 280px; }
  }

  @media (max-width: 768px) {
    .content { padding: 18px; }
    .grid { grid-template-columns: 1fr; }
  }

  @media (max-width: 560px) {
    .content { padding: 14px; }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
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

  document.addEventListener('DOMContentLoaded', function () {
    // Tampilkan toast personal dari session
    @if(session('welcome_message'))
      showToast('{{ session('welcome_message') }}', 'success');
    @elseif(session('login_success'))
      showToast('Selamat datang! Anda berhasil login.', 'success');
    @endif

    const visitsCanvas  = document.getElementById('chartVisits');
    const purposeCanvas = document.getElementById('chartPurpose');

    let visitsChart  = null;
    let purposeChart = null;
    let resizeTimer  = null;

    const purposeLabels = {
      coordination : 'Koordinasi',
      audience     : 'Audiensi',
      monitoring   : 'Monitoring',
      meeting      : 'Rapat',
      visit        : 'Ketemu',
      other        : 'Lainnya'
    };

    function resizeCharts(delay = 320) {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (visitsChart)  { visitsChart.resize();  visitsChart.update('none');  }
        if (purposeChart) { purposeChart.resize(); purposeChart.update('none'); }
      }, delay);
    }

    if (visitsCanvas) {
      visitsChart = new Chart(visitsCanvas.getContext('2d'), {
        type: 'line',
        data: {
          labels: {!! $weeklyData->pluck('label')->toJson() !!},
          datasets: [{
            label: 'Kunjungan',
            data: {!! $weeklyData->pluck('count')->toJson() !!},
            borderColor: '#ff679a',
            backgroundColor: 'rgba(255, 103, 154, 0.12)',
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#ff679a',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: {
              grid: { display: false },
              ticks: { color: '#8d7d84' }
            },
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(255, 103, 154, 0.08)' },
              ticks: { color: '#8d7d84' }
            }
          }
        }
      });
    }

    if (purposeCanvas) {
      const rawPurpose = {!! $purposeData->toJson() !!};
      const pLabels    = rawPurpose.map(p => purposeLabels[p.purpose] ?? p.purpose);
      const pData      = rawPurpose.map(p => p.total);

      purposeChart = new Chart(purposeCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: pLabels,
          datasets: [{
            data: pData,
            backgroundColor: ['#ff679a', '#ff8eb5', '#ffb5d0', '#ffdbe7', '#e05588', '#ffcce0'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '68%',
          layout: { padding: 8 },
          plugins: {
            legend: {
              position: 'bottom',
              align: 'center',
              labels: {
                boxWidth: 12,
                color: '#6c5d63',
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 14,
                font: { family: 'Poppins', size: 12 }
              }
            }
          }
        }
      });
    }

    window.addEventListener('resize', () => resizeCharts(150));
    resizeCharts(100);
  });
</script>
@endpush
