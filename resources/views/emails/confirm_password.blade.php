<!DOCTYPE html>
<html>
<head><title>Konfirmasi Password</title></head>
<body>
<h2>Halo, {{ $user->name }}</h2>
<p>Kami menerima permintaan untuk mengubah password akun Anda.</p>
<p>Jika ini benar Anda, silakan klik tombol di bawah ini untuk mengaktifkan password baru:</p>

<p>
    <a href="{{ route('profile.password.verify', $user->password_change_token) }}"
       style="background-color: #1B2A52; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Konfirmasi Perubahan Password
    </a>
</p>

<p>Jika Anda tidak merasa melakukan ini, abaikan email ini. Password Anda tetap aman.</p>
</body>
</html>
