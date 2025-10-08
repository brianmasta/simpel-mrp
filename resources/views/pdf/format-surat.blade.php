<!DOCTYPE html>
<html>
<head>
    <title>{{ $format->jenis }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin-top: 10px; }
    </style>
</head>
<body>
    {{-- <div class="header">
        <h2>{{ $format->jenis }}</h2>
    </div> --}}
    <div class="content">
        {!! $format->isi !!}
    </div>
</body>
</html>
