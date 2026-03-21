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
<link rel="stylesheet" href="{{ asset('admin/css/dashboard.css') }}">
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
