<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Laporan Harian - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1;
            margin: 0;
            padding: 0;
        }

        h3 {
            margin: 0.3em 0;
            font-size: 1.2em;
        }

        p,
        td {
            margin: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            font-size: 0.9em;
        }

        th,
        td {
            padding: 9px;
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
            margin: 5px 0;
            border: none;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 0.9em;
        }

        /* Menghapus border dan mengatur margin pada tabel tanpa border */
        .no-border {
            border: none;
            margin-top: 0;
            /* Mengurangi jarak dari atas */
        }

        .no-border td {
            border: none;
        }

        .no-border h3,
        .no-border p {
            margin-top: 0;
            margin-bottom: 0;
        }

    </style>
</head>

<body>
    <table class="no-border">
        <tr>
            <td width="12%" class="center">
                <img width="100"
                    src="{{ generateBase64Image('storage/pengaturan/' . basename(getPengaturan()->logo)) }}"
                    alt="Logo">
            </td>
            <td class="center">
                <h3>{{ $jenis == 'laporan' ? 'Laporan ' . ucfirst($tipe) : 'Tunggakan' }}</h3>
                @if ($jenis == 'laporan')
                    <h3>Pemasukan dan Pengeluaran</h3>
                @endif
                <h3>{{ getPengaturan()->nama }}</h3>
                <p>Komplek Pesantren Condong, Setianagara, Tasikmalaya, Jawa Barat 46196</p>
            </td>
            <td width="12%"></td>
        </tr>
    </table>
    <hr>
    @if ($jenis == 'laporan')
        @include('components.laporan', [
            'tipe' => $tipe,
            'daftarUlangCash' => $setorandaftarulangCash,
            'daftarUlangTransfer' => $setorandaftarulangTransfer,
            'iuransCash' => $iuransCash,
            'iuransTransfer' => $iuransTransfer,
            'setoransCash' => $setoransCash,
            'setoransTransfer' => $setoransTransfer,
        ])
    @elseif ($jenis == 'laporan_singkat')
        @include('components.laporan_singkat', [
            'tipe' => $tipe,
            'daftarUlang' => $setorandaftarulangs,
            'iurans' => $iurans,
        ])
    @elseif ($jenis == 'tunggakan')
        @include('components.tunggakan', ['daftarUlangs' => $daftarUlangs, 'iurans' => $iurans])
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem {{ config('app.name') }}.</p>
    </div>
</body>

</html>
