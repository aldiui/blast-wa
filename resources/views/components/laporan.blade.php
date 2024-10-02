<div class="center">
    <h3><u>Data Pemasukan dan Pengeluaran {{ $tipe == 'cash' ? 'Cash' : 'Transfer' }}</u></h3>
    <p>{{ $tipe == 'harian' ? $tanggal : ($tipe == 'bulanan' ? $bulan . ' ' . $tahun : $tahun) }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Jenis</th>
            <th>Pembayaran</th>
            <th class="right">Pemasukan</th>
            <th class="right">Pengeluaran</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
            $totalPemasukanCash = 0;
            $totalPemasukanTransfer = 0;
            $totalPengeluaranCash = 0;
            $totalPengeluaranTransfer = 0;
        @endphp

        @foreach ($iuransCash as $iuranCash)
            @php
                $no++;
                $totalPemasukanCash +=
                    $iuranCash->status == '1' && $iuranCash->pembayaran == 'Cash'
                        ? $iuranCash->syahriyah + $iuranCash->field_trip + $iuranCash->uang_makan
                        : 0;
            @endphp
            @include('components.row', [
                'data' => $iuranCash,
                'jenis' => 'Iuran',
                'pemasukan' => $iuranCash->syahriyah + $iuranCash->field_trip + $iuranCash->uang_makan,
                'pengeluaran' => '-',
            ])
        @endforeach

        @foreach ($setoransCash as $setoranCash)
            @php
                $no++;
                $totalPemasukanCash += $setoranCash->transaksi == 'Pemasukan' ? $setoranCash->nominal : 0;
                $totalPengeluaranCash += $setoranCash->transaksi == 'Pengeluaran' ? $setoranCash->nominal : 0;
            @endphp
            @include('components.row', [
                'data' => $setoranCash,
                'jenis' => 'Tabungan',
                'pemasukan' => $setoranCash->transaksi == 'Pemasukan' ? $setoranCash->nominal : '-',
                'pengeluaran' => $setoranCash->transaksi == 'Pengeluaran' ? $setoranCash->nominal : '-',
            ])
        @endforeach

        @foreach ($setorandaftarulangCash as $daftarulangCash)
            @php
                $no++;
                $totalPemasukanCash += $daftarulangCash->nominal;
            @endphp
            @include('components.row', [
                'data' => $daftarulangCash,
                'jenis' => 'Daftar Ulang',
                'pemasukan' => $daftarulangCash->nominal,
                'pengeluaran' => '-',
            ])
        @endforeach

        <tr>
            <td colspan="4" class="right"><strong>Jumlah Cash</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPemasukanCash) }}</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPengeluaranCash) }}</strong></td>
        </tr>

        @foreach ($iuransTransfer as $iuranTransfer)
            @php
                $no++;
                $totalPengeluaranTransfer +=
                    $iuranTransfer->transaksi == 'Pengeluaran' && $setoran->pembayaran == 'Transfer'
                        ? $setoran->nominal
                        : 0;
            @endphp
            @include('components.row', [
                'data' => $iuranTransfer,
                'jenis' => 'Iuran',
                'pemasukan' => '-',
                'pengeluaran' => $iuranTransfer->transaksi == 'Pengeluaran' ? $iuranTransfer->nominal : '-',
            ])
        @endforeach

        @foreach ($setoransTransfer as $setoranTransfer)
            @php
                $no++;
                $totalPengeluaranTransfer +=
                    $setoranTransfer->transaksi == 'Pengeluaran' && $setoranTransfer->pembayaran == 'Transfer'
                        ? $setoranTransfer->nominal
                        : 0;
            @endphp

            @include('components.row', [
                'data' => $setoranTransfer,
                'jenis' => 'Tabungan',
                'pemasukan' => '-',
                'pengeluaran' => $setoranTransfer->transaksi == 'Pengeluaran' ? $setoranTransfer->nominal : '-',
            ])
        @endforeach

        @foreach ($setorandaftarulangTransfer as $daftarulangTransfer)
            @php
                $no++;
                $totalPemasukanTransfer += $daftarulangTransfer->nominal;
            @endphp

            @include('components.row', [
                'data' => $daftarulangTransfer,
                'jenis' => 'Daftar Ulang',
                'pemasukan' => $daftarulangTransfer->nominal,
                'pengeluaran' => '-',
            ])
        @endforeach


        <tr>
            <td colspan="4" class="right"><strong>Jumlah Transfer</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPemasukanTransfer) }}</strong></td>
            <td class="right"><strong>{{ formatRupiah($totalPengeluaranTransfer) }}</strong></td>
        </tr>
        <tr>
            <td colspan="4" class="right"><strong>Total</strong></td>
            <td colspan="2" class="right">
                <strong>{{ formatRupiah($totalPemasukanCash + $totalPemasukanTransfer - $totalPengeluaranCash - $totalPengeluaranTransfer) }}</strong>
            </td>
        </tr>
    </tbody>
</table>
