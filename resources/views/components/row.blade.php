<tr>
    <td class="center">{{ $no++ }}</td>
    <td>
        {{ $jenis == 'Tabungan' 
            ? $data->tabungan->siswa->nama 
            : ($jenis == 'Iuran' 
                ? $data->siswa->nama 
                : $data->daftarUlang->siswa->nama) 
        }}
    </td>
    <td>{{ $data->tanggal}}</td>
    <td>{{ $jenis }}</td>
    <td>{{ $data->pembayaran }}</td>
    <td class="right">{{ $pemasukan == '-' ? '-' : formatRupiah($pemasukan)}}</td>
    <td class="right">{{ $pengeluaran == '-' ? '-' : formatRupiah($pengeluaran)}}</td>
</tr>