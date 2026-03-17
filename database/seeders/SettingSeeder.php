<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'app_name'          => 'Buku Tamu Digital',
            'institution_name'  => 'Jember Command Center',
            'institution_short' => 'JCC',
            'year'              => '2026',
            'address'           => 'Jl. Sudarman No. 1, Jember, Jawa Timur',
            'description'       => 'Sistem buku tamu digital Jember Command Center untuk mencatat dan mengelola data kunjungan tamu.',
            'logo'              => 'setting/jcc.png',
            'favicon'           => 'setting/jcc.png',
            'qr_url'            => 'http://192.168.1.8:8000/beranda',
            'qr_path'           => null,
            'scan_count'        => 0,
        ]);
    }
}
