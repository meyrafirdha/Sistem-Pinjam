<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pinjaman - {{ $pinjaman->user->name }}</title>
    <style>
        @page { margin: 20px 40px; }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11.5px;
            color: #1a1a1a;
            line-height: 1.35;
        }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .header-table td { vertical-align: middle; }
        .logo-cell { width: 70px; }
        .logo-cell img { width: 60px; height: 60px; object-fit: contain; }
        .logo-cell.right { text-align: right; }
        .title-cell { text-align: center; }
        .title-cell .main-title { font-size: 18px; font-weight: bold; color: #7a1f2b; margin: 0; }
        .title-cell .sub-title { font-size: 12px; margin: 2px 0 0; }
        .divider { border: none; border-top: 3px solid #7a1f2b; margin: 4px 0 12px; }

        .form-title { text-align: center; font-weight: bold; font-size: 13px; text-decoration: underline; margin-bottom: 14px; text-transform: uppercase; }

        .intro { margin-bottom: 8px; }

        table.fields { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.fields td { padding: 2px 0; vertical-align: top; }
        table.fields td.label { width: 190px; }
        table.fields td.colon { width: 12px; }
        .note { font-size: 10px; color: #555; font-style: italic; margin: -2px 0 6px 190px; }

        p { margin: 0 0 8px; text-align: justify; }

        .tanggal { text-align: center; margin: 12px 0 16px; }

        table.ttd { width: 100%; border-collapse: collapse; }
        .ttd-space { height: 50px; }
        .ttd-name { text-decoration: underline; font-weight: bold; }

        .print-btn {
            display: inline-block; margin-bottom: 16px; padding: 8px 16px;
            background: #7a1f2b; color: #fff; border: none; border-radius: 6px;
            font-size: 13px; cursor: pointer; font-family: inherit;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    @if(!($download ?? false))
        <button class="print-btn no-print" onclick="window.print()">🖨️ Print Formulir</button>
    @endif

    @php
        $logoKemhan = ($download ?? false) ? public_path('images/Kemhan logo.png') : asset('images/Kemhan logo.png');
        $logoKoperasi = ($download ?? false) ? public_path('images/koperasi logo.png') : asset('images/koperasi logo.png');
        $pangkatNrp = trim(($pinjaman->user->pangkat_gol ?? '').' / '.$pinjaman->user->nip_nrp, ' /');
        // Kalau pengajuan ini SUDAH disetujui dan sudah punya snapshot sendiri, pakai itu
        // (supaya tidak ikut berubah kalau admin ganti nama Juru Bayar global setelahnya).
        // Kalau belum disetujui / belum ada snapshot, pakai nilai global yang berlaku saat ini.
        $pengaturanGlobal = \App\Models\Pengaturan::current();
        $namaJuruBayar = ($pinjaman->isDisetujui() && $pinjaman->nama_juru_bayar)
            ? $pinjaman->nama_juru_bayar
            : $pengaturanGlobal->nama_juru_bayar;
        $nrpJuruBayar = ($pinjaman->isDisetujui() && $pinjaman->nrp_juru_bayar)
            ? $pinjaman->nrp_juru_bayar
            : $pengaturanGlobal->nrp_juru_bayar;
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ $logoKoperasi }}" alt="Logo Koperasi">
            </td>
            <td class="title-cell">
                <p class="main-title">Koperasi Konsumen Karyawan Balitbang Kemhan</p>
                <p class="sub-title"> Jl. Jati No. 1 Pondok Labu Jakarta Selatan</p>
                <p class="sub-title"> Telp/Fax: 021-7502086</p>
            </td>
            <td class="logo-cell right">
                <img src="{{ $logoKemhan }}" alt="Logo Kemhan">
            </td>
        </tr>
    </table>
    <hr class="divider">

    <p class="form-title">Surat Permohonan dan Perjanjian Pengajuan Pinjaman</p>

    <p class="intro">Yang bertandatangan dibawah ini:</p>

    <table class="fields">
        <tr>
            <td class="label">Nama</td><td class="colon">:</td><td>{{ $pinjaman->user->name }}</td>
        </tr>
        <tr>
            <td class="label">Pangkat/Gol/NRP/NIP</td><td class="colon">:</td><td>{{ $pangkatNrp ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan/Satker</td><td class="colon">:</td><td>{{ $pinjaman->user->jabatan_satker ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Rekening</td><td class="colon">:</td><td>{{ $pinjaman->no_rekening }}</td>
        </tr>
        <tr>
            <td class="label">Nama Rekening</td><td class="colon">:</td><td>{{ $pinjaman->nama_rekening }}</td>
        </tr>
    </table>
    <div class="note">(wajib rekening atas nama pribadi)</div>

    <table class="fields">
        <tr>
            <td class="label">Nama Bank</td><td class="colon">:</td><td>{{ $pinjaman->nama_bank }}</td>
        </tr>
        <tr>
            <td class="label">No. Handphone</td><td class="colon">:</td><td>{{ $pinjaman->no_hp ?: '-' }}</td>
        </tr>
    </table>

    <p>Dengan ini mengajukan permohonan pengajuan pinjaman kepada USIPA (Usaha Simpan Pinjam) Kementerian Pertahanan RI:</p>

    <table class="fields">
        <tr>
            <td class="label">Jumlah Pinjaman</td><td class="colon">:</td><td>Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Angsuran</td><td class="colon">:</td><td>{{ $pinjaman->jumlah_angsuran ? 'Rp '.number_format($pinjaman->jumlah_angsuran, 0, ',', '.').' / bulan' : '(belum ditentukan)' }}</td>
        </tr>
        <tr>
            <td class="label">Jangka Waktu Pinjaman</td><td class="colon">:</td><td>{{ $pinjaman->jangka_waktu }}</td>
        </tr>
    </table>

    <p>
        Saya
        <span style="{{ !$pinjaman->punya_hutang_bank ? 'text-decoration: line-through;' : 'font-weight: bold;' }}">memiliki</span>
        /
        <span style="{{ $pinjaman->punya_hutang_bank ? 'text-decoration: line-through;' : 'font-weight: bold;' }}">tidak memiliki</span>
        Hutang pada Bank
        {{ $pinjaman->punya_hutang_bank ? ' '.$pinjaman->hutang_bank_nama.' ' : ' ______________________ ' }}
        dengan angsuran
        Rp {{ $pinjaman->punya_hutang_bank ? number_format($pinjaman->hutang_bank_angsuran, 0, ',', '.') : '______________' }} /bulan.
    </p>

    <p>
        Saya sanggup/bersedia membayar angsuran dari gaji pokok yang dipotong melalui Bendahara Pengeluaran Balitbang Kemhan.
        Dan saya bersedia melaksanakan seluruh aturan dan ketentuan yang sudah ditetapkan oleh USIPA Kementerian Pertahanan RI.
    </p>

    <p>Demikian Surat Permohonan ini untuk ditindaklanjuti.</p>

    <table class="ttd" style="width: 100%;">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                Mengetahui,<br>
                Juru Bayar Balitbang Kemhan,
                <div class="ttd-space"></div>
                @if($namaJuruBayar)
                    <span class="ttd-name">{{ $namaJuruBayar }}</span><br>
                @else
                    <span class="ttd-name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
                @endif
                NRP. {{ $nrpJuruBayar ?: '' }}
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                Jakarta, {{ ($pinjaman->processed_at ?? $pinjaman->created_at)->translatedFormat('d - m - Y') }}<br>
                Pemohon,
                <div class="ttd-space"></div>
                <span class="ttd-name">{{ $pinjaman->user->name }}</span><br>
                NIP/NRP. {{ $pinjaman->user->nip_nrp }}
            </td>
        </tr>
    </table>

    <table class="ttd" style="width: 100%; margin-top: 20px;">
        <tr>
            <td style="text-align: center; vertical-align: top;">
                Menyetujui,<br>
                Unit USIPA,
                <div class="ttd-space"></div>
                <span class="ttd-name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </td>
        </tr>
    </table>

</body>
</html>