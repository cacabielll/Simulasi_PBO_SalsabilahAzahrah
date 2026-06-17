<?php

require_once __DIR__ . '/PendaftaranReguler.php';
require_once __DIR__ . '/PendaftaranPrestasi.php';
require_once __DIR__ . '/PendaftaranKedinasan.php';

// Buat objek dummy tiap jalur hanya untuk mengakses method query & conn
$reguler   = new PendaftaranReguler(0, '', '', 0, 0, '', '');
$prestasi  = new PendaftaranPrestasi(0, '', '', 0, 0, '', '');
$kedinasan = new PendaftaranKedinasan(0, '', '', 0, 0, '', '');

// Ambil data dari database via method query spesifik masing-masing jalur
$dataReguler   = $reguler->getDaftarReguler($reguler->conn);
$dataPrestasi  = $prestasi->getDaftarPrestasi($prestasi->conn);
$dataKedinasan = $kedinasan->getDaftarKedinasan($kedinasan->conn);

// Helper: format angka ke Rupiah
function rupiahFormat($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PMB - Pendaftaran Mahasiswa Baru</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #333;
            padding: 30px 20px;
        }

        h1 {
            text-align: center;
            font-size: 1.6rem;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 36px;
        }

        .section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 36px;
            overflow: hidden;
        }

        .section-header {
            padding: 14px 20px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-reguler   { background: #2563eb; }
        .header-prestasi  { background: #16a34a; }
        .header-kedinasan { background: #dc2626; }

        .badge {
            background: rgba(255,255,255,0.25);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.8rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        thead tr { background: #f8f9fa; }

        th {
            padding: 10px 14px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        .null-val {
            color: #bbb;
            font-style: italic;
            font-size: 0.8rem;
        }

        .total-biaya {
            font-weight: 600;
        }

        .total-reguler   { color: #2563eb; }
        .total-prestasi  { color: #16a34a; }
        .total-kedinasan { color: #dc2626; }

        .info-jalur {
            font-size: 0.78rem;
            color: #777;
            margin-top: 3px;
        }

        .no-data {
            padding: 20px;
            text-align: center;
            color: #aaa;
            font-style: italic;
        }
    </style>
</head>
<body>

<h1>Sistem Manajemen Pendaftaran Mahasiswa Baru</h1>
<p class="subtitle">Data Calon Mahasiswa Berdasarkan Jalur Pendaftaran</p>


<!-- ========================================= -->
<!-- SEKSI 1: JALUR REGULER                    -->
<!-- ========================================= -->
<div class="section">
    <div class="section-header header-reguler">
        Jalur Reguler
        <span class="badge"><?= $dataReguler->num_rows ?> Pendaftar</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Calon</th>
                <th>Asal Sekolah</th>
                <th>Nilai Ujian</th>
                <th>Info Jalur</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($dataReguler->num_rows > 0): ?>
            <?php while ($row = $dataReguler->fetch_assoc()):
                // Buat objek per baris untuk memanfaatkan method polimorfik
                $obj  = new PendaftaranReguler(
                    $row['id_pendaftaran'],
                    $row['nama_calon'],
                    $row['asal_sekolah'],
                    $row['nilai_ujian'],
                    $row['biaya_pendaftaran_dasar'],
                    $row['pilihan_prodi'],
                    $row['lokasi_kampus']
                );
                $info  = $obj->tampilkanInfoJalur();
                $biaya = $obj->hitungTotalBiaya();
            ?>
            <tr>
                <td><?= $row['id_pendaftaran'] ?></td>
                <td><?= htmlspecialchars($row['nama_calon']) ?></td>
                <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                <td><?= $row['nilai_ujian'] ?></td>
                <td>
                    <div><?= htmlspecialchars($info['pilihan_prodi']) ?></div>
                    <div class="info-jalur">📍 <?= htmlspecialchars($info['lokasi_kampus']) ?></div>
                </td>
                <td class="total-biaya total-reguler"><?= rupiahFormat($biaya) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="no-data">Tidak ada data jalur Reguler.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- ========================================= -->
<!-- SEKSI 2: JALUR PRESTASI                   -->
<!-- ========================================= -->
<div class="section">
    <div class="section-header header-prestasi">
        Jalur Prestasi
        <span class="badge"><?= $dataPrestasi->num_rows ?> Pendaftar</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Calon</th>
                <th>Asal Sekolah</th>
                <th>Nilai Ujian</th>
                <th>Info Jalur</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($dataPrestasi->num_rows > 0): ?>
            <?php while ($row = $dataPrestasi->fetch_assoc()):
                $obj  = new PendaftaranPrestasi(
                    $row['id_pendaftaran'],
                    $row['nama_calon'],
                    $row['asal_sekolah'],
                    $row['nilai_ujian'],
                    $row['biaya_pendaftaran_dasar'],
                    $row['jenis_prestasi'],
                    $row['tingkat_prestasi']
                );
                $info  = $obj->tampilkanInfoJalur();
                $biaya = $obj->hitungTotalBiaya();
            ?>
            <tr>
                <td><?= $row['id_pendaftaran'] ?></td>
                <td><?= htmlspecialchars($row['nama_calon']) ?></td>
                <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                <td><?= $row['nilai_ujian'] ?></td>
                <td>
                    <div><?= htmlspecialchars($info['jenis_prestasi']) ?></div>
                    <div class="info-jalur">🏆 <?= htmlspecialchars($info['tingkat_prestasi']) ?></div>
                </td>
                <td class="total-biaya total-prestasi"><?= rupiahFormat($biaya) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="no-data">Tidak ada data jalur Prestasi.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- ========================================= -->
<!-- SEKSI 3: JALUR KEDINASAN                  -->
<!-- ========================================= -->
<div class="section">
    <div class="section-header header-kedinasan">
        Jalur Kedinasan
        <span class="badge"><?= $dataKedinasan->num_rows ?> Pendaftar</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Calon</th>
                <th>Asal Sekolah</th>
                <th>Nilai Ujian</th>
                <th>Info Jalur</th>
                <th>Total Biaya</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($dataKedinasan->num_rows > 0): ?>
            <?php while ($row = $dataKedinasan->fetch_assoc()):
                $obj  = new PendaftaranKedinasan(
                    $row['id_pendaftaran'],
                    $row['nama_calon'],
                    $row['asal_sekolah'],
                    $row['nilai_ujian'],
                    $row['biaya_pendaftaran_dasar'],
                    $row['sk_ikatan_dinas'],
                    $row['instansi_sponsor']
                );
                $info  = $obj->tampilkanInfoJalur();
                $biaya = $obj->hitungTotalBiaya();
            ?>
            <tr>
                <td><?= $row['id_pendaftaran'] ?></td>
                <td><?= htmlspecialchars($row['nama_calon']) ?></td>
                <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                <td><?= $row['nilai_ujian'] ?></td>
                <td>
                    <div><?= htmlspecialchars($info['sk_ikatan_dinas']) ?></div>
                    <div class="info-jalur">🏛️ <?= htmlspecialchars($info['instansi_sponsor']) ?></div>
                </td>
                <td class="total-biaya total-kedinasan"><?= rupiahFormat($biaya) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="no-data">Tidak ada data jalur Kedinasan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>