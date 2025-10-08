<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.6; }
        h2 { text-align: center; text-transform: uppercase; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>{{ $judul }}</h2>
    <p><strong>{{ $nomorSurat }}</strong></p>

    <div class="content">
        {!! nl2br(e($isiSurat)) !!}
    </div>
</body>
</html>
