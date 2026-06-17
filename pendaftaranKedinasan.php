<?php

require_once __DIR__ . '/Pendaftaran.php';

class PendaftaranKedinasan extends Pendaftaran {

    // Properti tambahan spesifik jalur Kedinasan
    protected $skIkatanDinas;
    protected $instansiSponsor;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar, $skIkatanDinas, $instansiSponsor) {
        parent::__construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar);

        $this->skIkatanDinas   = $skIkatanDinas;
        $this->instansiSponsor = $instansiSponsor;
    }

    // Metode query spesifik - ambil semua data jalur Kedinasan dari database
    public function getDaftarKedinasan($db) {
        $query  = "SELECT * FROM tabel_pendaftaran WHERE jalur_pendaftaran = 'Kedinasan'";
        $result = $db->query($query);
        return $result;
    }

    // Tahap 5: Polymorphism - overriding hitungTotalBiaya()
    // Implementasi abstract method: total biaya = biaya dasar * 1.25 (surcharge 25%)
    public function hitungTotalBiaya() {
        return $this->biayaPendaftaranDasar * 1.25;
    }

    // Implementasi abstract method: tampilkan info unik jalur Kedinasan
    public function tampilkanInfoJalur() {
        return [
            'jalur'           => 'Kedinasan',
            'sk_ikatan_dinas' => $this->skIkatanDinas   ?? '-',
            'instansi_sponsor'=> $this->instansiSponsor ?? '-',
        ];
    }
}