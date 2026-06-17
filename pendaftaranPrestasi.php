<?php

require_once __DIR__ . '/Pendaftaran.php';

class PendaftaranPrestasi extends Pendaftaran {

    // Properti tambahan spesifik jalur Prestasi
    protected $jenisPrestasi;
    protected $tingkatPrestasi;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar, $jenisPrestasi, $tingkatPrestasi) {
        parent::__construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar);

        $this->jenisPrestasi   = $jenisPrestasi;
        $this->tingkatPrestasi = $tingkatPrestasi;
    }

    // Metode query spesifik - ambil semua data jalur Prestasi dari database
    public function getDaftarPrestasi($db) {
        $query  = "SELECT * FROM tabel_pendaftaran WHERE jalur_pendaftaran = 'Prestasi'";
        $result = $db->query($query);
        return $result;
    }

    // Tahap 5: Polymorphism - overriding hitungTotalBiaya()
    // Implementasi abstract method: total biaya = biaya dasar - 50000 (potongan prestasi)
    public function hitungTotalBiaya() {
        return $this->biayaPendaftaranDasar - 50000;
    }

    // Implementasi abstract method: tampilkan info unik jalur Prestasi
    public function tampilkanInfoJalur() {
        return [
            'jalur'           => 'Prestasi',
            'jenis_prestasi'  => $this->jenisPrestasi  ?? '-',
            'tingkat_prestasi'=> $this->tingkatPrestasi ?? '-',
        ];
    }
}