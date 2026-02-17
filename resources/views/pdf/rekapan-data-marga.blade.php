<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapan Data Marga</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        h3 {
            text-align: center;
            margin-bottom: 3px;
        }
        .sub-title {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
        }
        td {
            font-size: 9.5px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h3>REKAPAN DATA MARGA</h3>
    <div class="sub-title">
        MAJELIS RAKYAT PAPUA PROVINSI PAPUA TENGAH
    </div>

    <p>
        Tanggal Cetak: {{ $tanggal }}
    </p>

    <table>
        <thead>
            <tr>
                <th width="6%">No</th>
                <th width="26%">Marga</th>
                <th width="26%">Wilayah Adat</th>
                <th width="22%">Suku</th>
                <th width="20%">Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $marga)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ strtoupper($marga->marga) }}</td>
                <td>{{ strtoupper($marga->wilayah_adat) }}</td>
                <td>{{ strtoupper($marga->suku) }}</td>
                <td class="text-center">
                    {{ optional($marga->created_at)->format('d-m-Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">
                    Tidak ada data marga
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <br><br>

    <table width="100%" style="border:none">
        <tr>
            <td width="60%" style="border:none"></td>
            <td class="text-center" style="border:none">
                Nabire, {{ $tanggal }}<br>
                <strong>Petugas SIMPEL-MRP</strong>
                <br><br><br>
                _______________________
            </td>
        </tr>
    </table>

</body>
</html>
