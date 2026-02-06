<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat OAP Telah Diterbitkan</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background:#f4f6f8; padding:20px">

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="600" style="background:#ffffff; border-radius:6px; padding:24px">

                {{-- HEADER --}}
                <tr>
                    <td style="border-bottom:2px solid #0d6efd; padding-bottom:12px">
                        <h2 style="margin:0; color:#0d6efd;">
                            SIMPEL-MRP
                        </h2>
                        <p style="margin:4px 0 0; color:#6c757d;">
                            Sistem Informasi Pelayanan Majelis Rakyat Papua
                        </p>
                    </td>
                </tr>

                {{-- ISI --}}
                <tr>
                    <td style="padding-top:20px; color:#212529; font-size:14px; line-height:1.6">
                        <p>Yth. Bapak/Ibu,</p>

                        <p>
                            Dengan hormat, bersama email ini kami sampaikan bahwa
                            <strong>Surat Keterangan Orang Asli Papua (OAP)</strong>
                            Anda telah <strong style="color:#198754;">DITERBITKAN</strong>
                            berdasarkan hasil verifikasi petugas.
                        </p>

                        <p>Berikut rincian surat:</p>

                        <table cellpadding="6" cellspacing="0" style="margin-bottom:16px">
                            <tr>
                                <td width="160"><strong>Nomor Surat</strong></td>
                                <td>:</td>
                                <td>{{ $pengajuan->nomor_surat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alasan Pengajuan</strong></td>
                                <td>:</td>
                                <td>{{ $pengajuan->alasan }}</td>
                            </tr>
                        </table>

                        <p>
                            📎 <strong>Surat resmi terlampir</strong> dalam email ini
                            dalam format PDF dan dapat langsung digunakan sesuai keperluan.
                        </p>

                        <p>
                            Apabila terdapat kekeliruan data atau informasi lainnya,
                            silakan menghubungi petugas melalui layanan SIMPEL-MRP.
                        </p>

                        <p>
                            Atas perhatian dan kerja sama Bapak/Ibu,
                            kami ucapkan terima kasih.
                        </p>

                        <p style="margin-top:32px">
                            Hormat kami,<br>
                            <strong>Petugas SIMPEL-MRP</strong><br>
                            Majelis Rakyat Papua Tengah
                        </p>
                    </td>
                </tr>

                {{-- FOOTER --}}
                <tr>
                    <td style="border-top:1px solid #dee2e6; padding-top:12px; font-size:12px; color:#6c757d">
                        Email ini dikirim secara otomatis oleh sistem SIMPEL-MRP.<br>
                        Mohon tidak membalas email ini.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>