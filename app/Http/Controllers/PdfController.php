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
        $jenis = $request->input('jenis');
        $tipe = $request->input('tipe');
        
        $params = [
            'tipe' => $tipe,
            'jenis' => $jenis,
        ];

        if ($jenis == 'laporan') {
            $this->handleLaporan($tipe, $request, $params);
        } elseif ($jenis == 'tunggakan') {
            $this->handleTunggakan($tipe, $request, $params);
        } elseif ($jenis == 'laporan_singkat') {
            $this->laporanSingkat($tipe, $request, $params);
        }
        $pdf = Pdf::loadView('laporan', $params);
        // $options = [
        //     'margin_top' => 0,
        //     'margin_right' => 20,
        //     'margin_bottom' => 0,
        //     'margin_left' => 20,
        // ];

        // $pdf->setOptions($options);
        $pdf->setPaper('a4', 'landscape');

        $namaFile = 'Laporan_Iuran.pdf';

        return $pdf->stream($namaFile);
    }

    private function laporanSingkat($tipe, Request $request, &$params)
    {
        if($tipe == 'harian'){
            $tanggal = $request->input('tanggal');
            $params['tanggal'] = $tanggal;
            $params['iurans'] = Iuran::with('siswa')->whereDate('tanggal', $tanggal)->get();
            $params['setorans'] = Setoran::with('tabungan.siswa')->whereDate('tanggal', $tanggal)->get();
            $params['setorandaftarulangs'] = SetoranDaftarUlang::with('daftarUlang.siswa')->whereDate('tanggal', $tanggal)->get();
        } elseif($tipe == 'bulanan'){
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $params['bulan'] = $bulan;
            $params['tahun'] = $tahun;
            $params['iurans'] = Iuran::with('siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
            $params['setorans'] = Setoran::with('tabungan.siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
            $params['setorandaftarulangs'] = SetoranDaftarUlang::with('daftarUlang.siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        } elseif($tipe == 'tahunan'){
            $tahun = $request->input('tahun');
            $params['tahun'] = $tahun;
            $params['iurans'] = Iuran::with('siswa')->whereYear('tanggal', $tahun)->get();
            $params['setorans'] = Setoran::with('tabungan.siswa')->whereYear('tanggal', $tahun)->get();
            $params['setorandaftarulangs'] = SetoranDaftarUlang::with('daftarUlang.siswa')->whereYear('tanggal', $tahun)->get();
        }
    }
    private function handleLaporan($tipe, Request $request, &$params)
    {
        if ($tipe == 'harian') {
            $tanggal = $request->input('tanggal');
            $params['tanggal'] = $tanggal;
    
            $params['iuransCash'] = Iuran::with('siswa')
                ->where('pembayaran', 'Cash')
                ->whereDate('tanggal', $tanggal)
                ->get();
            $params['iuransTransfer'] = Iuran::with('siswa')
                ->where('pembayaran', 'Transfer')
                ->whereDate('tanggal', $tanggal)
                ->get();
            $params['setoransCash'] = Setoran::with('tabungan.siswa')
                ->where('pembayaran', 'Cash')
                ->whereDate('tanggal', $tanggal)
                ->get();
            $params['setoransTransfer'] = Setoran::with('tabungan.siswa')
                ->where('pembayaran', 'Transfer')
                ->whereDate('tanggal', $tanggal)
                ->get();
            $params['setorandaftarulangCash'] = SetoranDaftarUlang::with('daftarUlang.siswa')
                ->where('pembayaran', 'Cash')
                ->whereDate('tanggal', $tanggal)
                ->get();
            $params['setorandaftarulangTransfer'] = SetoranDaftarUlang::with('daftarUlang.siswa')
                ->where('pembayaran', 'Transfer')
                ->whereDate('tanggal', $tanggal)
                ->get();
        } elseif ($tipe == 'bulanan') {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $params['bulan'] = $bulan;
            $params['tahun'] = $tahun;
    
            $params['iuransCash'] = Iuran::with('siswa')
                ->where('pembayaran', 'Cash')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['iuransTransfer'] = Iuran::with('siswa')
                ->where('pembayaran', 'Transfer')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setoransCash'] = Setoran::with('tabungan.siswa')
                ->where('pembayaran', 'Cash')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setoransTransfer'] = Setoran::with('tabungan.siswa')
                ->where('pembayaran', 'Transfer')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setorandaftarulangCash'] = SetoranDaftarUlang::with('daftarUlang.siswa')
                ->where('pembayaran', 'Cash')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setorandaftarulangTransfer'] = SetoranDaftarUlang::with('daftarUlang.siswa')
                ->where('pembayaran', 'Transfer')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
        } elseif ($tipe == 'tahunan') {
            $tahun = $request->input('tahun');
            $params['tahun'] = $tahun;
    
            $params['iuransCash'] = Iuran::with('siswa')
                ->where('pembayaran', 'Cash')
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['iuransTransfer'] = Iuran::with('siswa')
                ->where('pembayaran', 'Transfer')
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setoransCash'] = Setoran::with('tabungan.siswa')
                ->where('pembayaran', 'Cash')
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setoransTransfer'] = Setoran::with('tabungan.siswa')
                ->where('pembayaran', 'Transfer')
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setorandaftarulangCash'] = SetoranDaftarUlang::with('daftarUlang.siswa')
                ->where('pembayaran', 'Cash')
                ->whereYear('tanggal', $tahun)
                ->get();
            $params['setorandaftarulangTransfer'] = SetoranDaftarUlang::with('daftarUlang.siswa')
                ->where('pembayaran', 'Transfer')
                ->whereYear('tanggal', $tahun)
                ->get();
        }
    }
    
    private function handleTunggakan($tipe, Request $request, &$params)
    {
        if ($tipe == 'harian') {
            $tanggal = $request->input('tanggal');
            $params['tanggal'] = $tanggal;

            $params['iurans'] = Iuran::with('siswa')->whereDate('tanggal', $tanggal)->where('status', '0')->get();
            $params['daftarUlangs'] = DaftarUlang::with('siswa')->whereDate('tanggal', $tanggal)->where('status', '0')->get();
        } elseif ($tipe == 'bulanan') {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $params['bulan'] = $bulan;
            $params['tahun'] = $tahun;

            $params['iurans'] = Iuran::with('siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', '0')->get();
            $params['daftarUlangs'] = DaftarUlang::with('siswa')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', '0')->get();
        } elseif ($tipe == 'tahunan') {
            $tahun = $request->input('tahun');
            $params['tahun'] = $tahun;

            $params['iurans'] = Iuran::with('siswa')->whereYear('tanggal', $tahun)->where('status', '0')->get();
            $params['daftarUlangs'] = DaftarUlang::with('siswa')->whereYear('tanggal', $tahun)->where('status', '0')->get();
        }
    }

    private function getIuran($pembayaran, $date, $dateType, $period)
    {
        return Iuran::with('siswa')
            ->where('pembayaran', $pembayaran)
            ->{$this->getDateMethod($dateType)}('tanggal', ...$this->getDateArguments($date, $period))
            ->get();
    }

    private function getSetoran($pembayaran, $date, $dateType, $period)
    {
        return Setoran::with(['tabungan.siswa'])
            ->where('pembayaran', $pembayaran)  // Ensure 'pembayaran' is treated as a string
            ->{$this->getDateMethod($dateType)}('tanggal', ...$this->getDateArguments($date, $period))
            ->get();
    }

    private function getSetoranDaftarUlang($pembayaran, $date, $dateType, $period)
    {
        return SetoranDaftarUlang::with(['daftarUlang.siswa'])
            ->where('pembayaran', $pembayaran)  // Ensure 'pembayaran' is treated as a string
            ->{$this->getDateMethod($dateType)}('tanggal', ...$this->getDateArguments($date, $period))
            ->get();
    }

    private function getDateMethod($type)
    {
        return [
            'tanggal' => 'whereDate',
            'bulan' => 'whereMonth',  // Handle only the month here
            'tahun' => 'whereYear',
        ][$type];
    }

    private function getDateArguments($date, $period)
    {
        // Split month and year if needed
        return is_array($date) ? $date : [$date];
    }
}
