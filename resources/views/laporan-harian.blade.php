<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Laporan Harian - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        h3 {
            margin: 2;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    @stack('style')
</head>

<body>
    <table width="100%" border="0" cellpadding="2.5" cellspacing="0">
        <tbody>
            <tr>
                <td width='20%'>
                    <img width='120px'
                        src="{{ generateBase64Image('storage/pengaturan/' . basename(getPengaturan()->logo)) }}"
                        alt="">
                </td>
                <td align="center">
                    <h3>Laporan Harian</h3>
                    <h3>Pemasukan dan Pengeluaran</h3>
                    <h3>{{ getPengaturan()->nama }}</h3>
                    <div>
                        <span>
                            Komplek Pesantren Condong, Setianagara, Kec. Cibeureum, Kab. Tasikmalaya, Jawa Barat 46196
                        </span>
                    </div>
                </td>
                <td width='20%' align="center">

                </td>
            </tr>
        </tbody>
    </table>
    <hr style="height:1px;background-color:black;">

    <div>
        <center>
            <h3><u>Data Pemasukan dan Pengeluaran {{ $tanggal }}</u></h3>
        </center>
        <br>
        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Siswa</th>
                    <th width="20%">Jenis</th>
                    <th width="20%">Pemasukan</th>
                    <th width="20%">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $totalPemasukan = 0;
                    $totalPengeluaran = 0;
                @endphp
                @foreach ($setorandaftarulang as $daful)
                    @php
                        $totalPemasukan += $daful->nominal;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $daful->daftarulang->siswa->nama }}</td>
                        <td>Daftar Ulang</td>
                        <td style="text-align: right;">{{ formatRupiah($daful->nominal) }}</td>
                        <td>-</td>
                    </tr>
                @endforeach
                @foreach ($setorans as $setoran)
                    <tr>
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $setoran->tabungan->siswa->nama }}</td>
                        <td>Tabungan</td>
                        @if ($setoran->transaksi == 'Pemasukan')
                            @php
                                $totalPemasukan += $setoran->nominal;
                            @endphp
                            <td style="text-align: right;">{{ formatRupiah($setoran->nominal) }}</td>
                            <td>-</td>
                        @else
                            @php
                                $totalPengeluaran += $setoran->nominal;
                            @endphp
                            <td>-</td>
                            <td style="text-align: right;">{{ formatRupiah($setoran->nominal) }}</td>
                        @endif
                @endforeach
                @forelse ($iurans as $iuran)
                    @php
                        $pemasukan = $iuran->syahriyah + $iuran->field_trip + $iuran->uang_makan;
                        $totalPemasukan += $pemasukan;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $iuran->siswa->nama }}</td>
                        <td>Iuran</td>
                        <td style="text-align: right;">{{ formatRupiah($pemasukan) }}</td>
                        <td>-</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center">Laporan Harian Kosong</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="3" align="right"><strong>Jumlah</strong></td>
                    <td style="text-align: right;"><strong>{{ formatRupiah($totalPemasukan) }}</strong></td>
                    <td style="text-align: right;"><strong>{{ formatRupiah($totalPengeluaran) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" align="right"><strong>Total</strong></td>
                    <td style="text-align: right;" colspan="2">
                        <strong>{{ formatRupiah($totalPemasukan - $totalPengeluaran) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
