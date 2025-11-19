<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-R">
    <title>Pengajuan Magang Ditolak</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

<p>Yth. {{ $student->name }},</p>

<p>Terima kasih atas pengajuan magang Anda (ID: {{ $registration->id }}).</p>

<p>Setelah melalui proses peninjauan, mohon maaf pengajuan Anda belum dapat kami terima saat ini.</p>

<p><strong>Alasan Penolakan:</strong></p>
<div style="background-color: #f8f8f8; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
    <p style="margin: 0;">{{ $registration->rejection_note }}</p>
</div>

<p>Kami menghargai minat Anda dan semoga sukses selalu.</p>

<p>Hormat kami,<br>
    Tim Rekrutmen Instansi</p>

</body>
</html>
