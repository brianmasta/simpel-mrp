<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapan Data Akun</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h3 { text-align: center; margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #f0f0f0; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<h3>REKAPAN DATA AKUN SIMPEL-MRP</h3>
<p style="text-align:center;">
    MAJELIS RAKYAT PAPUA PROVINSI PAPUA TENGAH
</p>

<p>Tanggal Cetak: {{ $tanggal }}</p>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Nama</th>
            <th>Email</th>
            <th width="12%">Role</th>
            <th>Kabupaten</th>
            <th width="15%">Tanggal Daftar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $user)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td class="text-center">{{ strtoupper($user->role) }}</td>
            <td>{{ $user->profil->kabupaten->nama ?? '-' }}</td>
            <td class="text-center">
                {{ optional($user->created_at)->format('d-m-Y') }}
            </td>
        </tr>
        @endforeach
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
