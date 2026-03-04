<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIMPEL MRP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

<h2>Akun SIMPEL-MRP Telah Dibuat</h2>

<p>Halo <b>{{ $user->name }}</b>,</p>

<p>Akun Anda telah dibuat oleh petugas Majelis Rakyat Papua.</p>

<p><b>Email :</b> {{ $user->email }}</p>
<p><b>Password :</b> {{ $password }}</p>

<p>Silakan login melalui:</p>

<p>
<a href="{{ url('/login') }}">
Login SIMPEL-MRP
</a>
</p>

<p>Setelah login, segera ubah password Anda.</p>

<br>

<p>Salam,</p>
<p><b>SIMPEL-MRP</b></p>

</body>
</html>
