<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Diterima</title>
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
            border-bottom: 4px solid #22c55e;
        }
        .logo-img { max-width: 300px; width: 100%; height: auto; }

        .body { padding: 40px 40px; color: #374151; line-height: 1.6; }
        .greeting { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 16px; margin-top: 0; }
        .text-p { margin-bottom: 20px; font-size: 16px; margin-top: 0; }

        .success-box {
            background-color: #ecfdf5;
            border-left: 4px solid #22c55e;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
            color: #15803d;
        }
        .success-title { font-weight: bold; font-size: 18px; margin-bottom: 5px; display: block; }

        .detail-table { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
        .detail-table td { padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .detail-label { font-weight: bold; color: #6b7280; width: 140px; }
        .detail-value { color: #111827; font-weight: 500; }

        .account-box {
            background-color: #eff6ff;
            border: 1px dashed #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
        .account-title { font-size: 14px; font-weight: bold; color: #1e40af; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .credentials { font-family: monospace; background: #ffffff; padding: 10px 15px; border-radius: 4px; border: 1px solid #dbeafe; display: inline-block; margin: 5px 0; font-size: 16px; color: #1e3a8a; }

        .btn-container { text-align: center; margin-top: 30px; }
        .btn {
            background-color: #1B2A52;
            color: #ffffff !important;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
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
            <h1 class="greeting">Selamat, {{ $registration->student->name }}! 🎉</h1>

            <div class="success-box">
                <span class="success-title">Pengajuan Diterima</span>
                Selamat! Permohonan Kerja Praktek (Magang) Anda di <strong>{{ $registration->division->name }}</strong> telah disetujui.
            </div>

            <p class="text-p">Berikut adalah detail penempatan magang Anda:</p>

            <table class="detail-table">
                <tr>
                    <td class="detail-label">Tanggal Mulai</td>
                    <td class="detail-value">{{ \Carbon\Carbon::parse($registration->start_date)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Pembimbing</td>
                    <td class="detail-value">{{ $registration->mentor->name }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Lokasi</td>
                    <td class="detail-value">Kantor Diskominfo Jabar</td>
                </tr>
            </table>

            @if($password)
                <div class="account-box">
                    <div class="account-title">Akun Portal Mahasiswa</div>
                    <p style="margin: 0 0 10px 0; font-size: 14px;">Gunakan akun ini untuk login dan mengisi logbook:</p>

                    <div style="margin-bottom: 8px;">
                        <span style="color:#6b7280; font-size:12px;">Email:</span><br>
                        <strong>{{ $registration->student->email }}</strong>
                    </div>

                    <div>
                        <span style="color:#6b7280; font-size:12px;">Password Sementara:</span><br>
                        <span class="credentials">{{ $password }}</span>
                    </div>

                    <p style="margin-top: 15px; font-size: 12px; color: #ef4444;">*Harap segera ganti password setelah login pertama.</p>
                </div>

                <div class="btn-container">
                    <a href="{{ route('login') }}" class="btn">Login ke Portal</a>
                </div>
            @endif

            <p class="text-p" style="margin-top: 30px; font-size: 14px;">
                Silakan datang ke kantor pada tanggal mulai yang tertera dengan membawa surat pengantar asli dari kampus.
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
