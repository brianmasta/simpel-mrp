<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perbaikan Berkas Surat OAP</title>
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
                            Berdasarkan hasil verifikasi petugas,
                            pengajuan <strong>Surat Keterangan Orang Asli Papua (OAP)</strong>
                            Anda dengan rincian:
                        </p>

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
                            dinyatakan <strong style="color:#dc3545;">PERLU PERBAIKAN</strong>
                            pada berkas berikut:
                        </p>

                        {{-- CATATAN PETUGAS --}}
                        <ul style="padding-left:18px">
                            @foreach($catatan as $item)
                                <li style="margin-bottom:8px">
                                    <strong>{{ strtoupper($item->dokumen) }}</strong><br>
                                    <span style="color:#dc3545">
                                        {{ $item->catatan }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <p>
                            Silakan melakukan perbaikan dan mengunggah ulang berkas
                            melalui tautan berikut:
                        </p>

                        {{-- BUTTON --}}
                        <p style="text-align:center; margin:24px 0">
                            <a href="{{ $perbaikanUrl }}"
                               style="background:#0d6efd; color:#ffffff; text-decoration:none;
                                      padding:12px 20px; border-radius:4px; display:inline-block">
                                Perbaiki Berkas Sekarang
                            </a>
                        </p>

                        <p>
                            Apabila perbaikan telah dilakukan, berkas akan kembali diverifikasi
                            oleh petugas.
                        </p>

                        <p>
                            Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.
                        </p>

                        <p style="margin-top:32px">
                            Hormat kami,<br>
                            <strong>Petugas SIMPEL-MRP</strong>
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
