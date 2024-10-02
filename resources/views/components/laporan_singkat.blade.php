<div class="center">
    <h3><u>Data Pemasukan dan Pengeluaran</u></h3>
    <p>{{ $tipe == 'harian' ? 'Tanggal : ' . $tanggal : ($tipe == 'bulanan' ? 'Bulan : ' . $bulan . ' ' . $tahun : $tahun) }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis</th>
            <th>Pembayaran</th>
            <th class="right">Pemasukan</th>
            <th class="right">Pengeluaran</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $totalIuranCash = 0;
            $totalIuranTransfer = 0;
            $totalPemasukanSetoranCash = 0;
            $totalPengeluaranSetoranCash = 0;
            $totalPemasukanSetoranTransfer = 0;
            $totalPengeluaranSetoranTransfer = 0;
            $totalSetoranDaftarUlangCash = 0;
            $totalSetoranDaftarUlangTransfer = 0;
            $setoransCash = $setorans->where('pembayaran', 'Cash');
            $setoransTransfer = $setorans->where('pembayaran', 'Transfer');
            $setorandaftarulangCash = $daftarUlang->where('pembayaran', 'Cash');
            $setorandaftarulangTransfer = $daftarUlang->where('pembayaran', 'Transfer');
            $iuransCash = $iurans->where('pembayaran', 'Cash');
            $iuransTransfer = $iurans->where('pembayaran', 'Transfer');
            $totalPemasukanCash = 0;
            $totalPemasukanTransfer = 0;
            $totalPengeluaranCash = 0;
            $totalPengeluaranTransfer = 0;
        @endphp

        @foreach ($iuransCash as $cashIuran)
            @php
                $totalPemasukanCash +=
                    $cashIuran->status == '1' && $cashIuran->pembayaran == 'Cash'
                        ? $cashIuran->syahriyah + $cashIuran->field_trip + $cashIuran->uang_makan
                        : 0;
                $totalIuranCash +=
                    $cashIuran->status == '1' && $cashIuran->pembayaran == 'Cash'
                        ? $cashIuran->syahriyah + $cashIuran->field_trip + $cashIuran->uang_makan
                        : 0;
            @endphp
        @endforeach

        @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Cash',
            'jenis' => 'Iuran',
            'pemasukan' => $totalIuranCash,
            'pengeluaran' => '-',
        ])

        @foreach ($setoransCash as $setoranCash)
            @php
                $totalPemasukanSetoranCash += $setoranCash->transaksi == 'Pemasukan' ? $setoranCash->nominal : 0;
                $totalPengeluaranSetoranCash += $setoranCash->transaksi == 'Pengeluaran' ? $setoranCash->nominal : 0;
                $totalPemasukanCash += $setoranCash->transaksi == 'Pemasukan' ? $setoranCash->nominal : 0;
                $totalPengeluaranCash += $setoranCash->transaksi == 'Pengeluaran' ? $setoranCash->nominal : 0;
            @endphp
        @endforeach
        @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Cash',
            'jenis' => 'Tabungan',
            'pemasukan' => $totalPemasukanSetoranCash,
            'pengeluaran' => '-',
        ])
        @if ($totalPengeluaranSetoranCash != 0)
            @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Cash',
                'jenis' => 'Tabungan',
                'pemasukan' => '-',
                'pengeluaran' => $totalPengeluaranSetoranCash,
            ])
        @endif
        @foreach ($setorandaftarulangCash as $daftarulangCash)
            @php
                $totalPemasukanCash += $daftarulangCash->nominal;
                $totalSetoranDaftarUlangCash += $daftarulangCash->nominal;
            @endphp
        @endforeach
        @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Cash',
            'jenis' => 'Daftar Ulang',
            'pemasukan' => $totalSetoranDaftarUlangCash,
            'pengeluaran' => '-',
        ])

        <tr>
            <td colspan="3" class="right"><strong>Jumlah Cash</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPemasukanCash) }}</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPengeluaranCash) }}</strong></td>
        </tr>
        
        @foreach ($iuransTransfer as $transferIuran)
            @php
                $totalPemasukanTransfer +=
                    $transferIuran->status == '1' && $transferIuran->pembayaran == 'Transfer'
                        ? $transferIuran->syahriyah + $transferIuran->field_trip + $transferIuran->uang_makan
                        : 0;
                $totalIuranTransfer +=
                    $transferIuran->status == '1' && $transferIuran->pembayaran == 'Transfer'
                        ? $transferIuran->syahriyah + $transferIuran->field_trip + $transferIuran->uang_makan
                        : 0;
            @endphp
        @endforeach

        @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Transfer',
            'jenis' => 'Iuran',
            'pemasukan' => $totalIuranTransfer,
            'pengeluaran' => '-',
        ])

        @foreach ($setoransTransfer as $setoranTransfer)
            @php
                $totalPemasukanSetoranTransfer += $setoranTransfer->transaksi == 'Pemasukan' ? $setoranTransfer->nominal : 0;
                $totalPengeluaranSetoranTransfer += $setoranTransfer->transaksi == 'Pengeluaran' ? $setoranTransfer->nominal : 0;
                $totalPemasukanTransfer += $setoranTransfer->transaksi == 'Pemasukan' ? $setoranTransfer->nominal : 0;
                $totalPengeluaranTransfer += $setoranTransfer->transaksi == 'Pengeluaran' ? $setoranTransfer->nominal : 0;
            @endphp
        @endforeach
        @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Transfer',
            'jenis' => 'Tabungan',
            'pemasukan' => $totalPemasukanSetoranTransfer,
            'pengeluaran' => '-',
        ])
        @if ($totalPengeluaranSetoranTransfer != 0)
            @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Transfer',
                'jenis' => 'Tabungan',
                'pemasukan' => '-',
                'pengeluaran' => $totalPengeluaranSetoranTransfer,
            ])
        @endif
        @foreach ($setorandaftarulangTransfer as $daftarulangTransfer)
            @php
                $totalPemasukanTransfer += $daftarulangTransfer->nominal;
                $totalSetoranDaftarUlangTransfer += $daftarulangTransfer->nominal;
            @endphp
        @endforeach
        @include('components.row_singkat', [
            'no' => $no++ ,
            'data' => 'Transfer',
            'jenis' => 'Daftar Ulang',
            'pemasukan' => $totalSetoranDaftarUlangTransfer,
            'pengeluaran' => '-',
        ])

        <tr>
            <td colspan="3" class="right"><strong>Jumlah Transfer</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPemasukanTransfer) }}</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPengeluaranTransfer) }}</strong></td>
        </tr>
        <tr>
            <td colspan="3" class="right"><strong>Total</strong></td>
            <td colspan="2" class="right">
                <strong>{{ formatRupiah($totalPemasukanCash + $totalPemasukanTransfer - $totalPengeluaranCash - $totalPengeluaranTransfer) }}</strong>
            </td>
        </tr>
    </tbody>
</table>
