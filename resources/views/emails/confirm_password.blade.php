<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Perubahan Password</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        table { border-spacing: 0; width: 100%; }
        td { padding: 0; }
        img { border: 0; display: block; margin: 0 auto; }

        .wrapper { width: 100%; background-color: #f3f4f6; padding: 40px 0; }
        .card {
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .header {
            background-color: #1B2A52;
            padding: 35px 30px;
            text-align: center;
            border-bottom: 4px solid #F59E0B;
        }
        .logo-img {
            max-width: 300px;
            width: 100%;
            height: auto;
        }

        .body { padding: 40px 40px; color: #374151; line-height: 1.6; }
        .greeting { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 16px; margin-top: 0; }
        .text-p { margin-bottom: 20px; font-size: 16px; margin-top: 0; }

        .btn-container { text-align: center; margin: 35px 0; }
        .btn {
            background-color: #1B2A52;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(27, 42, 82, 0.2);
        }
        .btn:hover { background-color: #2a407c; }

        .warning-box {
            background-color: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            margin-top: 30px;
            font-size: 14px;
            color: #9a3412;
            border-radius: 4px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="card">

        <div class="header">
            <img src="{{ $message->embed(public_path('images/diskominfo-logo.png')) }}"
                 alt="Logo Diskominfo Jabar"
                 class="logo-img">
        </div>

        <div class="body">
            <h1 class="greeting">Halo, {{ $user->name }}!</h1>

            <p class="text-p">
                Kami menerima permintaan untuk memperbarui kata sandi akun Sistem Magang Anda. Keamanan akun Anda adalah prioritas kami.
            </p>

            <p class="text-p">
                Untuk melanjutkan proses ini dan mengaktifkan kata sandi baru, silakan klik tombol konfirmasi di bawah ini:
            </p>

            <div class="btn-container">
                <a href="{{ route('profile.password.verify', $user->password_change_token) }}" class="btn">
                    Konfirmasi Password Baru
                </a>
            </div>

            <div class="warning-box">
                <strong>Bukan Anda?</strong><br>
                Jika Anda tidak merasa melakukan permintaan ini, mohon <strong>abaikan email ini</strong>. Kata sandi lama Anda tetap aman.
            </div>

            <p style="margin-top: 30px; font-size: 12px; color: #6b7280; border-top: 1px dashed #e5e7eb; padding-top: 20px;">
                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
                <a href="{{ route('profile.password.verify', $user->password_change_token) }}" style="color: #1B2A52; word-break: break-all;">
                    {{ route('profile.password.verify', $user->password_change_token) }}
                </a>
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} <strong>Dinas Komunikasi dan Informatika Provinsi Jawa Barat</strong><br>
                Jl. Tamansari No. 55, Bandung, Jawa Barat 40132
            </p>
            <p>Email ini dikirim secara otomatis oleh sistem.</p>
        </div>

    </div>
</div>

</body>
</html>
