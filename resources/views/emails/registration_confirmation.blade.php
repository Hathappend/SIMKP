<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <style>
        /* Reset dasar */
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

        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #1e40af;
        }

        .step { margin-bottom: 10px; }
        .step-icon { display: inline-block; width: 20px; font-weight: bold; color: #1B2A52; }

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
            <h1 class="greeting">Halo, {{ $name }}! 👋</h1>

            <p class="text-p">
                Terima kasih telah mendaftar untuk program <strong>Kerja Praktek (Magang)</strong> di Dinas Komunikasi dan Informatika Provinsi Jawa Barat.
            </p>

            <div class="info-box">
                <strong>Status: Menunggu Verifikasi</strong><br>
                Data pendaftaran Anda telah kami terima dan sedang dalam antrian verifikasi oleh Admin.
            </div>

            <p class="text-p"><strong>Apa langkah selanjutnya?</strong></p>

            <div class="step"><span class="step-icon">1.</span> Admin akan memeriksa kelengkapan berkas Anda.</div>
            <div class="step"><span class="step-icon">2.</span> Jika lolos verifikasi admin, data akan diteruskan ke Kepala Divisi tujuan.</div>
            <div class="step"><span class="step-icon">3.</span> Anda akan menerima <strong>email pemberitahuan</strong> jika pendaftaran Anda disetujui atau ditolak.</div>

            <p class="text-p" style="margin-top: 25px;">
                Mohon cek email Anda secara berkala. Terima kasih atas antusiasme Anda!
            </p>
        </div>

        <div class="footer">
            <p>
                &copy; {{ date('Y') }} <strong>Dinas Komunikasi dan Informatika Provinsi Jawa Barat</strong><br>
                Jl. Tamansari No. 55, Bandung, Jawa Barat 40132
            </p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas.</p>
        </div>

    </div>
</div>

</body>
</html>
