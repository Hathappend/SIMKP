<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Magang</title>
    <style>
        @page { margin: 0; size: landscape; }
        body { font-family: 'Arial', sans-serif; text-align: center; padding: 50px; }

        .border-pattern {
            position: absolute; left: 15px; top: 15px; right: 15px; bottom: 15px;
            border: 5px double #1B2A52; z-index: -1;
        }

        .logo { width: 80px; margin-bottom: 20px; }
        .title { font-size: 36pt; font-weight: bold; color: #1B2A52; margin-bottom: 10px; letter-spacing: 2px; }
        .subtitle { font-size: 18pt; color: #555; margin-bottom: 40px; }

        .name { font-size: 32pt; font-weight: bold; color: #000; border-bottom: 2px solid #ccc; display: inline-block; padding: 0 20px 5px 20px; margin-bottom: 20px; }

        .content { font-size: 14pt; color: #444; line-height: 1.6; margin-bottom: 40px; }

        .table-score {
            width: 60%; margin: 0 auto 40px auto; border-collapse: collapse;
        }
        .table-score th, .table-score td {
            border: 1px solid #999; padding: 8px 15px; text-align: left;
        }
        .table-score th { background-color: #f0f0f0; }

        .footer { width: 100%; display: table; }
        .col { display: table-cell; width: 50%; vertical-align: top; }
        .sign { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
<div class="border-pattern"></div>

<img src="{{ public_path('images/logo-jabar.png') }}" class="logo">

<div class="title">SERTIFIKAT</div>
<div class="subtitle">Nomor: {{ $assessment->certificate_number }}</div>

<div class="content">
    Diberikan kepada:
    <br>
    <div class="name">{{ $studentName }}</div>
    <br>
    Atas partisipasinya dalam melaksanakan <strong>Kerja Praktek (Magang)</strong> di Dinas Komunikasi dan Informatika Provinsi Jawa Barat pada Divisi <strong>{{ $divisionName }}</strong>.
    <br>
    Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}.
</div>

<table class="table-score">
    <tr>
        <th>Aspek Penilaian</th>
        <th style="text-align: center">Nilai</th>
    </tr>
    <tr><td>Kedisiplinan</td><td style="text-align: center">{{ $assessment->score_discipline }}</td></tr>
    <tr><td>Kualitas Hasil Kerja</td><td style="text-align: center">{{ $assessment->score_technical }}</td></tr>
    <tr><td>Komunikasi & Kerjasama</td><td style="text-align: center">{{ $assessment->score_performance }}</td></tr>
    <tr><td>Initiatif/td><td style="text-align: center">{{ $assessment->score_initiative }}</td></tr>
    <tr><td>Personallity/td><td style="text-align: center">{{ $assessment->score_personallity }}</td></tr>
    <tr><td><strong>NILAI AKHIR</strong></td><td style="text-align: center"><strong>{{ $assessment->final_score }} ({{ $assessment->grade }})</strong></td></tr>
</table>

<div class="footer">
    <div class="col">
    </div>
    <div class="col">
        Bandung, {{ now()->translatedFormat('d F Y') }}<br>
        Kepala Divisi / Pembimbing,
        <br><br><br><br>
        <div class="sign">{{ Auth::user()->name }}</div>
        NIP. 198xxxxxxxxx
    </div>
</div>
</body>
</html>
