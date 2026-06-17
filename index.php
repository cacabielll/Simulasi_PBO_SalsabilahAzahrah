<?php
require_once __DIR__ . '/PendaftaranReguler.php';
require_once __DIR__ . '/PendaftaranPrestasi.php';
require_once __DIR__ . '/PendaftaranKedinasan.php';

$reguler   = new PendaftaranReguler(0,'','',0,0,'','');
$prestasi  = new PendaftaranPrestasi(0,'','',0,0,'','');
$kedinasan = new PendaftaranKedinasan(0,'','',0,0,'','');

$dataReguler   = $reguler->getDaftarReguler($reguler->conn);
$dataPrestasi  = $prestasi->getDaftarPrestasi($prestasi->conn);
$dataKedinasan = $kedinasan->getDaftarKedinasan($kedinasan->conn);

function rupiahFormat($n){ return 'Rp '.number_format($n,0,',','.'); }

// Kumpulkan semua data ke array PHP supaya bisa di-filter JS
$allRows = [];
$dataReguler->data_seek(0);
while($r = $dataReguler->fetch_assoc()){
    $obj = new PendaftaranReguler($r['id_pendaftaran'],$r['nama_calon'],$r['asal_sekolah'],$r['nilai_ujian'],$r['biaya_pendaftaran_dasar'],$r['pilihan_prodi'],$r['lokasi_kampus']);
    $info  = $obj->tampilkanInfoJalur();
    $biaya = $obj->hitungTotalBiaya();
    $allRows[] = ['jalur'=>'Reguler','id'=>$r['id_pendaftaran'],'nama'=>$r['nama_calon'],'sekolah'=>$r['asal_sekolah'],'nilai'=>$r['nilai_ujian'],'dasar'=>$r['biaya_pendaftaran_dasar'],'final'=>$biaya,'info1'=>$info['pilihan_prodi'],'info2'=>$info['lokasi_kampus'],'ket'=>'Tarif standar','icon'=>'📋'];
}
$dataPrestasi->data_seek(0);
while($r = $dataPrestasi->fetch_assoc()){
    $obj = new PendaftaranPrestasi($r['id_pendaftaran'],$r['nama_calon'],$r['asal_sekolah'],$r['nilai_ujian'],$r['biaya_pendaftaran_dasar'],$r['jenis_prestasi'],$r['tingkat_prestasi']);
    $info  = $obj->tampilkanInfoJalur();
    $biaya = $obj->hitungTotalBiaya();
    $allRows[] = ['jalur'=>'Prestasi','id'=>$r['id_pendaftaran'],'nama'=>$r['nama_calon'],'sekolah'=>$r['asal_sekolah'],'nilai'=>$r['nilai_ujian'],'dasar'=>$r['biaya_pendaftaran_dasar'],'final'=>$biaya,'info1'=>$info['jenis_prestasi'],'info2'=>$info['tingkat_prestasi'],'ket'=>'Potongan Rp 50.000','icon'=>'🏆'];
}
$dataKedinasan->data_seek(0);
while($r = $dataKedinasan->fetch_assoc()){
    $obj = new PendaftaranKedinasan($r['id_pendaftaran'],$r['nama_calon'],$r['asal_sekolah'],$r['nilai_ujian'],$r['biaya_pendaftaran_dasar'],$r['sk_ikatan_dinas'],$r['instansi_sponsor']);
    $info  = $obj->tampilkanInfoJalur();
    $biaya = $obj->hitungTotalBiaya();
    $allRows[] = ['jalur'=>'Kedinasan','id'=>$r['id_pendaftaran'],'nama'=>$r['nama_calon'],'sekolah'=>$r['asal_sekolah'],'nilai'=>$r['nilai_ujian'],'dasar'=>$r['biaya_pendaftaran_dasar'],'final'=>$biaya,'info1'=>$info['sk_ikatan_dinas'],'info2'=>$info['instansi_sponsor'],'ket'=>'Surcharge +25%','icon'=>'🏛️'];
}

$totalReguler   = $dataReguler->num_rows;
$totalPrestasi  = $dataPrestasi->num_rows;
$totalKedinasan = $dataKedinasan->num_rows;
$totalAll       = $totalReguler + $totalPrestasi + $totalKedinasan;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem PMB</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
    --blue-50:  #eff6ff;
    --blue-100: #dbeafe;
    --blue-200: #bfdbfe;
    --blue-400: #60a5fa;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;
    --blue-700: #1d4ed8;
    --blue-900: #1e3a8a;
    --cream:    #faf9f7;
    --card-bg:  #ffffff;
    --text-1:   #1e293b;
    --text-2:   #64748b;
    --text-3:   #94a3b8;
    --sidebar-w: 72px;
    --radius-lg: 20px;
    --radius-md: 14px;
    --radius-sm: 10px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--blue-50);
    color: var(--text-1);
    display: flex;
    min-height: 100vh;
}

/* ── SIDEBAR ── */
.sidebar {
    width: var(--sidebar-w);
    background: var(--blue-700);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px 0;
    gap: 8px;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 100;
    border-radius: 0 24px 24px 0;
}

.sidebar-logo {
    width: 40px; height: 40px;
    background: var(--blue-500);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(37,99,235,0.4);
}

.nav-item {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s;
    color: var(--blue-200);
    position: relative;
}

.nav-item:hover   { background: rgba(255,255,255,0.12); }
.nav-item.active  { background: var(--blue-500); color: #fff; }

.sidebar-bottom { margin-top: auto; display: flex; flex-direction: column; align-items: center; gap: 8px; }

/* ── MAIN LAYOUT ── */
.main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    gap: 20px;
    padding: 24px 20px;
    min-height: 100vh;
}

/* ── LEFT CONTENT ── */
.content { flex: 1; display: flex; flex-direction: column; gap: 20px; min-width: 0; }

/* ── TOPBAR ── */
.topbar {
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-wrap {
    flex: 1;
    position: relative;
}

.search-wrap input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: none;
    border-radius: var(--radius-sm);
    background: var(--card-bg);
    font-size: 0.875rem;
    color: var(--text-1);
    outline: none;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    font-family: 'Inter', sans-serif;
}

.search-wrap input::placeholder { color: var(--text-3); }

.search-icon {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-3);
    font-size: 0.9rem;
    pointer-events: none;
}

.topbar-right { display: flex; align-items: center; gap: 10px; }

.icon-btn {
    width: 38px; height: 38px;
    border-radius: var(--radius-sm);
    background: var(--card-bg);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    transition: background 0.2s;
}

.icon-btn:hover { background: var(--blue-100); }

/* ── BANNER CARD ── */
.banner {
    background: linear-gradient(135deg, var(--blue-600) 0%, var(--blue-900) 100%);
    border-radius: var(--radius-lg);
    padding: 28px 32px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    min-height: 148px;
}

.banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}

.banner::after {
    content: '';
    position: absolute;
    bottom: -60px; right: 80px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}

.banner-text h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 6px; }
.banner-text p  { font-size: 0.875rem; opacity: 0.75; max-width: 320px; line-height: 1.5; }

.banner-stats {
    display: flex;
    gap: 20px;
    position: relative;
    z-index: 1;
}

.stat-pill {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 14px;
    padding: 12px 18px;
    text-align: center;
    backdrop-filter: blur(4px);
}

.stat-pill .num  { font-size: 1.5rem; font-weight: 700; }
.stat-pill .lbl  { font-size: 0.7rem; opacity: 0.8; margin-top: 2px; letter-spacing: 0.03em; }

/* ── TAB PILLS ── */
.tab-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.tab-pill {
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 0.82rem;
    font-weight: 500;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.18s;
    background: var(--card-bg);
    color: var(--text-2);
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.tab-pill:hover { border-color: var(--blue-200); color: var(--blue-600); }

.tab-pill.active {
    background: var(--blue-600);
    color: #fff;
    border-color: var(--blue-600);
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
}

/* ── CARD GRID ── */
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 14px;
}

.card {
    background: var(--card-bg);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: transform 0.18s, box-shadow 0.18s;
    cursor: default;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(37,99,235,0.12);
}

.card-thumb {
    height: 88px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    position: relative;
    overflow: hidden;
}

.thumb-reguler   { background: linear-gradient(135deg, #dbeafe, #eff6ff); }
.thumb-prestasi  { background: linear-gradient(135deg, #dcfce7, #f0fdf4); }
.thumb-kedinasan { background: linear-gradient(135deg, #fee2e2, #fff1f2); }

.card-icon {
    font-size: 2.2rem;
    line-height: 1;
}

.jalur-badge {
    font-size: 0.68rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.badge-reguler   { background: var(--blue-100); color: var(--blue-700); }
.badge-prestasi  { background: #dcfce7; color: #15803d; }
.badge-kedinasan { background: #fee2e2; color: #b91c1c; }

.card-body { padding: 14px 16px; }

.card-nama {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-1);
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-sekolah {
    font-size: 0.75rem;
    color: var(--text-2);
    margin-bottom: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-info {
    font-size: 0.75rem;
    color: var(--text-2);
    margin-bottom: 12px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.card-info span { display: flex; align-items: center; gap: 5px; }

.card-footer {
    border-top: 1px solid #f1f5f9;
    padding-top: 10px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}

.biaya-lama {
    font-size: 0.7rem;
    color: var(--text-3);
    text-decoration: line-through;
}

.biaya-ket {
    font-size: 0.68rem;
    color: var(--text-3);
    font-style: italic;
}

.biaya-final {
    font-size: 0.95rem;
    font-weight: 700;
}

.final-reguler   { color: var(--blue-600); }
.final-prestasi  { color: #16a34a; }
.final-kedinasan { color: #dc2626; }

.nilai-chip {
    font-size: 0.7rem;
    font-weight: 600;
    background: var(--blue-50);
    color: var(--blue-700);
    padding: 3px 8px;
    border-radius: 6px;
}

/* ── EMPTY STATE ── */
.empty {
    grid-column: 1/-1;
    text-align: center;
    padding: 48px 0;
    color: var(--text-3);
    font-size: 0.875rem;
}

/* ── RIGHT PANEL ── */
.right-panel {
    width: 240px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Profil */
.profile-card {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    padding: 20px 16px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.avatar {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-500), var(--blue-700));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    margin: 0 auto 10px;
}

.profile-name { font-size: 0.9rem; font-weight: 600; color: var(--text-1); }
.profile-role { font-size: 0.75rem; color: var(--text-2); margin-bottom: 12px; }

.profile-btn {
    display: inline-block;
    padding: 6px 18px;
    background: var(--blue-600);
    color: #fff;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
}

/* Ringkasan jalur */
.summary-card {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    padding: 18px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.summary-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-1);
    margin-bottom: 14px;
}

.summary-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.15s;
}

.summary-row:last-child { border-bottom: none; }
.summary-row:hover { background: var(--blue-50); padding-left: 6px; }

.sum-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.sum-icon-r { background: var(--blue-100); }
.sum-icon-p { background: #dcfce7; }
.sum-icon-k { background: #fee2e2; }

.sum-label { font-size: 0.78rem; color: var(--text-1); font-weight: 500; flex: 1; }
.sum-count { font-size: 0.75rem; font-weight: 600; color: var(--text-2); }
.sum-view  { font-size: 0.65rem; color: var(--blue-500); font-weight: 500; background: var(--blue-50); padding: 2px 8px; border-radius: 10px; }

/* No data search */
#no-result {
    display: none;
    grid-column: 1/-1;
    text-align: center;
    padding: 48px 0;
    color: var(--text-3);
    font-size: 0.875rem;
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">🎓</div>
    <div class="nav-item active" title="Dashboard">🏠</div>
    <div class="nav-item" title="Pendaftar">👤</div>
    <div class="nav-item" title="Jadwal">📅</div>
    <div class="nav-item" title="Dokumen">📄</div>
    <div class="nav-item" title="Statistik">📊</div>
    <div class="sidebar-bottom">
        <div class="nav-item" title="Pengaturan">⚙️</div>
    </div>
</aside>

<!-- MAIN -->
<div class="main">

    <!-- LEFT: content -->
    <div class="content">

        <!-- Topbar -->
        <div class="topbar">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Cari nama pendaftar atau asal sekolah...">
            </div>
            <div class="topbar-right">
                <div class="icon-btn">🔔</div>
                <div class="icon-btn">📌</div>
            </div>
        </div>

        <!-- Banner -->
        <div class="banner">
            <div class="banner-text">
                <h2>Pendaftaran Mahasiswa Baru</h2>
                <p>Sistem manajemen jalur penerimaan mahasiswa baru — Reguler, Prestasi, dan Kedinasan.</p>
            </div>
            <div class="banner-stats">
                <div class="stat-pill">
                    <div class="num"><?= $totalAll ?></div>
                    <div class="lbl">TOTAL</div>
                </div>
                <div class="stat-pill">
                    <div class="num"><?= $totalReguler ?></div>
                    <div class="lbl">REGULER</div>
                </div>
                <div class="stat-pill">
                    <div class="num"><?= $totalPrestasi ?></div>
                    <div class="lbl">PRESTASI</div>
                </div>
                <div class="stat-pill">
                    <div class="num"><?= $totalKedinasan ?></div>
                    <div class="lbl">KEDINASAN</div>
                </div>
            </div>
        </div>

        <!-- Tab Pills -->
        <div class="tab-row">
            <div class="tab-pill active" onclick="filterTab('semua', this)">Semua Jalur</div>
            <div class="tab-pill" onclick="filterTab('Reguler', this)">📋 Reguler</div>
            <div class="tab-pill" onclick="filterTab('Prestasi', this)">🏆 Prestasi</div>
            <div class="tab-pill" onclick="filterTab('Kedinasan', this)">🏛️ Kedinasan</div>
        </div>

        <!-- Card Grid -->
        <div class="card-grid" id="cardGrid">

            <?php foreach($allRows as $r):
                $jalurLower = strtolower($r['jalur']);
                $isDasar    = $r['dasar'] == $r['final'];
            ?>
            <div class="card" data-jalur="<?= $r['jalur'] ?>" data-nama="<?= strtolower($r['nama']) ?>" data-sekolah="<?= strtolower($r['sekolah']) ?>">
                <div class="card-thumb thumb-<?= $jalurLower ?>">
                    <span class="card-icon"><?= $r['icon'] ?></span>
                    <span class="jalur-badge badge-<?= $jalurLower ?>"><?= $r['jalur'] ?></span>
                </div>
                <div class="card-body">
                    <div class="card-nama"><?= htmlspecialchars($r['nama']) ?></div>
                    <div class="card-sekolah">🏫 <?= htmlspecialchars($r['sekolah']) ?></div>
                    <div class="card-info">
                        <?php if($r['info1'] && $r['info1'] !== '-'): ?>
                        <span>📌 <?= htmlspecialchars($r['info1']) ?></span>
                        <?php endif; ?>
                        <?php if($r['info2'] && $r['info2'] !== '-'): ?>
                        <span>📍 <?= htmlspecialchars($r['info2']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <div>
                            <?php if(!$isDasar): ?>
                            <div class="biaya-lama"><?= rupiahFormat($r['dasar']) ?></div>
                            <div class="biaya-ket"><?= $r['ket'] ?></div>
                            <?php else: ?>
                            <div class="biaya-ket"><?= $r['ket'] ?></div>
                            <?php endif; ?>
                            <div class="biaya-final final-<?= $jalurLower ?>"><?= rupiahFormat($r['final']) ?></div>
                        </div>
                        <div class="nilai-chip">Nilai <?= $r['nilai'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div id="no-result" class="empty">Tidak ada data yang cocok.</div>
        </div>

    </div><!-- /content -->

    <!-- RIGHT PANEL -->
    <div class="right-panel">

        <!-- Profile -->
        <div class="profile-card">
            <div class="avatar">👩‍💼</div>
            <div class="profile-name">Admin PMB</div>
            <div class="profile-role">Operator Pendaftaran</div>
            <button class="profile-btn">Profil Saya</button>
        </div>

        <!-- Ringkasan Jalur -->
        <div class="summary-card">
            <div class="summary-title">Ringkasan Jalur</div>

            <div class="summary-row" onclick="filterTab('Reguler', null)">
                <div class="sum-icon sum-icon-r">📋</div>
                <div class="sum-label">Reguler</div>
                <div class="sum-count"><?= $totalReguler ?></div>
                <div class="sum-view">Lihat</div>
            </div>

            <div class="summary-row" onclick="filterTab('Prestasi', null)">
                <div class="sum-icon sum-icon-p">🏆</div>
                <div class="sum-label">Prestasi</div>
                <div class="sum-count"><?= $totalPrestasi ?></div>
                <div class="sum-view">Lihat</div>
            </div>

            <div class="summary-row" onclick="filterTab('Kedinasan', null)">
                <div class="sum-icon sum-icon-k">🏛️</div>
                <div class="sum-label">Kedinasan</div>
                <div class="sum-count"><?= $totalKedinasan ?></div>
                <div class="sum-view">Lihat</div>
            </div>
        </div>

    </div><!-- /right-panel -->

</div><!-- /main -->

<script>
let activeTab = 'semua';

function filterTab(jalur, btn) {
    activeTab = jalur;

    // update tab pills
    if (btn) {
        document.querySelectorAll('.tab-pill').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
    } else {
        // dipanggil dari right panel
        document.querySelectorAll('.tab-pill').forEach(el => {
            el.classList.remove('active');
            if (el.textContent.includes(jalur)) el.classList.add('active');
        });
    }

    applyFilter();
}

function applyFilter() {
    const q     = document.getElementById('searchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.card');
    let visible = 0;

    cards.forEach(card => {
        const matchTab    = activeTab === 'semua' || card.dataset.jalur === activeTab;
        const matchSearch = !q || card.dataset.nama.includes(q) || card.dataset.sekolah.includes(q);
        const show        = matchTab && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('no-result').style.display = visible === 0 ? 'grid' : 'none';
}

document.getElementById('searchInput').addEventListener('input', applyFilter);
</script>

</body>
</html>