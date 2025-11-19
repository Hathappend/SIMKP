<!DOCTYPE html>
<html>
<head>
    <title>Surat Balasan KP Diskominfo</title>
    <style>
        /* Setup Kertas dan Font */
        @page {
            margin: 2cm 2cm 2cm 2cm; /* Margin Atas Kanan Bawah Kiri */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3; /* Jarak antar baris standar surat dinas */
            color: #000;
        }

        /* KOP SURAT */
        .header-table {
            width: 100%;
            border-bottom: 4px solid #000; /* Garis tebal bawah kop */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            width: 85px; /* Sesuaikan ukuran logo */
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 0;
            font-size: 10pt;
            font-style: normal;
        }

        /* BAGIAN TANGGAL & ALAMAT */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            vertical-align: top;
        }
        .info-label {
            width: 80px; /* Lebar label 'Nomor', 'Sifat' */
        }
        .info-separator {
            width: 10px;
            text-align: center;
        }

        /* BAGIAN ISI */
        .content-text {
            text-align: justify;
            text-indent: 30px; /* Menjorok ke dalam (alinea) */
            margin-bottom: 10px;
        }
        .content-normal {
            text-align: justify;
            margin-bottom: 10px;
        }

        /* TABEL MAHASISWA */
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .student-table th, .student-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            font-size: 11pt;
        }
        .student-table th {
            text-align: center;
            font-weight: bold;
        }
        .center-text {
            text-align: center !important;
        }

        /* TANDA TANGAN */
        .signature-container {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid; /* Jangan potong ttd ke halaman baru */
        }
        .signature-box {
            float: right;
            width: 45%; /* Lebar area tanda tangan */
            text-align: center;
        }
        .digital-signature {
            width: 250px; /* Ukuran gambar TTE */
            height: auto;
            margin: 10px auto;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td width="15%" style="vertical-align: middle;">
            <img src="{{ public_path('images/jabar-logo.png') }}" class="logo" alt="Logo">
        </td>
        <td width="85%" class="kop-text">
            <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
            <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
            <p>Jalan Tamansari No. 55 Tlp. (022) 2502898 Faksimili (022) 2511505</p>
            <p>Website: https://diskominfo.jabarprov.go.id email: diskominfo@jabarprov.go.id</p>
            <p>Bandung 40132</p>
        </td>
    </tr>
</table>

<div style="text-align: right; margin-bottom: 10px;">
    Bandung, {{ \Carbon\Carbon::parse($registration->letter_date)->translatedFormat('d F Y') }}
</div>

<table class="info-table">
    <tr>
        <td width="55%" style="vertical-align: top;">
            <table width="100%">
                <tr>
                    <td class="info-label">Nomor</td>
                    <td class="info-separator">:</td>
                    <td>{{ $registration->letter_number }}</td>
                </tr>
                <tr>
                    <td>Sifat</td>
                    <td>:</td>
                    <td>Biasa</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td>
                        Surat Balasan Permohonan<br>Kerja Praktek
                    </td>
                </tr>
            </table>
        </td>

        <td width="45%" style="vertical-align: top;">
            Kepada :<br>
            Yth. Ketua Program Studi {{ $registration->student->study_program }}<br>
            {{ $registration->student->university }}<br>
            di<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T E M P A T
        </td>
    </tr>
</table>

<div class="content-text">
    Disampaikan dengan hormat, menindaklanjuti surat Pengajuan Permohonan Kerja Praktek pada tanggal
    {{ \Carbon\Carbon::parse($registration->created_at)->translatedFormat('d F Y') }}
    Nomor: {{ $campusLetterNumber }} atas nama berikut:
</div>

@if($registration->members->count() > 0)

    <table border="1" cellspacing="0" cellpadding="5" class="student-table" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
        <tr style="background-color: #f0f0f0;">
            <th style="width: 5%;">No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registration->members as $index => $member)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $member->name }}</td>
                <td style="text-align: center;">{{ $member->nim }}</td>
                {{-- Jurusan diambil dari ketua --}}
                <td>{{ $member->study_program }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@else
    {{-- Fallback jika tidak ada data members (Data tunggal) --}}
    <table border="0" style="width: 100%; margin-left: 20px; margin-bottom: 15px;">
        <tr><td width="100">Nama</td><td>: <strong>{{ $registration->student->name }}</strong></td></tr>
        <tr><td>NIM</td><td>: {{ $registration->student->nim }}</td></tr>
        <tr><td>Jurusan</td><td>: {{ $registration->student->study_program }}</td></tr>
    </table>
@endif

<div class="content-normal">
    Diberikan ijin untuk melaksanakan Kerja Praktek di Dinas Komunikasi dan Informatika Provinsi Jawa Barat pada periode
    <strong>{{ \Carbon\Carbon::parse($registration->start_date)->translatedFormat('F') }}</strong> s.d
    <strong>{{ \Carbon\Carbon::parse($registration->end_date)->translatedFormat('F Y') }}</strong>,
    dengan mentor pembimbing: <strong>{{ $registration->mentor->name }}</strong>.
</div>

<div class="content-text">
    Demikian kami sampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.
</div>

<div class="signature-container">
    <div class="signature-box">
        KEPALA DINAS KOMUNIKASI DAN INFORMATIKA<br>
        PROVINSI JAWA BARAT,
        <br>
        <img src="{{ public_path('images/tte-sample.png') }}" class="digital-signature" alt="Tanda Tangan Elektronik">
    </div>
    <div style="clear: both;"></div>
</div>

</body>
</html>
