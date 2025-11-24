<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Data Akun</title>
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
            border-bottom: 4px solid #3b82f6;
        }
        .logo-img { max-width: 300px; width: 100%; height: auto; }

        .body { padding: 40px 40px; color: #374151; line-height: 1.6; }
        .greeting { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 16px; margin-top: 0; }
        .text-p { margin-bottom: 20px; font-size: 16px; margin-top: 0; }

        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
            color: #1e3a8a;
            font-size: 14px;
        }
        .info-title { font-weight: bold; display: block; margin-bottom: 5px; font-size: 15px; }

        .btn-container { text-align: center; margin-top: 30px; margin-bottom: 30px; }
        .btn {
            background-color: #1B2A52;
            color: #ffffff !important;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
            font-size: 15px;
        }
        .btn:hover { background-color: #2a407c; }

        .footer { background-color: #f9fafb; padding: 24px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; line-height: 1.5; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="card">

        <div class="header">
            <img src="{{ $message->embed(public_path('images/diskominfo-logo.png')) }}" alt="Logo Diskominfo" class="logo-img">
        </div>

        <div class="body">
            <h1 class="greeting">Halo, {{ $user->name }}</h1>

            <p class="text-p">
                Pemberitahuan ini dikirimkan untuk mengonfirmasi bahwa detail akun Anda di <strong>Sistem Manajemen Magang</strong> baru saja diperbarui oleh Administrator.
            </p>

            <div class="info-box">
                <span class="info-title">Apa yang berubah?</span>
                Perubahan ini mungkin mencakup perbaikan pada Nama Lengkap, Alamat Email, atau penyesuaian Hak Akses (Role) Anda.
            </div>

            <p class="text-p">
                Silakan login kembali untuk memastikan data profil Anda sudah sesuai.
            </p>

            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn">Masuk ke Aplikasi</a>
            </div>

            <p class="text-p" style="font-size: 13px; color: #6b7280; border-top: 1px dashed #e5e7eb; padding-top: 20px;">
                <strong>Keamanan:</strong> Jika Anda merasa ini adalah kesalahan atau Anda tidak meminta perubahan ini, harap segera hubungi Admin atau IT Support Diskominfo.
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
