<!DOCTYPE html>
<html>
<head>
    <title>Pengajuan Diterima</title>
</head>
<body style="font-family: Arial, sans-serif;">

<h2>Halo, {{ $registration->student->name }}!</h2>

<p>Selamat! Pengajuan kerja praktek/magang Anda di <strong>{{ $registration->division->name }}</strong> telah <strong>DITERIMA</strong>.</p>

<p><strong>Detail Magang:</strong></p>
<ul>
    <li>Tanggal Mulai: {{ \Carbon\Carbon::parse($registration->start_date)->format('d M Y') }}</li>
    <li>Pembimbing Lapangan: <strong>{{ $registration->mentor->name }}</strong></li>
</ul>

@if($password)
    <div style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p style="margin-top: 0;">Kami telah membuatkan akun untuk Anda mengakses Portal Mahasiswa:</p>
        <ul style="margin-bottom: 0;">
            <li><strong>Email Login:</strong> {{ $registration->student->email }}</li>
            <li><strong>Password:</strong> <code style="background: #eee; padding: 2px 5px; border-radius: 3px;">{{ $password }}</code></li>
        </ul>
        <p style="font-size: 12px; color: #666; margin-top: 10px;">*Mohon segera ganti password Anda setelah login.</p>
    </div>
@endif

<p>Silakan datang ke kantor pada tanggal mulai yang tertera.</p>

<p>Salam,<br>Admin Diskominfo</p>
</body>
</html>
