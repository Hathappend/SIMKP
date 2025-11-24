<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengajuan Magang</title>
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
            border-bottom: 4px solid #ef4444;
        }
        .logo-img { max-width: 300px; width: 100%; height: auto; }

        .body { padding: 40px 40px; color: #374151; line-height: 1.6; }
        .greeting { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 16px; margin-top: 0; }
        .text-p { margin-bottom: 20px; font-size: 16px; margin-top: 0; }

        .rejection-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
            color: #991b1b;
        }
        .box-title { font-weight: bold; font-size: 14px; margin-bottom: 5px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
        .reason-text { font-style: italic; font-weight: 500; }

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
            <h1 class="greeting">Halo, {{ $student->name }}</h1>

            <p class="text-p">
                Terima kasih atas ketertarikan Anda untuk melaksanakan Kerja Praktek (Magang) di Dinas Komunikasi dan Informatika Provinsi Jawa Barat.
            </p>

            <p class="text-p">
                Kami telah meninjau berkas pengajuan Anda (ID: <strong>#{{ $registration->id }}</strong>). Namun, dengan berat hati kami informasikan bahwa pengajuan Anda <strong>belum dapat kami terima</strong> untuk saat ini.
            </p>

            <div class="rejection-box">
                <span class="box-title">Alasan Penolakan:</span>
                <span class="reason-text">"{{ $registration->rejection_note }}"</span>
            </div>

            <p class="text-p" style="margin-top: 30px;">
                Keputusan ini tidak mengurangi apresiasi kami terhadap profil akademis Anda. Kami mendoakan yang terbaik untuk studi dan karir Anda ke depannya.
            </p>

            <p class="text-p">
                Tetap semangat!
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
