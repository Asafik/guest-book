<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class GuestController extends Controller
{


    private function buildGuestQuery(Request $request)
    {
        $search    = $request->input('search');
        $sort      = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['full_name', 'institution', 'purpose', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $direction = $direction === 'asc' ? 'asc' : 'desc';

        return Visitor::query()
            ->when($search, function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction);
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        
        $guests = $this->buildGuestQuery($request)
            ->paginate($perPage)
            ->appends($request->only(['search', 'per_page', 'sort', 'direction']));

        return view('admin.guest', compact('guests'));
    }

    public function exportExcel(Request $request)
    {
        $guests = $this->buildGuestQuery($request)->get();

        $fileName = 'Data_Kunjungan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $writer = SimpleExcelWriter::streamDownload($fileName);
        
        $counter = 1;
        foreach ($guests as $guest) {
            $writer->addRow([
                'No' => $counter++,
                'Nama Lengkap' => $guest->full_name,
                'Instansi' => $guest->institution ?? '-',
                'No. HP' => $guest->phone_number ?? '-',
                'Keperluan' => ucfirst($guest->purpose),
                'Bertemu Dengan' => $guest->meet_with ?? '-',
                'Catatan' => $guest->notes ?? '-',
                'Tanggal' => $guest->created_at->format('d/m/Y H:i:s'),
            ]);
        }

        return $writer->toBrowser();
    }

    public function exportPdf(Request $request)
    {
        $guests = $this->buildGuestQuery($request)->get();

        $pdf = Pdf::loadView('admin.pdf.guest', compact('guests'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('Data_Kunjungan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function destroy($id)
    {
        $guest = Visitor::findOrFail($id);

        if ($guest->photo) {
            \Storage::disk('public')->delete($guest->photo);
        }

        $guest->delete();

        return response()->json(['success' => true]);
    }
}
