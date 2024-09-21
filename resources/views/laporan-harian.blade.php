<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Laporan Harian - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h3 {
            margin: 0.5em 0;
            font-size: 1.2em;
        }

        p {
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            font-size: 0.9em;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            display: block;
            margin: 0 auto;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #000;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
        }
    </style>
    @stack('style')
</head>

<body>
    <table>
        <tr>
            <td width="20%">
                <img width="120" src="{{ generateBase64Image('storage/pengaturan/' . basename(getPengaturan()->logo)) }}" alt="Logo">
            </td>
            <td class="center">
                @if ($jenis == 'laporan')
                    <h3>Laporan {{ ucfirst($tipe) }}</h3>
                    <h3>Pemasukan dan Pengeluaran</h3>
                @else
                    <h3>Tunggakan</h3>
                @endif
                <h3>{{ getPengaturan()->nama }}</h3>
                <p>Komplek Pesantren Condong, Setianagara, Kec. Cibeureum, Kab. Tasikmalaya, Jawa Barat 46196</p>
            </td>
            <td width="20%"></td>
        </tr>
    </table>
    
    <hr>

    @if ($jenis == 'laporan')
        <div class="center">
            <h3><u>Data Pemasukan dan Pengeluaran</u></h3>
            <p>{{ $tipe == 'harian' ? $tanggal : ($tipe == 'bulanan' ? $bulan . ' ' . $tahun : $tahun) }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Jenis</th>
                    <th class="right">Pemasukan</th>
                    <th class="right">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $totalPemasukan = 0;
                    $totalPengeluaran = 0;
                @endphp
                @foreach ($setorandaftarulang as $daful)
                    @php $totalPemasukan += $daful->nominal; @endphp
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $daful->daftarulang->siswa->nama }}</td>
                        <td>Daftar Ulang</td>
                        <td class="right">{{ formatRupiah($daful->nominal) }}</td>
                        <td class="right">-</td>
                    </tr>
                @endforeach
                @foreach ($setorans as $setoran)
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $setoran->tabungan->siswa->nama }}</td>
                        <td>Tabungan</td>
                        @if ($setoran->transaksi == 'Pemasukan')
                            @php $totalPemasukan += $setoran->nominal; @endphp
                            <td class="right">{{ formatRupiah($setoran->nominal) }}</td>
                            <td class="right">-</td>
                        @else
                            @php $totalPengeluaran += $setoran->nominal; @endphp
                            <td class="right">-</td>
                            <td class="right">{{ formatRupiah($setoran->nominal) }}</td>
                        @endif
                    </tr>
                @endforeach
                @foreach ($iurans as $iuran)
                    @php
                        $pemasukan = $iuran->syahriyah + $iuran->field_trip + $iuran->uang_makan;
                        $totalPemasukan += $pemasukan;
                    @endphp
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $iuran->siswa->nama }}</td>
                        <td>Iuran</td>
                        <td class="right">{{ formatRupiah($pemasukan) }}</td>
                        <td class="right">-</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="right"><strong>Jumlah</strong></td>
                    <td class="right"><strong>{{ formatRupiah($totalPemasukan) }}</strong></td>
                    <td class="right"><strong>{{ formatRupiah($totalPengeluaran) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="right"><strong>Total</strong></td>
                    <td colspan="2" class="right"><strong>{{ formatRupiah($totalPemasukan - $totalPengeluaran) }}</strong></td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="center">
            <h3><u>Data Pemasukan dan Pengeluaran {{ $tipe == 'harian' ? $tanggal : ($tipe == 'bulanan' ? $bulan . ' ' . $tahun : $tahun) }}</u></h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Jenis</th>
                    <th class="right">Pemasukan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $totalTunggakan = 0;
                @endphp
                @foreach ($setorandaftarulang as $daful)
                    @php $totalTunggakan += $daful->nominal; @endphp
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $daful->daftarulang->siswa->nama }}</td>
                        <td>Daftar Ulang</td>
                        <td class="right">{{ formatRupiah($daful->nominal) }}</td>
                    </tr>
                @endforeach
                @foreach ($iurans as $iuran)
                    @php
                        $pemasukan = $iuran->syahriyah + $iuran->field_trip + $iuran->uang_makan;
                        $totalTunggakan += $pemasukan;
                    @endphp
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $iuran->siswa->nama }}</td>
                        <td>Iuran</td>
                        <td class="right">{{ formatRupiah($pemasukan) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="right"><strong>Jumlah</strong></td>
                    <td class="right"><strong>{{ formatRupiah($totalTunggakan) }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem {{ config('app.name') }}.</p>
    </div>
</body>

</html>
