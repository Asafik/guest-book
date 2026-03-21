<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan Tamu</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Data Kunjungan Tamu</h2>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Nama Lengkap</th>
                <th width="15%">Instansi</th>
                <th width="12%">No. HP</th>
                <th width="12%">Keperluan</th>
                <th width="15%">Bertemu Dengan</th>
                <th width="14%">Catatan</th>
                <th width="12%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $index => $guest)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $guest->full_name }}</td>
                <td>{{ $guest->institution ?? '-' }}</td>
                <td>{{ $guest->phone_number ?? '-' }}</td>
                <td>{{ ucfirst($guest->purpose) }}</td>
                <td>{{ $guest->meet_with ?? '-' }}</td>
                <td>{{ $guest->notes ?? '-' }}</td>
                <td>{{ $guest->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
