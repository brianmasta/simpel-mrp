<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapan Surat OAP</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        h3 {
            text-align: center;
            margin-bottom: 4px;
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
            padding: 4px 5px;
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
        .footer {
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>
<body>

    <h3>
        REKAPAN DATA PENGAJUAN SURAT OAP
    </h3>
    <div class="sub-title">
        MAJELIS RAKYAT PAPUA PROVINSI PAPUA TENGAH
    </div>

    <p>
        Tanggal Cetak: {{ $tanggal }}
    </p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="20%">Nama Pemohon</th>
                <th width="15%">NIK</th>
                <th width="15%">Kabupaten</th>
                <th width="15%">No Surat</th>
                <th width="16%">Alasan</th>
                <th width="10%">Status</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $surat)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $surat->user->name ?? '-' }}</td>
                    <td>{{ $surat->user->profil->nik ?? '-' }}</td>
                    <td>{{ $surat->user->profil->kabupaten->nama ?? '-' }}</td>
                    <td>{{ $surat->nomor_surat ?? '-' }}</td>
                    <td>{{ $surat->alasan ?? '-' }}</td>
                    <td class="text-center">{{ strtoupper($surat->status) }}</td>
                    <td class="text-center">
                        {{ optional($surat->created_at)->format('d-m-Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Tidak ada data pengajuan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table width="100%" style="border:none">
            <tr>
                <td width="65%" style="border:none"></td>
                <td class="text-center" style="border:none">
                    Nabire, {{ $tanggal }}<br>
                    <strong>Petugas SIMPEL-MRP</strong>
                    <br><br><br>
                    _______________________
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
