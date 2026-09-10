<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Seminar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
        .header { text-align: center; margin-bottom: 24px; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: bold; margin-bottom: 8px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Berita Acara Seminar Proposal</h2>
        <p>Dokumen ini disetujui secara elektronik melalui PAMASESA</p>
    </div>

    <div class="section">
        <div class="section-title">Data Seminar</div>
        <table class="table">
            <tbody>
                <tr><th>Nama Mahasiswa</th><td>{{ $seminarSchedule->seminarProposal->finalProject->student->nama }}</td></tr>
                <tr><th>NIM</th><td>{{ $seminarSchedule->seminarProposal->finalProject->student->nim }}</td></tr>
                <tr><th>Judul PA</th><td>{{ $seminarSchedule->seminarProposal->finalProject->title }}</td></tr>
                <tr><th>Tanggal Seminar</th><td>{{ $seminarSchedule->date }}</td></tr>
                <tr><th>Ruangan</th><td>{{ $seminarSchedule->room?->name ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Pembimbing</div>
        <table class="table">
            <tbody>
                <tr><th>Pembimbing 1</th><td>{{ $seminarSchedule->seminarProposal->finalProject->supervisorOne()?->lecturer?->nama ?? '-' }}</td></tr>
                <tr><th>Pembimbing 2</th><td>{{ $seminarSchedule->seminarProposal->finalProject->supervisorTwo()?->lecturer?->nama ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Hasil Penilaian</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Pembimbing</th>
                    <th>Nilai Akhir</th>
                    <th>Keputusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seminarSchedule->assessments as $assessment)
                    <tr>
                        <td>{{ $assessment->lecturer->nama }}</td>
                        <td>{{ $assessment->total_score }} / 100</td>
                        <td>{{ $assessment->decision }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Catatan Revisi</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Dari</th>
                </tr>
            </thead>
            <tbody>
                @forelse($seminarSchedule->revisions as $revision)
                    <tr>
                        <td>{{ $revision->revision_category }}</td>
                        <td>{{ $revision->revision_note }}</td>
                        <td>{{ $revision->status }}</td>
                        <td>{{ $revision->lecturer->nama }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Tidak ada catatan revisi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
