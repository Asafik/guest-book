<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function index()
    {
        return view('visitor');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'    => ['required', 'string', 'max:255'],
            'address'      => ['nullable', 'string'],
            'institution'  => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'purpose'      => ['required', 'string', 'max:100'],
            'meet_with'    => ['nullable', 'string', 'max:255'],
            'notes'        => ['nullable', 'string'],
        ]);

         Visitor::create([
             'full_name'    => $validated['full_name'],
             'address'      => $validated['address'] ?? null,
             'institution'  => $validated['institution'] ?? null,
             'phone_number' => $validated['phone_number'] ?? null,
             'purpose'      => $validated['purpose'],
             'meet_with'    => $validated['meet_with'] ?? null,
             'notes'        => $validated['notes'] ?? null,
         ]);


        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil disimpan.',
        ]);
    }

    public function ocrKtp(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
        ]);

        $processedPath = null;

        try {
            $file = $request->file('photo');
            $imagePath = $file?->getRealPath();

            if (!$imagePath || !file_exists($imagePath)) {
                throw new \Exception('File gambar tidak ditemukan.');
            }

            $processedDir = storage_path('app/ocr-temp');

            if (!is_dir($processedDir)) {
                mkdir($processedDir, 0777, true);
            }

            $processedPath = $processedDir . DIRECTORY_SEPARATOR . 'ktp_' . uniqid('', true) . '.jpg';

            $binary = file_get_contents($imagePath);
            if ($binary === false) {
                throw new \Exception('Gagal membaca file upload.');
            }

            $image = @imagecreatefromstring($binary);
            if (!$image) {
                throw new \Exception('Format gambar tidak valid atau tidak bisa diproses.');
            }

            imagefilter($image, IMG_FILTER_GRAYSCALE);
            imagefilter($image, IMG_FILTER_CONTRAST, -20);
            imagefilter($image, IMG_FILTER_BRIGHTNESS, 10);

            if (!imagejpeg($image, $processedPath, 95)) {
                imagedestroy($image);
                throw new \Exception('Gagal membuat file sementara untuk OCR.');
            }

            imagedestroy($image);

            $tesseract = 'C:/Program Files/Tesseract-OCR/tesseract.exe';
            $tessdata  = 'C:/Program Files/Tesseract-OCR/tessdata';

            if (!file_exists($tesseract)) {
                throw new \Exception('File tesseract.exe tidak ditemukan di C:/Program Files/Tesseract-OCR/');
            }

            if (!is_dir($tessdata)) {
                throw new \Exception('Folder tessdata tidak ditemukan.');
            }

            $command = '"' . $tesseract . '" '
                . escapeshellarg($processedPath) . ' stdout '
                . '--tessdata-dir "' . $tessdata . '" '
                . '-l ind+eng quiet 2>&1';

            exec($command, $output, $exitCode);

            $text = trim(implode("\n", $output));

            logger()->info('OCR RESULT', [
                'image_path'     => $imagePath,
                'processed_path' => $processedPath,
                'exit_code'      => $exitCode,
                'raw_text'       => $text,
            ]);

            if ($exitCode !== 0) {
                throw new \Exception('OCR gagal diproses: ' . $text);
            }

            if ($text === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Teks KTP tidak terbaca. Coba foto ulang dengan pencahayaan lebih terang.',
                    'data'    => [
                        'full_name' => null,
                        'address'   => null,
                        'rt_rw'     => null,
                        'kel_desa'  => null,
                        'kecamatan' => null,
                        'raw_text'  => '',
                    ],
                ]);
            }

            $data = $this->extractKtpData($text);

            $hasFullData = !empty($data['full_name']) && (
                !empty($data['address']) ||
                !empty($data['rt_rw']) ||
                !empty($data['kel_desa']) ||
                !empty($data['kecamatan'])
            );

            if ($hasFullData) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nama dan alamat berhasil diisi otomatis.',
                    'data'    => [
                        'full_name' => $data['full_name'],
                        'address'   => $data['address'],
                        'rt_rw'     => $data['rt_rw'],
                        'kel_desa'  => $data['kel_desa'],
                        'kecamatan' => $data['kecamatan'],
                        'raw_text'  => $text,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Nama dan alamat belum terbaca lengkap. Silakan arahkan ulang KTP.',
                'data'    => [
                    'full_name' => $data['full_name'],
                    'address'   => $data['address'],
                    'rt_rw'     => $data['rt_rw'],
                    'kel_desa'  => $data['kel_desa'],
                    'kecamatan' => $data['kecamatan'],
                    'raw_text'  => $text,
                ],
            ]);
        } catch (\Throwable $e) {
            logger()->error('OCR ERROR', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca KTP: ' . $e->getMessage(),
                'data'    => [
                    'full_name' => null,
                    'address'   => null,
                    'rt_rw'     => null,
                    'kel_desa'  => null,
                    'kecamatan' => null,
                    'raw_text'  => null,
                ],
            ], 500);
        } finally {
            if ($processedPath && file_exists($processedPath)) {
                @unlink($processedPath);
            }
        }
    }

    private function extractKtpData(string $text): array
    {
        $normalizedText = strtoupper($text ?? '');
        $normalizedText = preg_replace('/[ \t]+/', ' ', $normalizedText);

        $lines = preg_split('/\r\n|\r|\n/', $normalizedText);
        $lines = array_values(array_filter(array_map(function ($line) {
            return trim($line);
        }, $lines)));

        $fullName = null;
        $address = null;
        $rtRw = null;
        $kelDesa = null;
        $kecamatan = null;

        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);

            // AMBIL NAMA
            if (!$fullName) {
                if (preg_match('/^NAMA\s*[:=]?\s*(.*)$/i', $line, $m)) {
                    $candidate = trim($m[1] ?? '');

                    if ($candidate === '' && isset($lines[$i + 1])) {
                        $candidate = trim($lines[$i + 1]);
                    }

                    if ($this->isValidNameCandidate($candidate)) {
                        $fullName = $candidate;
                    }
                }
            }

            // AMBIL ALAMAT UTAMA
            if (!$address) {
                if (preg_match('/^ALAMAT\s*[:=]?\s*(.*)$/i', $line, $m)) {
                    $candidate = trim($m[1] ?? '');

                    if ($candidate === '' && isset($lines[$i + 1])) {
                        $candidate = trim($lines[$i + 1]);
                    }

                    $candidate = trim($candidate);

                    if ($this->isValidAddressCandidate($candidate)) {
                        $address = $candidate;
                    }
                }
            }

            // AMBIL RT/RW
            if (!$rtRw) {
                if (preg_match('/^(RT\/RW|RTRW)\s*[:=]?\s*(.*)$/i', $line, $m)) {
                    $candidate = trim($m[2] ?? '');

                    if ($candidate === '' && isset($lines[$i + 1])) {
                        $candidate = trim($lines[$i + 1]);
                    }

                    $candidate = $this->normalizeRtRw($candidate);

                    if ($candidate !== '') {
                        $rtRw = $candidate;
                    }
                }
            }

            // AMBIL KEL/DESA
            if (!$kelDesa) {
                if (preg_match('/^(KEL\/DESA|KELURAHAN|DESA)\s*[:=]?\s*(.*)$/i', $line, $m)) {
                    $candidate = trim($m[2] ?? '');

                    if ($candidate === '' && isset($lines[$i + 1])) {
                        $candidate = trim($lines[$i + 1]);
                    }

                    $candidate = $this->cleanKtpValue($candidate);

                    if ($candidate !== '') {
                        $kelDesa = $candidate;
                    }
                }
            }

            // AMBIL KECAMATAN
            if (!$kecamatan) {
                if (preg_match('/^KECAMATAN\s*[:=]?\s*(.*)$/i', $line, $m)) {
                    $candidate = trim($m[1] ?? '');

                    if ($candidate === '' && isset($lines[$i + 1])) {
                        $candidate = trim($lines[$i + 1]);
                    }

                    $candidate = $this->cleanKtpValue($candidate);

                    if ($candidate !== '') {
                        $kecamatan = $candidate;
                    }
                }
            }
        }

        return [
            'full_name' => $fullName ? $this->cleanKtpValue($fullName) : null,
            'address'   => $address ? $this->cleanKtpValue($address) : null,
            'rt_rw'     => $rtRw ? $this->cleanKtpValue($rtRw) : null,
            'kel_desa'  => $kelDesa ? $this->cleanKtpValue($kelDesa) : null,
            'kecamatan' => $kecamatan ? $this->cleanKtpValue($kecamatan) : null,
        ];
    }

    private function isValidNameCandidate(?string $value): bool
    {
        $value = trim((string) $value);

        if ($value === '') {
            return false;
        }

        if (preg_match('/^\d+$/', $value)) {
            return false;
        }

        if (preg_match('/^(NIK|TEMPAT|TGL|LAHIR|TEMPAT\/TGL|JENIS|KELAMIN|GOL|DARAH|ALAMAT|RT\/RW|RTRW|KEL\/DESA|KELURAHAN|DESA|KECAMATAN|AGAMA|STATUS|PEKERJAAN|KEWARGANEGARAAN|BERLAKU)/i', $value)) {
            return false;
        }

        if (!preg_match('/^[A-Z\s\.\',-]+$/i', $value)) {
            return false;
        }

        if (strlen(preg_replace('/[^A-Z]/i', '', $value)) < 2) {
            return false;
        }

        return true;
    }

    private function isValidAddressCandidate(?string $value): bool
    {
        $value = trim((string) $value);

        if ($value === '') {
            return false;
        }

        if (preg_match('/^(NIK|RT\/RW|RTRW|KEL\/DESA|KELURAHAN|DESA|KECAMATAN)\b/i', $value)) {
            return false;
        }

        if (preg_match('/^\d{10,}$/', preg_replace('/\D/', '', $value))) {
            return false;
        }

        return true;
    }

    private function normalizeRtRw(?string $value): string
    {
        $value = strtoupper(trim((string) $value));
        $value = preg_replace('/\s+/', '', $value);
        $value = str_replace(['\\', '|'], '/', $value);

        if (preg_match('/(\d{1,3})\D+(\d{1,3})/', $value, $m)) {
            return str_pad($m[1], 3, '0', STR_PAD_LEFT) . '/' . str_pad($m[2], 3, '0', STR_PAD_LEFT);
        }

        return $this->cleanKtpValue($value);
    }

    private function cleanKtpValue(string $value): string
    {
        $value = preg_replace('/\s+/', ' ', trim($value));
        $value = preg_replace('/^[\:;\-=]+/', '', $value);
        return trim($value);
    }
}
