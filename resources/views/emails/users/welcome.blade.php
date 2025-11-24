<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Sistem Magang</title>
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

        .credential-box {
            background-color: #f0f9ff;
            border: 1px dashed #0ea5e9;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        .credential-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .credential-value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            font-family: 'Courier New', Courier, monospace;
            background: #ffffff;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #cbd5e1;
        }
        .credential-row { margin-bottom: 15px; }
        .credential-row:last-child { margin-bottom: 0; }

        .btn-container { text-align: center; margin-top: 30px; }
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

        .security-note {
            font-size: 13px;
            color: #ef4444;
            margin-top: 20px;
            background-color: #fef2f2;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
        }

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
            <h1 class="greeting">Selamat Datang, {{ $user->name }}! 👋</h1>

            <p class="text-p">
                Akun Anda untuk mengakses <strong>Sistem Manajemen Magang Diskominfo</strong> telah berhasil dibuat oleh Administrator.
            </p>

            <p class="text-p">
                Anda kini dapat login sebagai <strong>Pembimbing Lapangan</strong> untuk memantau aktivitas, memeriksa logbook, dan memberikan penilaian kepada mahasiswa magang.
            </p>

            <div class="credential-box">
                <div class="credential-row">
                    <span class="credential-label">Alamat Email</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Password Sementara</span>
                    <span class="credential-value">{{ $temporaryPassword }}</span>
                </div>
            </div>

            <div class="security-note">
                <strong>⚠️ PENTING:</strong> Demi keamanan akun Anda, harap segera mengganti password ini setelah login pertama kali melalui menu <em>Profil Saya</em>.
            </div>

            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn">
                    Login ke Dashboard
                </a>
            </div>

            <p class="text-p" style="margin-top: 30px; font-size: 14px; text-align: center; color: #6b7280;">
                Jika Anda mengalami kendala saat login, silakan hubungi Administrator.
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
