<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Setoran;
use App\Models\DaftarUlang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SetoranDaftarUlang;

class PdfController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->jenis;
        $tipe = $request->tipe;
        
        $params = [
            'tipe' => $tipe,
            'jenis' => $jenis,
        ];
        
        if($jenis == 'laporan') {
            if($tipe == 'harian') {
                $tanggal = $request->tanggal;
                $iurans = Iuran::with('siswa')->whereDate('tanggal', $tanggal)->get();
                $setorans = Setoran::with(['tabungan.siswa'])->whereDate('tanggal', $tanggal)->get();
                $setorandaftarulang = SetoranDaftarUlang::with(['daftarUlang.siswa'])->whereDate('tanggal', $tanggal)->get();
                $params['tanggal'] = $tanggal;
            } elseif($tipe == 'bulanan') {
                $bulan = $request->bulan;
                $tahun = $request->tahun;
                $iurans = Iuran::with('siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
                $setorans = Setoran::with(['tabungan.siswa'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
                $setorandaftarulang = SetoranDaftarUlang::with(['daftarUlang.siswa'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
                $params['bulan'] = $bulan;
                $params['tahun'] = $tahun;
            } elseif($tipe == 'tahunan') {
                $tahun = $request->tahun;
                $iurans = Iuran::with('siswa')->whereYear('tanggal', $tahun)->get();
                $setorans = Setoran::with(['tabungan.siswa'])->whereYear('tanggal', $tahun)->get();
                $setorandaftarulang = SetoranDaftarUlang::with(['daftarUlang.siswa'])->whereYear('tanggal', $tahun)->get();
                $params['tahun'] = $tahun;
            }

            $params['iurans'] = $iurans;
            $params['setorans'] = $setorans;
            $params['setorandaftarulang'] = $setorandaftarulang;
        } elseif ($jenis == 'tunggakan') {

            if($tipe == 'harian') {
                $tanggal = $request->tanggal;
                $iurans = Iuran::with('siswa')->whereDate('tanggal', $tanggal)->where('status', '0')->get();
                $daftarulang = DaftarUlang::with(['daftarUlang.siswa'])->whereDate('tanggal', $tanggal)->where('status', '0')->get();
                $params['tanggal'] = $tanggal;
            } elseif($tipe == 'bulanan') {
                $bulan = $request->bulan;
                $tahun = $request->tahun;
                $iurans = Iuran::with('siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', '0')->get();
                $daftarulang = DaftarUlang::with(['daftarUlang.siswa'])->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', '0')->get();
                $params['bulan'] = $bulan;
                $params['tahun'] = $tahun;
            } elseif($tipe == 'tahunan') {
                $tahun = $request->tahun;
                $iurans = Iuran::with('siswa')->whereYear('tanggal', $tahun)->where('status', '0')->get();
                $daftarulang = DaftarUlang::with(['daftarUlang.siswa'])->whereYear('tanggal', $tahun)->where('status', '0')->get();
                $params['tahun'] = $tahun;
            }

            $params['iurans'] = $iurans;
            $params['setorandaftarulang'] = $daftarulang;
        }


        $pdf = Pdf::loadView('laporan-harian', $params);

        $options = [
            'margin_top' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'margin_left' => 20,
        ];

        $pdf->setOptions($options, true); ;
        $pdf->setPaper('a4', 'landscape');

        $namaFile = 'Laporan_Iuran_' . $tanggal . '.pdf';

        ob_end_clean();
        ob_start();

        return $pdf->stream($namaFile);
    }
}
