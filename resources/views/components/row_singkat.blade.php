
<tr>
    <td class="center">{{ $no }}</td>  
    <td>{{ $jenis }}</td>
    <td>{{ $data }}</td>
    <td class="right">{{ $pemasukan == '-' ? '-' : formatRupiah($pemasukan)}}</td>
    <td class="right">{{ $pengeluaran == '-' ? '-' : formatRupiah($pengeluaran)}}</td>
</tr>