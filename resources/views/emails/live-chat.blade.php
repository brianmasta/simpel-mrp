<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Chat Masuk - SIMPEL MRP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h3>📩 Live Chat Baru Masuk</h3>

    <p>Telah masuk permintaan bantuan melalui <strong>Live Chat SIMPEL-MRP</strong>.</p>

    <table cellpadding="6" cellspacing="0" border="1" width="100%">
        <tr>
            <th align="left">Nama</th>
            <td>{{ $chat->name }}</td>
        </tr>
        <tr>
            <th align="left">Email</th>
            <td>{{ $chat->email ?? '-' }}</td>
        </tr>
        <tr>
            <th align="left">Status</th>
            <td>{{ strtoupper($chat->status ?? 'OPEN') }}</td>
        </tr>
        <tr>
            <th align="left">Waktu</th>
            <td>{{ $chat->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <p style="margin-top:15px;">
        Silakan login ke <strong>Dashboard SIMPEL-MRP</strong> untuk menindaklanjuti pesan ini.
    </p>

    <hr>

    <small>
        Email ini dikirim otomatis oleh sistem SIMPEL-MRP.<br>
        Mohon tidak membalas email ini.
    </small>

</body>
</html>
