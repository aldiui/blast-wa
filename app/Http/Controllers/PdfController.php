<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Setoran;
use App\Models\SetoranDaftarUlang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari request yang dikirim oleh tombol Filament
        $tanggal = $request->input('tanggal');

        if (!$tanggal) {
            return redirect()->back()->withErrors(['message' => 'Tanggal harus disertakan']);
        }

        // Query Iuran berdasarkan tanggal
        $iurans = Iuran::with('siswa')->whereDate('tanggal', $tanggal)->get();
        $setorans = Setoran::with(['tabungan.siswa'])->whereDate('tanggal', $tanggal)->get();
        $setorandaftarulang = SetoranDaftarUlang::with(['daftarUlang.siswa'])->whereDate('tanggal', $tanggal)->get();

        $pdf = Pdf::loadView('laporan-harian', compact('iurans', 'tanggal', 'setorans', 'setorandaftarulang'));

        // Set opsi tambahan untuk PDF
        $options = [
            'margin_top' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'margin_left' => 20,
        ];

        $pdf->setOptions($options);
        $pdf->setPaper('a4', 'landscape');

        // Nama file yang akan dihasilkan
        $namaFile = 'Laporan_Iuran_' . $tanggal . '.pdf';

        // Membersihkan output buffer sebelum stream
        ob_end_clean();
        ob_start();

        // Stream file PDF
        return $pdf->stream($namaFile);
    }
}
