<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

<p>Halo, {{ $user->name }},</p>

<p>Akun Anda untuk Sistem Manajemen Magang telah berhasil dibuat oleh Admin.</p>

<p>Anda dapat login ke sistem menggunakan detail berikut:</p>
<ul>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Password Sementara:</strong> {{ $temporaryPassword }}</li>
</ul>

<p>Demi keamanan, kami sangat menyarankan Anda untuk <strong>segera mengganti password</strong> Anda setelah login pertama kali melalui halaman profil Anda.</p>

<p>Terima kasih,<br>
    Admin Sistem Magang</p>

</body>
</html>
