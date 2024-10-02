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

        $options = [
            'margin_top' => 20,
            'margin_right' => 20,
            'margin_bottom' => 20,
            'margin_left' => 20,
        ];

        $pdf->setOptions($options);
        $pdf->setPaper('a4', 'landscape');

        $namaFile = 'Laporan_Iuran.pdf';

        return $pdf->stream($namaFile);
    }

    private function laporanSingkat($tipe, Request $request, &$params){
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

            $params['iuransCash'] = $this->getIuran('Cash', $tanggal, 'tanggal', 'harian');
            $params['iuransTransfer'] = $this->getIuran('Transfer', $tanggal, 'tanggal', 'harian');
            $params['setoransCash'] = $this->getSetoran('Cash', $tanggal, 'tanggal', 'harian');
            $params['setoransTransfer'] = $this->getSetoran('Transfer', $tanggal, 'tanggal', 'harian');
            $params['setorandaftarulangCash'] = $this->getSetoranDaftarUlang('Cash', $tanggal, 'tanggal', 'harian');
            $params['setorandaftarulangTransfer'] = $this->getSetoranDaftarUlang('Transfer', $tanggal, 'tanggal', 'harian');
        } elseif ($tipe == 'bulanan') {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $params['bulan'] = $bulan;
            $params['tahun'] = $tahun;

            $params['iuransCash'] = $this->getIuran('Cash', [$bulan, $tahun], 'bulan', 'bulanan');
            $params['iuransTransfer'] = $this->getIuran('Transfer', [$bulan, $tahun], 'bulan', 'bulanan');
            $params['setoransCash'] = $this->getSetoran('Cash', [$bulan, $tahun], 'bulan', 'bulanan');
            $params['setoransTransfer'] = $this->getSetoran('Transfer', [$bulan, $tahun], 'bulan', 'bulanan');
            $params['setorandaftarulangCash'] = $this->getSetoranDaftarUlang('Cash', [$bulan, $tahun], 'bulan', 'bulanan');
            $params['setorandaftarulangTransfer'] = $this->getSetoranDaftarUlang('Transfer', [$bulan, $tahun], 'bulan', 'bulanan');
        } elseif ($tipe == 'tahunan') {
            $tahun = $request->input('tahun');
            $params['tahun'] = $tahun;

            $params['iuransCash'] = $this->getIuran('Cash', $tahun, 'tahun', 'tahunan');
            $params['iuransTransfer'] = $this->getIuran('Transfer', $tahun, 'tahun', 'tahunan');
            $params['setoransCash'] = $this->getSetoran('Cash', $tahun, 'tahun', 'tahunan');
            $params['setoransTransfer'] = $this->getSetoran('Transfer', $tahun, 'tahun', 'tahunan');
            $params['setorandaftarulangCash'] = $this->getSetoranDaftarUlang('Cash', $tahun, 'tahun', 'tahunan');
            $params['setorandaftarulangTransfer'] = $this->getSetoranDaftarUlang('Transfer', $tahun, 'tahun', 'tahunan');
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
            ->where('pembayaran', $pembayaran)
            ->{$this->getDateMethod($dateType)}('tanggal', ...$this->getDateArguments($date, $period))
            ->get();
    }

    private function getSetoranDaftarUlang($pembayaran, $date, $dateType, $period)
    {
        return SetoranDaftarUlang::with(['daftarUlang.siswa'])
            ->where('pembayaran', $pembayaran)
            ->{$this->getDateMethod($dateType)}('tanggal', ...$this->getDateArguments($date, $period))
            ->get();
    }

    private function getDateMethod($type)
    {
        return [
            'tanggal' => 'whereDate',
            'bulan' => 'whereMonth',
            'tahun' => 'whereYear',
        ][$type];
    }

    private function getDateArguments($date, $period)
    {
        return is_array($date) ? $date : [$date];
    }
}
