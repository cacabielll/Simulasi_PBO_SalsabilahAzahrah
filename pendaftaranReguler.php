<?php

require_once __DIR__ . '/Pendaftaran.php';

class PendaftaranReguler extends Pendaftaran {

    // Properti tambahan spesifik jalur Reguler
    protected $pilihanProdi;
    protected $lokasiKampus;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar, $pilihanProdi, $lokasiKampus) {
        parent::__construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar);

        $this->pilihanProdi = $pilihanProdi;
        $this->lokasiKampus = $lokasiKampus;
    }

    // Metode query spesifik - ambil semua data jalur Reguler dari database
    public function getDaftarReguler($db) {
        $query  = "SELECT * FROM tabel_pendaftaran WHERE jalur_pendaftaran = 'Reguler'";
        $result = $db->query($query);
        return $result;
    }

    // Tahap 5: Polymorphism - overriding hitungTotalBiaya()
    // Total Biaya = biayaPendaftaranDasar (tarif standar, tanpa biaya tambahan)
    public function hitungTotalBiaya() {
        return $this->biayaPendaftaranDasar;
    }

    // Implementasi abstract method: tampilkan info unik jalur Reguler
    public function tampilkanInfoJalur() {
        return [
            'jalur'         => 'Reguler',
            'pilihan_prodi' => $this->pilihanProdi ?? '-',
            'lokasi_kampus' => $this->lokasiKampus ?? '-',
        ];
    }
    
}