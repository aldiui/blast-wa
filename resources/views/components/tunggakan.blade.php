<div class="tunggakan-container">
    <h3 class="text-center">Data Tunggakan</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jenis Tunggakan</th>
                <th class="text-right">Jumlah Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalTunggakan = 0;
            @endphp
            @foreach ($iurans as $tunggakanIuran)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $tunggakanIuran->siswa->nama }}</td>
                    <td>Iuran</td>
                    <td class="text-right">{{ formatRupiah($tunggakanIuran->syahriyah + $tunggakanIuran->uang_makan + $tunggakanIuran->field_trip) }}</td>
                </tr>
                @php
                    $totalTunggakan += $tunggakanIuran->syahriyah+$tunggakanIuran->uang_makan+$tunggakanIuran->field_trip;
                @endphp
            @endforeach

            @foreach ($daftarUlangs as $tunggakanDaftarUlang)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $tunggakanDaftarUlang->siswa->nama }}</td>
                    <td>Daftar Ulang</td>
                    <td class="text-right">{{ formatRupiah($tunggakanDaftarUlang->biaya) }}</td>
                </tr>
                @php
                    $totalTunggakan += $tunggakanDaftarUlang->biaya;
                @endphp
            @endforeach
            <tr>
                <td colspan="3" class="text-right"><strong>Total Tunggakan</strong></td>
                <td class="text-right"><strong>{{ formatRupiah($totalTunggakan) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
