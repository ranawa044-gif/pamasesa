<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pengajuan Judul - {{ $student?->nim ?? $user->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
        }
        .header-doc {
            text-align: right;
            font-size: 10pt;
            margin-bottom: 25px;
        }
        .header-doc table {
            float: right;
            border-collapse: collapse;
        }
        .header-doc td {
            padding: 1px 4px;
        }
        .clearfix {
            clear: both;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 6px;
            font-size: 12pt;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .table-data td {
            vertical-align: top;
            padding: 3px 0;
        }
        .table-data td.label {
            width: 180px;
        }
        .table-data td.separator {
            width: 20px;
            text-align: center;
        }
        .checkbox-box {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            text-align: center;
            line-height: 12px;
            font-size: 10pt;
            margin-right: 6px;
            font-weight: bold;
        }
        .skema-list {
            margin-bottom: 15px;
            padding-left: 5px;
        }
        .skema-item {
            margin-bottom: 5px;
        }
        .content-box {
            border: 1px solid #000;
            padding: 10px 12px;
            margin-top: 5px;
            margin-bottom: 15px;
            min-height: 40px;
        }
        .text-justify {
            text-align: justify;
            text-justify: inter-word;
        }
        .note {
            font-size: 10pt;
            font-style: italic;
            color: #333;
            margin-bottom: 4px;
        }
        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .table-detail th,
        .table-detail td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
            font-size: 11pt;
        }
        .table-detail td.label-col {
            width: 28%;
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .table-detail tr {
            page-break-inside: avoid;
        }
        .footer-sign {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        .footer-sign td {
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>
<body>

    {{-- HEADER KANAN ATAS --}}
    <div class="header-doc">
        <table>
            <tr>
                <td style="text-align: left;">Tanggal Berlaku</td>
                <td>:</td>
                <td style="text-align: left;">17 November 2025</td>
            </tr>
            <tr>
                <td style="text-align: left;">Kode Dokumen</td>
                <td>:</td>
                <td style="text-align: left;">-</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>

    {{-- JUDUL TENGAH --}}
    <div class="title">
        FORMULIR PENGAJUAN JUDUL
    </div>

    {{-- SECTION A. IDENTITAS MAHASISWA --}}
    <div class="section-title">A. IDENTITAS MAHASISWA</div>
    <table class="table-data">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="separator">:</td>
            <td><strong>{{ $student?->nama ?? $user->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="separator">:</td>
            <td>{{ $student?->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="separator">:</td>
            <td>{{ $student?->kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. HP/WA</td>
            <td class="separator">:</td>
            <td>{{ $student?->phone ?? '-' }}</td>
        </tr>
        @if(!empty($student?->guardian_phone))
        <tr>
            <td class="label">No. HP Orang Tua/Kerabat</td>
            <td class="separator">:</td>
            <td>{{ $student->guardian_phone }}</td>
        </tr>
        @endif
    </table>

    {{-- SECTION B. SKEMA PROYEK AKHIR --}}
    <div class="section-title">B. SKEMA PROYEK AKHIR</div>
    <div class="note">Pilih salah satu skema (tanda &#10003; pada skema yang diajukan):</div>
    <div class="skema-list">
        <div class="skema-item">
            <span class="checkbox-box">{!! $pengajuan->jenis_skema === 'perancangan' ? '&#10003;' : '&nbsp;' !!}</span>
            Skema Perancangan
        </div>
        <div class="skema-item">
            <span class="checkbox-box">{!! $pengajuan->jenis_skema === 'implementasi' ? '&#10003;' : '&nbsp;' !!}</span>
            Skema Implementasi
        </div>
        <div class="skema-item">
            <span class="checkbox-box">{!! $pengajuan->jenis_skema === 'prestasi' ? '&#10003;' : '&nbsp;' !!}</span>
            Skema Prestasi (Kompetisi/Rekognisi)
        </div>
    </div>

    {{-- SECTION C. USULAN JUDUL --}}
    <div class="section-title">C. USULAN JUDUL</div>
    <div class="note">Tuliskan judul usulan penelitian maksimal 20 kata.</div>
    <div class="content-box">
        <strong>
            @if($pengajuan->jenis_skema === 'prestasi')
                {{ $detail?->nama_lomba ?? 'Belum ada nama kegiatan' }} (Skema Prestasi)
            @else
                {{ $detail?->judul_pa ?? 'Belum mengisi judul' }}
            @endif
        </strong>
    </div>

    @if($detail && isset($detail->dosen_pembimbing))
    <table class="table-data" style="margin-top: -5px; margin-bottom: 10px;">
        <tr>
            <td class="label" style="width: 180px;">Usulan Dosen Pembimbing</td>
            <td class="separator">:</td>
            <td>{{ $detail->dosen_pembimbing }}</td>
        </tr>
        @if(isset($detail->lokasi_penelitian))
        <tr>
            <td class="label">Lokasi/Objek Penelitian</td>
            <td class="separator">:</td>
            <td>{{ $detail->lokasi_penelitian }}</td>
        </tr>
        @endif
    </table>
    @endif

    {{-- SECTION D. PENDAHULUAN --}}
    <div class="section-title">D. PENDAHULUAN</div>
    <div class="content-box text-justify" style="min-height: 120px;">
        @if($pengajuan->jenis_skema === 'prestasi')
            <strong>Penyelenggara:</strong> {{ $detail?->penyelenggara ?? '-' }}<br>
            <strong>Tingkat Kompetisi:</strong> {{ $detail?->tingkat ?? '-' }}<br>
            <strong>Tanggal Pelaksanaan:</strong> {{ $detail?->tanggal_pelaksanaan?->translatedFormat('d F Y') ?? '-' }}<br>
            <strong>URL Karya/Produk:</strong> {{ $detail?->url_produk ?? '-' }}
        @else
            {!! nl2br(e($detail?->pendahuluan ?? 'Belum mengisi pendahuluan')) !!}
        @endif
    </div>

    {{-- SECTION E. DETAIL SISTEM --}}
    <div class="section-title">E. DETAIL SISTEM</div>

    @if($pengajuan->jenis_skema === 'perancangan')
        @php
            $dp = $pengajuan->detailPerancangan;
        @endphp
        <table class="table-detail" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <td class="label-col">Jenis Sistem</td>
                <td>{{ $dp?->jenis_sistem ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Proses Bisnis Sistem</td>
                <td class="text-justify">{!! nl2br(e($dp?->proses_bisnis ?? '-')) !!}</td>
            </tr>
            <tr>
                <td class="label-col">Jumlah Aktor</td>
                <td>{{ $dp?->jumlah_aktor ? $dp->jumlah_aktor . ' Aktor' : '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Metode Pengembangan</td>
                <td>{{ $dp?->metode_pengembangan ?? '-' }}</td>
            </tr>
            @if(!empty($dp?->metode_pendekatan))
            <tr>
                <td class="label-col">Metode / Pendekatan Khusus</td>
                <td>
                    @if(is_array($dp->metode_pendekatan))
                        {{ implode(', ', $dp->metode_pendekatan) }}
                    @else
                        {{ $dp->metode_pendekatan }}
                    @endif
                </td>
            </tr>
            @endif
            <tr>
                <td class="label-col">Fitur Utama</td>
                <td class="text-justify">{!! nl2br(e($dp?->fitur_utama ?? '-')) !!}</td>
            </tr>
            <tr>
                <td class="label-col">Tools Perancangan</td>
                <td>{{ $dp?->tools_perancangan ?? '-' }}</td>
            </tr>
        </table>

    @elseif($pengajuan->jenis_skema === 'implementasi')
        @php
            $di = $pengajuan->detailImplementasi;
        @endphp
        <table class="table-detail" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <td class="label-col">Jenis Sistem</td>
                <td>{{ $di?->jenis_sistem ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Proses Bisnis Sistem</td>
                <td class="text-justify">{!! nl2br(e($di?->proses_bisnis ?? '-')) !!}</td>
            </tr>
            <tr>
                <td class="label-col">Jumlah Aktor</td>
                <td>{{ $di?->jumlah_aktor ? $di->jumlah_aktor . ' Aktor' : '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Metode Pengembangan</td>
                <td>{{ $di?->metode_pengembangan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Fitur Utama</td>
                <td class="text-justify">{!! nl2br(e($di?->fitur_utama ?? '-')) !!}</td>
            </tr>
            <tr>
                <td class="label-col">Stack Teknologi</td>
                <td>{{ $di?->teknologi ?? '-' }}</td>
            </tr>
            @if(!empty($di?->metode_pendekatan))
            <tr>
                <td class="label-col">Metode / Pendekatan Khusus</td>
                <td>
                    @if(is_array($di->metode_pendekatan))
                        {{ implode(', ', $di->metode_pendekatan) }}
                    @else
                        {{ $di->metode_pendekatan }}
                    @endif
                </td>
            </tr>
            @endif
            @if(!empty($di?->rencana_pengujian))
            <tr>
                <td class="label-col">Rencana Pengujian</td>
                <td>
                    @if(is_array($di->rencana_pengujian))
                        {{ implode(', ', $di->rencana_pengujian) }}
                    @else
                        {{ $di->rencana_pengujian }}
                    @endif
                </td>
            </tr>
            @endif
        </table>

    @elseif($pengajuan->jenis_skema === 'prestasi')
        @php
            $dpr = $pengajuan->detailPrestasi;
        @endphp
        <table class="table-detail" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <td class="label-col">Nama Kompetisi / Lomba</td>
                <td>{{ $dpr?->nama_lomba ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Penyelenggara</td>
                <td>{{ $dpr?->penyelenggara ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tingkat Kompetisi</td>
                <td>{{ $dpr?->tingkat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Pelaksanaan</td>
                <td>{{ $dpr?->tanggal_pelaksanaan?->translatedFormat('d F Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">URL Karya / Produk</td>
                <td>{{ $dpr?->url_produk ?? '-' }}</td>
            </tr>
        </table>
    @endif

    {{-- TANDA TANGAN --}}
    <table class="footer-sign">
        <tr>
            <td></td>
            <td style="text-align: center;">
                Semarang, {{ $pengajuan->created_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}<br>
                Mahasiswa Pengusul,
                <br><br><br><br><br>
                <strong><u>{{ $student?->nama ?? $user->name }}</u></strong><br>
                NIM. {{ $student?->nim ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
